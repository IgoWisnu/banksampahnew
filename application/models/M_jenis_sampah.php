<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class M_jenis_sampah extends CI_Model {

    public function loadData(){
        $banjar_id = $this->session->userdata('banjar_id');
        $role = $this->session->userdata('role');

        if (!empty($banjar_id) && $role != 'superadmin') {
            $this->db->group_start();
            $this->db->where('banjar_id', $banjar_id);
            $this->db->or_where('banjar_id', NULL);
            $this->db->group_end();
        }
        return $this->db->get('jenis_sampah');
    }

    public function get_count() {
        $banjar_id = $this->session->userdata('banjar_id');
        $role = $this->session->userdata('role');

        if (!empty($banjar_id) && $role != 'superadmin') {
            $this->db->group_start();
            $this->db->where('banjar_id', $banjar_id);
            $this->db->or_where('banjar_id', NULL);
            $this->db->group_end();
        }
        return $this->db->count_all_results('jenis_sampah');
    }

    public function get_paginated($limit, $start) {
        $banjar_id = $this->session->userdata('banjar_id');
        $role = $this->session->userdata('role');

        if (!empty($banjar_id) && $role != 'superadmin') {
            $this->db->group_start();
            $this->db->where('banjar_id', $banjar_id);
            $this->db->or_where('banjar_id', NULL);
            $this->db->group_end();
        }
        $this->db->limit($limit, $start);
        return $this->db->get('jenis_sampah');
    }

    /**
     * Ambil 1 baris jenis_sampah berdasarkan id (untuk form edit).
     */
    public function getById($id){
        $this->db->where('id', $id);
        return $this->db->get('jenis_sampah')->row_array();
    }

    /**
     * Insert jenis sampah baru.
     */
    public function insertJenis($data){
        return $this->db->insert('jenis_sampah', $data);
    }

    /**
     * Update jenis sampah, dengan auto-log perubahan harga.
     * 
     * Kalau harga_sampah berubah, otomatis insert ke harga_sampah_history
     * untuk audit trail dan transparansi ke nasabah.
     *
     * @param int   $id          ID jenis sampah
     * @param array $data        Data baru (jenis_sampah, kategori, sub_kategori, harga_sampah)
     * @param string $keterangan Alasan perubahan (opsional)
     * @return bool
     */
    public function updateJenis($id, $data, $keterangan = null){
        // Ambil data lama untuk dibandingkan
        $lama = $this->getById($id);
        if(!$lama) return false;

        $this->db->trans_start();

        // Update jenis_sampah
        $this->db->where('id', $id);
        $this->db->update('jenis_sampah', $data);

        // Kalau harga_sampah berubah → log ke history
        if(isset($data['harga_sampah']) && intval($data['harga_sampah']) != intval($lama['harga_sampah'])){
            $this->db->insert('harga_sampah_history', array(
                'id_jenis_sampah' => $id,
                'harga_lama'      => intval($lama['harga_sampah']),
                'harga_baru'      => intval($data['harga_sampah']),
                'id_user_admin'   => $this->session->userdata('id'),
                'keterangan'      => $keterangan,
                'tgl_perubahan'   => date('Y-m-d H:i:s'),
            ));
        }

        $this->db->trans_complete();
        return $this->db->trans_status() !== FALSE;
    }

    /**
     * Hapus jenis sampah. 
     * CATATAN: Jangan delete kalau jenis_sampah ini sudah pernah dipakai
     * di transaksi_sampahdetail (foreign key constraint).
     */
    public function deleteJenis($id){
        $this->db->where('id', $id);
        return $this->db->delete('jenis_sampah');
    }

    /**
     * Riwayat perubahan harga untuk 1 jenis sampah (untuk transparansi).
     */
    public function getHargaHistory($id_jenis_sampah, $limit = 20){
        $this->db->select('hsh.*, u.username AS admin_username');
        $this->db->from('harga_sampah_history hsh');
        $this->db->join('user u', 'hsh.id_user_admin = u.id_user', 'left');
        $this->db->where('hsh.id_jenis_sampah', $id_jenis_sampah);
        $this->db->order_by('hsh.tgl_perubahan', 'DESC');
        $this->db->limit($limit);
        return $this->db->get();
    }

    /**
     * Riwayat perubahan harga semua jenis sampah (untuk halaman audit).
     */
    public function getAllHargaHistory($limit = 50){
        $this->db->select('hsh.*, js.jenis_sampah, u.username AS admin_username');
        $this->db->from('harga_sampah_history hsh');
        $this->db->join('jenis_sampah js', 'hsh.id_jenis_sampah = js.id');
        $this->db->join('user u', 'hsh.id_user_admin = u.id_user', 'left');
        $this->db->order_by('hsh.tgl_perubahan', 'DESC');
        $this->db->limit($limit);
        return $this->db->get();
    }

    /**
     * Laporan Akumulasi Stok Sampah (Masuk, Keluar, dan Sisa Realtime).
     */
    public function getLaporanStok() {
        $banjar_id = $this->session->userdata('banjar_id');
        $role = $this->session->userdata('role');

        $this->db->select("
            js.*,
            COALESCE(SUM(CASE WHEN sl.tipe_pergerakan = 'masuk' THEN sl.jumlah ELSE 0 END), 0) as total_masuk,
            COALESCE(SUM(CASE WHEN sl.tipe_pergerakan = 'keluar' THEN sl.jumlah ELSE 0 END), 0) as total_keluar
        ");
        $this->db->from('jenis_sampah js');
        $this->db->join('stok_log sl', 'js.id = sl.id_jenis_sampah', 'left');

        if (!empty($banjar_id) && $role != 'superadmin') {
            $this->db->group_start();
            $this->db->where('js.banjar_id', $banjar_id);
            $this->db->or_where('js.banjar_id', NULL);
            $this->db->group_end();
        }

        $this->db->group_by('js.id');
        $this->db->order_by('js.kategori_sampah', 'ASC');
        $this->db->order_by('js.jenis_sampah', 'ASC');
        return $this->db->get();
    }

    /**
     * Audit Trail Pergerakan Stok Sampah (Stok Log).
     */
    public function getStokLogTrail($limit = 100) {
        $banjar_id = $this->session->userdata('banjar_id');
        $role = $this->session->userdata('role');

        $this->db->select('sl.*, js.jenis_sampah, js.kategori_sampah, ts.no_invoice');
        $this->db->from('stok_log sl');
        $this->db->join('jenis_sampah js', 'sl.id_jenis_sampah = js.id');
        $this->db->join('transaksi_sampah ts', 'sl.ref_invoice_id = ts.id_transaksi_sampah', 'left');

        if (!empty($banjar_id) && $role != 'superadmin') {
            $this->db->group_start();
            $this->db->where('sl.banjar_id', $banjar_id);
            $this->db->or_where('sl.banjar_id', NULL);
            $this->db->group_end();
        }

        $this->db->order_by('sl.id_stok_log', 'DESC');
        $this->db->limit($limit);
        return $this->db->get();
    }
}