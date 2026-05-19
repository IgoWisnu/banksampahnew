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
     * Insert header transaksi_sampah.
     */
    public function insertSampah()
    {
        date_default_timezone_set('Asia/Makassar');

        $data = array(
            'id_user_staff' => $this->session->userdata('id'),
            'id_user_nasabah' => $this->input->post('id_user'),
            'total_transaksi' => 0,
            'banjar_id' => $this->session->userdata('banjar_id'),
            'tgl_transaksi' => date('Y-m-d H:i:s') // Menggunakan Y besar dari update-feature-1
        );
        $this->db->insert('transaksi_sampah', $data);
        return $this->db->insert_id();
    }

    /**
     * Insert detail per jenis sampah ke transaksi_sampahdetail.
     * 
     * PERUBAHAN UTAMA:
     * - berat_sisa diisi sama dengan berat_sampah (kantong baru = sisa = berat awal)
     * - harga_saat_setor diisi snapshot harga sekarang (untuk audit)
     */
    public function insertDtSampah($id)
    {
        $list_id_jenis = $this->input->post('id_jenis_sampah');
        $list_berat    = $this->input->post('berat_sampah');
        $list_harga    = $this->input->post('harga_sampah');

        foreach ($list_id_jenis as $key => $value) {
            // Snapshot harga sekarang utk audit (dari update-feature-1)
            $harga_snapshot = $this->cariHarga($value);

            $berat = floatval($list_berat[$key]);
            $total = intval($list_harga[$key]);

            $data = array(
                'id_transaksi_sampah' => $id,
                'id_jenis_sampah'     => $value,
                'berat_sampah'        => $berat,
                'berat_sisa'          => $berat,           // BARU: stok awal = berat setor
                'harga_saat_setor'    => $harga_snapshot,  // BARU: snapshot harga
                'total_harga'         => $total,
            );
            $this->db->insert('transaksi_sampahdetail', $data);
        }
    }

    public function updateTotal($id)
    {
        $this->db->select_sum('total_harga', 'total');
        $this->db->where('id_transaksi_sampah', $id);
        $data = $this->db->get('transaksi_sampahdetail')->row()->total;

        $this->db->set('total_transaksi', $data);
        $this->db->where('id_transaksi_sampah', $id);
        $this->db->update('transaksi_sampah');

        return $data;
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
        $this->db->select('id, jenis_sampah');
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