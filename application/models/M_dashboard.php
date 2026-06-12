<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class M_dashboard extends CI_Model {

    // ============================================================
    // METHOD YANG SUDAH ADA (tidak diubah)
    // ============================================================

    public function getData(){
        $this->db->where('role', 'user');
        $banjar_id = $this->session->userdata('banjar_id');
        if(!empty($banjar_id)){
            $this->db->where('banjar_id', $banjar_id);
        }
        $data = $this->db->get('user'); 
        return $data;
    }

    public function getUserData($limit, $offset, $keyword){
        if($keyword){
            $this->db->like('username', $keyword);
        }
        $this->db->select('*');
        $this->db->from('user');
        $this->db->where('role', 'user');
        $this->db->where('isVerif', '1');
        
        $banjar_id = $this->session->userdata('banjar_id');
        if(!empty($banjar_id)){
            $this->db->where('banjar_id', $banjar_id);
        }
        
        $this->db->order_by('id_user', 'desc');
        $this->db->limit($limit, $offset);  
        $query = $this->db->get();
        return $query;
    }

    public function getAdminCount(){
        $this->db->where('role', 'admin');
        
        // FILTER BANJAR: Hanya hitung admin yang satu banjar dengan yang login
        $banjar_id = $this->session->userdata('banjar_id');
        if(!empty($banjar_id)){
            $this->db->where('banjar_id', $banjar_id);
        }
        
        $count = $this->db->count_all_results('user');
        return $count;
    }

    public function getNasabahCount(){
        $this->db->where('role', 'user');
        $this->db->where('isVerif', '1');
        
        $banjar_id = $this->session->userdata('banjar_id');
        if(!empty($banjar_id)){
            $this->db->where('banjar_id', $banjar_id);
        }
        
        $query = $this->db->get('user');
        return $query->num_rows();
    }

    public function getBerita($limit, $offset){
        // FILTER BANJAR & UNIVERSAL
        $banjar_id = $this->session->userdata('banjar_id');
        
        if(!empty($banjar_id)){
            // Gunakan group_start() agar kondisi OR dibungkus dalam kurung "( )"
            $this->db->group_start();
            $this->db->where('banjar_id', $banjar_id);
            $this->db->or_where('banjar_id IS NULL', null, false);
            $this->db->group_end();
        }

        $this->db->order_by('id', 'desc');
        $this->db->limit($limit, $offset);
        $data = $this->db->get('artikel'); 
        return $data;
    }

    public function getTransaksi($limit, $offset){
        $this->db->limit($limit, $offset);
        $query = $this->db->get('tabungan_transaksi');
        return $query;
    }

    public function getTransaksiCount(){
        $banjar_id = $this->session->userdata('banjar_id');
        
        // FILTER BANJAR: Langsung tembak ke kolom banjar_id yang ada di tabel tabungan_transaksi
        if(!empty($banjar_id)){
            $this->db->where('banjar_id', $banjar_id);
        }
        
        // Gunakan count_all_results agar kondisi where terbaca
        $count = $this->db->count_all_results('tabungan_transaksi'); 
        return $count;
    }

    public function getArtikelCount(){
        // FILTER BANJAR & UNIVERSAL UNTUK PAGINATION
        $banjar_id = $this->session->userdata('banjar_id');
        
        if(!empty($banjar_id)){
            $this->db->group_start();
            $this->db->where('banjar_id', $banjar_id);
            $this->db->or_where('banjar_id IS NULL', null, false);
            $this->db->group_end();
        }

        // PERBAIKAN: Gunakan count_all_results agar fungsi filter (where) di atas terbaca.
        // Jika pakai count_all(), CodeIgniter akan menghitung semua baris tanpa peduli filter.
        $count = $this->db->count_all_results('artikel'); 
        return $count;
    }

    public function insertBerita($gambarBerita, $banjar_id) {
        $data = array(
            'judul' => $this->input->post('judulBerita'),
            'gambar' => $gambarBerita,
            'deskripsi' => $this->input->post('deskripsiBerita'),
            'banjar_id' => $banjar_id
        );
        $result = $this->db->insert('artikel', $data);
        return $result;
    }

    public function deleteData($id){
        $this->db->where('id', $id);
        $result = $this->db->delete('artikel');
        return $result;
    }

    public function getBeritaById($id) {
        $this->db->where('id', $id);
        $data = $this->db->get('artikel')->row_array();
        return $data;
    }

    public function updateBerita($id, $gambarBerita, $banjar_id) {
        $this->db->where('id', $id);
        $data = array(
            'judul' => $this->input->post('judulBerita'),
            'gambar' => $gambarBerita,
            'deskripsi' => $this->input->post('deskripsiBerita'),
            'banjar_id' => $banjar_id 
        );
        $result = $this->db->update('artikel', $data);
        return $result;
    }

    public function getDataSampah($limit, $offset){
        $this->db->order_by('id', 'desc');
        $this->db->limit($limit, $offset);
        $query = $this->db->get('jenis_sampah');
        return $query;
    }

    public function getSampahCount(){
        $count = $this->db->count_all('jenis_sampah');
        return $count;
    }


    // ============================================================
    // METHOD BARU UNTUK SALDO DINAMIS FIFO
    // ============================================================

    /**
     * Hitung saldo dinamis nasabah berdasarkan berat_sisa di semua kantong
     * dikalikan harga sampah TERKINI (real-time).
     * 
     * @param int $id_user_nasabah ID user nasabah
     * @return float Saldo dinamis dalam rupiah
     */
    public function getSaldoDinamis($id_user_nasabah){
        $this->db->select('saldo_dinamis');
        $this->db->from('v_saldo_dinamis_nasabah');
        $this->db->where('id_user_nasabah', $id_user_nasabah);
        $query = $this->db->get();

        if($query->num_rows() > 0){
            return floatval($query->row()->saldo_dinamis);
        }
        return 0;
    }

    /**
     * Ambil semua kantong setoran nasabah yang masih punya berat_sisa > 0
     * Urut dari kantong tertua (untuk FIFO display) atau dengan info harga sekarang.
     *
     * @param int $id_user_nasabah
     * @return CI_DB_result
     */
    public function getStokKantong($id_user_nasabah){
        $sql = "SELECT 
                    tsd.id_transaksi_sampahDetail,
                    tsd.berat_sampah AS berat_awal,
                    tsd.berat_sisa,
                    tsd.harga_saat_setor,
                    tsd.total_harga AS nilai_saat_setor,
                    ts.tgl_transaksi,
                    ts.id_transaksi_sampah,
                    js.id AS id_jenis_sampah,
                    js.jenis_sampah,
                    js.harga_sampah AS harga_sekarang,
                    (tsd.berat_sisa * js.harga_sampah) AS nilai_sekarang
                FROM transaksi_sampahdetail tsd
                JOIN transaksi_sampah ts 
                    ON ts.id_transaksi_sampah = tsd.id_transaksi_sampah
                JOIN jenis_sampah js 
                    ON js.id = tsd.id_jenis_sampah
                WHERE ts.id_user_nasabah = ?
                    AND tsd.berat_sisa > 0
                ORDER BY ts.tgl_transaksi ASC, tsd.id_transaksi_sampahDetail ASC";

        return $this->db->query($sql, array($id_user_nasabah));
    }

    /**
     * Rekapitulasi stok per jenis sampah (digabung lintas kantong).
     * Berguna untuk tampilan nasabah: "Anda menyimpan: 1,5 kg plastik, 0,6 kg botol"
     *
     * @param int $id_user_nasabah
     * @return CI_DB_result
     */
    public function getStokPerJenis($id_user_nasabah){
        $sql = "SELECT 
                    js.id AS id_jenis_sampah,
                    js.jenis_sampah,
                    js.harga_sampah AS harga_sekarang,
                    SUM(tsd.berat_sisa) AS total_berat_sisa,
                    SUM(tsd.berat_sisa * js.harga_sampah) AS nilai_sekarang
                FROM transaksi_sampahdetail tsd
                JOIN transaksi_sampah ts 
                    ON ts.id_transaksi_sampah = tsd.id_transaksi_sampah
                JOIN jenis_sampah js 
                    ON js.id = tsd.id_jenis_sampah
                WHERE ts.id_user_nasabah = ?
                    AND tsd.berat_sisa > 0
                GROUP BY js.id, js.jenis_sampah, js.harga_sampah
                ORDER BY nilai_sekarang DESC";

        return $this->db->query($sql, array($id_user_nasabah));
    }

    /**
     * Sync ulang kolom tabungan.saldo dengan saldo dinamis sekarang.
     * Dipanggil setelah setor/tarik atau saat admin update harga.
     *
     * @param int $id_user_nasabah
     * @return float Saldo baru
     */
    public function syncSaldoCache($id_user_nasabah){
        $saldo_baru = $this->getSaldoDinamis($id_user_nasabah);

        $this->db->set('saldo', $saldo_baru);
        $this->db->where('id_user_nasabah', $id_user_nasabah);
        $this->db->update('tabungan');

        return $saldo_baru;
    }
}