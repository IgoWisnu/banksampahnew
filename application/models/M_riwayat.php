<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class M_riwayat extends CI_Model {

    /**
     * Load riwayat transaksi (setor + tarik) untuk nasabah.
     * Pakai query builder — anti SQL injection.
     */
    public function loadRiwayat($id_user_nasabah){
        $this->db->select('tt.*, t.id_user_nasabah');
        $this->db->from('tabungan_transaksi tt');
        $this->db->join('tabungan t', 'tt.id_tabungan = t.id_tabungan');
        $this->db->where('t.id_user_nasabah', $id_user_nasabah);
        $this->db->order_by('tt.id_tabungan_transaksi', 'DESC');
        return $this->db->get();
    }

    /**
     * Detail header transaksi untuk halaman invoice.
     */
    public function getDetail($id_tabungan_transaksi){
        $this->db->select('tt.*, t.id_user_nasabah, u.username, u.id_user, u.email');
        $this->db->from('tabungan_transaksi tt');
        $this->db->join('tabungan t', 'tt.id_tabungan = t.id_tabungan');
        $this->db->join('user u', 't.id_user_nasabah = u.id_user');
        $this->db->where('tt.id_tabungan_transaksi', $id_tabungan_transaksi);
        $this->db->limit(1);
        return $this->db->get();
    }

    /**
     * Detail sampah untuk invoice SETOR.
     * Tampilkan jenis_sampah, berat, dan harga SAAT SETOR (snapshot, bukan harga sekarang).
     */
    public function getDetailSampah($id_tabungan_transaksi){
        $this->db->select('tsd.*, js.jenis_sampah, ts.tgl_transaksi');
        $this->db->from('tabungan_transaksi tt');
        $this->db->join('transaksi_sampah ts', 'tt.id_transaksi_sampah = ts.id_transaksi_sampah');
        $this->db->join('transaksi_sampahdetail tsd', 'ts.id_transaksi_sampah = tsd.id_transaksi_sampah');
        $this->db->join('jenis_sampah js', 'tsd.id_jenis_sampah = js.id');
        $this->db->where('tt.id_tabungan_transaksi', $id_tabungan_transaksi);
        return $this->db->get();
    }

    /**
     * Detail TARIK pakai FIFO: tampilkan kantong mana saja yg digerus.
     * Dipanggil di invoice kalau transaksi adalah tarik.
     */
    public function getDetailPenarikan($id_tabungan_transaksi){
        $sql = "SELECT 
                    pd.berat_terambil,
                    pd.harga_saat_tarik,
                    pd.nilai_terambil,
                    pd.tgl_dibuat,
                    js.jenis_sampah,
                    ts.tgl_transaksi AS tgl_setor_asal
                FROM penarikan_detail pd
                JOIN transaksi_sampahdetail tsd 
                    ON pd.id_transaksi_sampahdetail = tsd.id_transaksi_sampahDetail
                JOIN transaksi_sampah ts 
                    ON tsd.id_transaksi_sampah = ts.id_transaksi_sampah
                JOIN jenis_sampah js 
                    ON tsd.id_jenis_sampah = js.id
                WHERE pd.id_tabungan_transaksi = ?
                ORDER BY ts.tgl_transaksi ASC";

        return $this->db->query($sql, array($id_tabungan_transaksi));
    }
}