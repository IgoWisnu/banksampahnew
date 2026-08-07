<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_setor extends CI_Model {

    public function cariUser($query)
    {
        $this->db->select("*");
        $this->db->from("user");

        // Pastikan filter banjar_id selalu mengikat (Fitur Lokal)
        $this->db->where('banjar_id', $this->session->userdata('banjar_id'));

        if ($query != '') {
            // Mulai tanda kurung: AND (...) agar OR tidak membocorkan data banjar lain
            $this->db->group_start();
            $this->db->like('id_user', $query);
            $this->db->or_like('username', $query);
            $this->db->or_like('email', $query);
            $this->db->group_end();
        }
        $this->db->order_by('username', 'DESC');
        return $this->db->get();
    }

    /**
     * Ambil harga sampah TERKINI dari jenis_sampah.
     * Dipakai 2x: (1) saat ajax hitungHarga, (2) saat snapshot ke harga_saat_setor.
     */
    public function cariHarga($id)
    {
        $this->db->select('harga_sampah');
        $this->db->where('id', $id);
        $harga = $this->db->get('jenis_sampah');
        if ($harga->num_rows() > 0) {
            return $harga->row()->harga_sampah;
        }
        return 1;
    }

    /**
     * Insert header transaksi_sampah (Beli Sampah).
     */
    public function insertSampah()
    {
        date_default_timezone_set('Asia/Makassar');

        $status_pembayaran = $this->input->post('status_pembayaran') ? $this->input->post('status_pembayaran') : 'Lunas';
        $tgl_pelunasan = ($status_pembayaran == 'Lunas') ? date('Y-m-d H:i:s') : NULL;
        $no_invoice = 'INV-BELI-' . date('YmdHis') . '-' . rand(100, 999);

        $data = array(
            'no_invoice'        => $no_invoice,
            'tipe_transaksi'    => 'beli',
            'id_user_staff'     => $this->session->userdata('id'),
            'id_user_nasabah'   => $this->input->post('id_user'),
            'total_transaksi'   => 0,
            'status_pembayaran' => $status_pembayaran,
            'biaya_tambahan'    => 0,
            'keterangan_biaya'  => NULL,
            'grand_total'       => 0,
            'banjar_id'         => $this->session->userdata('banjar_id'),
            'tgl_transaksi'     => date('Y-m-d H:i:s'),
            'tgl_pelunasan'     => $tgl_pelunasan
        );
        $this->db->insert('transaksi_sampah', $data);
        return $this->db->insert_id();
    }

    /**
     * Insert detail per jenis sampah ke transaksi_sampahdetail.
     * 
     * PERUBAHAN UTAMA:
     * - harga_satuan diisi snapshot harga fix per unit saat invoice dibuat
     * - stok_tersisa di jenis_sampah otomatis bertambah
     * - pergerakan stok dicatat di stok_log
     */
    public function insertDtSampah($id)
    {
        $list_id_jenis = $this->input->post('id_jenis_sampah');
        $list_berat    = $this->input->post('berat_sampah');
        $list_harga    = $this->input->post('harga_sampah');

        foreach ($list_id_jenis as $key => $value) {
            // Snapshot harga sekarang utk audit & harga fix invoice
            $harga_snapshot = $this->cariHarga($value);

            $berat = floatval($list_berat[$key]);
            $total = intval($list_harga[$key]);

            $data = array(
                'id_transaksi_sampah' => $id,
                'id_jenis_sampah'     => $value,
                'harga_satuan'        => $harga_snapshot,  // BARU: Snapshot harga unit per kg (harga fix)
                'berat_sampah'        => $berat,
                'berat_sisa'          => $berat,           // stok awal = berat setor
                'harga_saat_setor'    => $harga_snapshot,  // snapshot harga audit
                'total_harga'         => $total,
            );
            $this->db->insert('transaksi_sampahdetail', $data);

            // SISTEM STOKING: Tambah stok_tersisa di jenis_sampah
            $this->db->set('stok_tersisa', 'stok_tersisa + ' . $berat, FALSE);
            $this->db->where('id', $value);
            $this->db->update('jenis_sampah');

            // Ambil stok_tersisa setelah update untuk logged balance
            $stok_sekarang = $this->getStokTersisa($value);
            $stok_sebelum = $stok_sekarang - $berat;

            // Catat audit trail di stok_log
            $log_data = array(
                'banjar_id'       => $this->session->userdata('banjar_id'),
                'id_jenis_sampah' => $value,
                'tipe_pergerakan' => 'masuk',
                'jumlah'          => $berat,
                'stok_sebelum'    => $stok_sebelum,
                'stok_sesudah'    => $stok_sekarang,
                'ref_invoice_id'  => $id,
                'keterangan'      => 'Pembelian Sampah (Beli)'
            );
            $this->db->insert('stok_log', $log_data);
        }
    }

    public function getStokTersisa($id)
    {
        $this->db->select('stok_tersisa');
        $this->db->where('id', $id);
        $res = $this->db->get('jenis_sampah');
        if ($res->num_rows() > 0) {
            return floatval($res->row()->stok_tersisa);
        }
        return 0;
    }

    public function updateTotal($id)
    {
        $this->db->select_sum('total_harga', 'total');
        $this->db->where('id_transaksi_sampah', $id);
        $data = $this->db->get('transaksi_sampahdetail')->row()->total;

        $total = $data ? intval($data) : 0;

        $this->db->set('total_transaksi', $total);
        $this->db->set('grand_total', $total);
        $this->db->where('id_transaksi_sampah', $id);
        $this->db->update('transaksi_sampah');

        return $total;
    }

    public function cariIdTabungan()
    {
        $id = $this->input->post('id_user');
        $query = $this->db->select('id_tabungan')
                    ->from('tabungan')
                    ->where('id_user_nasabah', $id)
                    ->get();

        if ($query->num_rows() > 0) {
            return $query->row()->id_tabungan;
        }
        return null;
    }

    /**
     * Catat transaksi setor di tabungan_transaksi (debit = uang masuk).
     */
    public function insertTabungan($id, $id_tabungan, $total, $margin, $debit_final)
    {
        date_default_timezone_set('Asia/Makassar');

        $data = array(
            'id_transaksi_sampah' => $id,
            'id_tabungan'         => $id_tabungan,
            'id_user_staff'       => $this->session->userdata('id'),
            'kredit'              => 0,
            'debit'               => $total,
            'margin'              => $margin,          // Dari Lokal (Master)
            'debit_final'         => $debit_final,     // Dari Lokal (Master)
            'banjar_id'           => $this->session->userdata('banjar_id'), // Dari Lokal
            'tgl_tabungan_transaksi' => date('Y-m-d H:i:s')
        );
        $this->db->insert('tabungan_transaksi', $data);
    }

    /**
     * Update kolom saldo (cache). 
     */
    public function updateDebitSaldo($id_tabungan, $total)
    {
        // Memastikan $total jadi int agar query aman (dari update-feature-1)
        $this->db->set('saldo', 'saldo + ' . intval($total), FALSE);
        $this->db->where('id_tabungan', $id_tabungan);
        $this->db->update('tabungan');
    }

    public function loadSelect()
    {
        $this->db->select('id, jenis_sampah, harga_sampah, stok_tersisa, sub_kategori_sampah');
        $banjar_id = $this->session->userdata('banjar_id');
        $role = $this->session->userdata('role');

        if (!empty($banjar_id) && $role != 'superadmin') {
            $this->db->group_start();
            $this->db->where('banjar_id', $banjar_id);
            $this->db->or_where('banjar_id', NULL);
            $this->db->group_end();
        }
        $this->db->order_by('jenis_sampah', 'ASC');
        $query = $this->db->get('jenis_sampah');
        return $query;
    }

    public function getMargin()
    {
        $this->db->select('margin_value');
        $this->db->where('id', $this->session->userdata('banjar_id'));
        $query = $this->db->get('banjar');
        return $query->row()->margin_value;
    }
}