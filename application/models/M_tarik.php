<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class M_tarik extends CI_Model {

    /**
     * Cari nasabah + saldo untuk halaman tarik.
     * 
     * PERUBAHAN: kolom `saldo` di tabel tabungan adalah cache (mungkin stale 
     * kalau harga sampah berubah). Untuk akurasi, kita JOIN ke 
     * v_saldo_dinamis_nasabah supaya saldo selalu real-time.
     */
    public function cariUserSaldo($keyword) {
        $sql = "SELECT 
                    u.id_user,
                    u.username,
                    u.email,
                    t.id_tabungan,
                    COALESCE(v.saldo_dinamis, 0) AS saldo
                FROM user u
                JOIN tabungan t ON u.id_user = t.id_user_nasabah
                LEFT JOIN v_saldo_dinamis_nasabah v 
                    ON v.id_user_nasabah = u.id_user
                WHERE u.username LIKE ? OR u.id_user LIKE ?
                ORDER BY u.username DESC";

        $like = '%' . $keyword . '%';
        return $this->db->query($sql, array($like, $like));
    }

    /**
     * Proses tarik saldo dengan logika FIFO.
     * 
     * Langkah:
     * 1. Validasi saldo dinamis cukup
     * 2. Insert header tabungan_transaksi (kredit = nominal tarik)
     * 3. Loop kantong tertua → gerus berat_sisa → catat ke penarikan_detail
     * 4. Update tabungan.saldo (cache)
     * 5. Semua dalam DB transaction (atomic)
     *
     * @return array ['success' => bool, 'message' => string, 'detail' => array]
     */
    public function prosesTarikFifo(){
        date_default_timezone_set('Asia/Makassar');

        $id_tabungan  = intval($this->input->post('id_tabungan'));
        $nominal      = floatval($this->input->post('tariksaldo'));
        $id_staff     = $this->session->userdata('id');

        // === STEP 1: Validasi ===
        if($nominal <= 0){
            return ['success' => false, 'message' => 'Nominal tidak valid.'];
        }

        // Ambil id_user_nasabah dari id_tabungan
        $this->db->select('id_user_nasabah');
        $this->db->where('id_tabungan', $id_tabungan);
        $tab = $this->db->get('tabungan');
        if($tab->num_rows() == 0){
            return ['success' => false, 'message' => 'Tabungan tidak ditemukan.'];
        }
        $id_user_nasabah = $tab->row()->id_user_nasabah;

        // Cek saldo dinamis cukup
        $this->load->model('m_dashboard');
        $saldo_sekarang = $this->m_dashboard->getSaldoDinamis($id_user_nasabah);
        if($nominal > $saldo_sekarang){
            return [
                'success' => false, 
                'message' => 'Saldo tidak mencukupi. Saldo Anda: Rp ' . number_format($saldo_sekarang, 0, ',', '.')
            ];
        }

        // === STEP 2: Mulai transaction ===
        $this->db->trans_start();

        // Insert header tabungan_transaksi
        $header = array(
            'id_tabungan'    => $id_tabungan,
            'id_user_staff'  => $id_staff,
            'kredit'         => $nominal,  
            'debit'          => 0,
            'banjar_id'      => $this->session->userdata('banjar_id'), 
            'tgl_tabungan_transaksi' => date('Y-m-d H:i:s')
        );
        $this->db->insert('tabungan_transaksi', $header);
        $id_tabungan_transaksi = $this->db->insert_id();

        // === STEP 3: Loop FIFO ===
        // Ambil semua kantong nasabah yang masih punya sisa, urut paling lama
        $sql = "SELECT 
                    tsd.id_transaksi_sampahDetail AS id_kantong,
                    tsd.berat_sisa,
                    tsd.id_jenis_sampah,
                    js.harga_sampah AS harga_sekarang
                FROM transaksi_sampahdetail tsd
                JOIN transaksi_sampah ts 
                    ON ts.id_transaksi_sampah = tsd.id_transaksi_sampah
                JOIN jenis_sampah js 
                    ON js.id = tsd.id_jenis_sampah
                WHERE ts.id_user_nasabah = ?
                    AND tsd.berat_sisa > 0
                ORDER BY ts.tgl_transaksi ASC, tsd.id_transaksi_sampahDetail ASC";

        $kantong = $this->db->query($sql, array($id_user_nasabah))->result();

        $sisa_kebutuhan = $nominal;
        $detail_terambil = array();

        foreach($kantong as $k){
            if($sisa_kebutuhan <= 0) break;

            $nilai_kantong = $k->berat_sisa * $k->harga_sekarang;

            if($nilai_kantong <= $sisa_kebutuhan){
                // Habiskan seluruh kantong ini
                $berat_terambil = $k->berat_sisa;
                $nilai_terambil = $nilai_kantong;
                $berat_sisa_baru = 0;
            } else {
                // Ambil sebagian saja
                $berat_terambil = $sisa_kebutuhan / $k->harga_sekarang;
                $nilai_terambil = $sisa_kebutuhan;
                $berat_sisa_baru = $k->berat_sisa - $berat_terambil;
            }

            // Update berat_sisa di kantong
            $this->db->set('berat_sisa', $berat_sisa_baru);
            $this->db->where('id_transaksi_sampahDetail', $k->id_kantong);
            $this->db->update('transaksi_sampahdetail');

            // Catat ke penarikan_detail (jejak FIFO)
            $this->db->insert('penarikan_detail', array(
                'id_tabungan_transaksi'     => $id_tabungan_transaksi,
                'id_transaksi_sampahdetail' => $k->id_kantong,
                'berat_terambil'            => $berat_terambil,
                'harga_saat_tarik'          => $k->harga_sekarang,
                'nilai_terambil'            => intval(round($nilai_terambil)),
                'tgl_dibuat'                => date('Y-m-d H:i:s')
            ));

            $detail_terambil[] = array(
                'id_kantong'     => $k->id_kantong,
                'berat_terambil' => $berat_terambil,
                'nilai_terambil' => $nilai_terambil,
            );

            $sisa_kebutuhan -= $nilai_terambil;
        }

        // === STEP 4: Update cache saldo ===
        $this->db->set('saldo', 'saldo - ' . intval($nominal), FALSE);
        $this->db->where('id_tabungan', $id_tabungan);
        $this->db->update('tabungan');

        // === STEP 5: Commit / rollback ===
        $this->db->trans_complete();

        if($this->db->trans_status() === FALSE){
            return ['success' => false, 'message' => 'Transaksi gagal, silakan coba lagi.'];
        }

        return [
            'success'  => true,
            'message'  => 'Penarikan berhasil',
            'id_trans' => $id_tabungan_transaksi,
            'detail'   => $detail_terambil
        ];
    }

    /**
     * @deprecated 
     * Method lama yang langsung update saldo tanpa FIFO. 
     * Digantikan oleh prosesTarikFifo(). Disimpan untuk referensi.
     */
    public function insertTrTarik(){
        date_default_timezone_set('Asia/Makassar');

        $data = array(
            'id_tabungan' => $this->input->post('id_tabungan'),
            'id_user_staff' => $this->session->userdata('id'),
            'kredit' => $this->input->post('tariksaldo'),
            'debit' => 0,
            'tgl_tabungan_transaksi' => date('Y-m-d H:i:s')
        );
        return $this->db->insert('tabungan_transaksi', $data);
    }

    /**
     * @deprecated digantikan logika FIFO di prosesTarikFifo()
     */
    public function updateSaldo(){
        $id = $this->input->post('id_tabungan');
        $kredit = $this->input->post('tariksaldo');

        $this->db->set('saldo', 'saldo - ' . $kredit, FALSE);
        $this->db->where('id_tabungan', $id);
        $this->db->update('tabungan');
    }
}