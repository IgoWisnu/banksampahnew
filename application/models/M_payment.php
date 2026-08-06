<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_payment extends CI_Model {

    public function getInvoices($tipe = 'all', $status = 'all')
    {
        $this->db->select('ts.*, u.username as nasabah_username, s.username as staff_username');
        $this->db->from('transaksi_sampah ts');
        $this->db->join('user u', 'ts.id_user_nasabah = u.id_user', 'left');
        $this->db->join('user s', 'ts.id_user_staff = s.id_user', 'left');
        
        $banjar_id = $this->session->userdata('banjar_id');
        if (!empty($banjar_id)) {
            $this->db->where('ts.banjar_id', $banjar_id);
        }

        if ($tipe != 'all') {
            $this->db->where('ts.tipe_transaksi', $tipe);
        }

        if ($status != 'all') {
            $this->db->where('ts.status_pembayaran', $status);
        }

        $this->db->order_by('ts.id_transaksi_sampah', 'DESC');
        return $this->db->get();
    }

    public function updateStatus($id_transaksi, $status)
    {
        date_default_timezone_set('Asia/Makassar');

        $tgl_pelunasan = ($status == 'Lunas') ? date('Y-m-d H:i:s') : NULL;

        $this->db->set('status_pembayaran', $status);
        $this->db->set('tgl_pelunasan', $tgl_pelunasan);
        $this->db->where('id_transaksi_sampah', $id_transaksi);
        return $this->db->update('transaksi_sampah');
    }

    public function getSummaryStats()
    {
        $banjar_id = $this->session->userdata('banjar_id');

        $this->db->select("
            COUNT(id_transaksi_sampah) as total_invoice,
            COALESCE(SUM(CASE WHEN status_pembayaran = 'Lunas' THEN grand_total ELSE 0 END), 0) as total_lunas,
            COALESCE(SUM(CASE WHEN status_pembayaran = 'Pending' THEN grand_total ELSE 0 END), 0) as total_pending
        ");
        $this->db->from('transaksi_sampah');
        if (!empty($banjar_id)) {
            $this->db->where('banjar_id', $banjar_id);
        }
        return $this->db->get()->row();
    }

    public function getInvoiceHeader($id_transaksi)
    {
        $this->db->select('ts.*, u.username as nasabah_username, u.nama_lengkap as nasabah_nama, u.notelp as nasabah_telp, u.alamat as nasabah_alamat, s.username as staff_username');
        $this->db->from('transaksi_sampah ts');
        $this->db->join('user u', 'ts.id_user_nasabah = u.id_user', 'left');
        $this->db->join('user s', 'ts.id_user_staff = s.id_user', 'left');
        $this->db->where('ts.id_transaksi_sampah', $id_transaksi);
        return $this->db->get()->row();
    }

    public function getInvoiceDetails($id_transaksi)
    {
        $this->db->select('tsd.*, js.jenis_sampah, js.kategori_sampah, js.sub_kategori_sampah');
        $this->db->from('transaksi_sampahdetail tsd');
        $this->db->join('jenis_sampah js', 'tsd.id_jenis_sampah = js.id', 'left');
        $this->db->where('tsd.id_transaksi_sampah', $id_transaksi);
        return $this->db->get()->result();
    }
}
