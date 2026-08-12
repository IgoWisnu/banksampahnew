<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class JualSampah extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_jual');
        $this->load->model('m_dashboard');

        if ($this->session->userdata('role') != 'admin' && $this->session->userdata('role') != 'superadmin') {
            redirect('auth');
        }
    }

    public function index()
    {
        $username = $this->session->userdata('username');
        $top['username']       = $username;
        $top['adminCount']     = $this->m_dashboard->getAdminCount();
        $top['nasabahCount']   = $this->m_dashboard->getNasabahCount();
        $top['transaksiCount'] = $this->m_dashboard->getTransaksiCount();
        $top['artikelCount']   = $this->m_dashboard->getArtikelCount();

        $data['option']        = $this->m_jual->loadJenisSampahWithStok();
        
        // Load Nasabah list for buyer selection
        $banjar_id = $this->session->userdata('banjar_id');
        if (!empty($banjar_id)) {
            $this->db->where('banjar_id', $banjar_id);
        }
        $this->db->where('role', 'user');
        $this->db->where('isVerif', '1');
        $data['nasabah_list']  = $this->db->get('user')->result_array();

        $this->load->view('template/header');
        $this->load->view('template/sidebar');
        $this->load->view('template/topbar', $top);
        $this->load->view('banksampah/v_jual', $data);
        $this->load->view('template/footer');
    }

    public function prosesJual()
    {
        $nama_buyer       = $this->input->post('nama_buyer');
        $status_pembayaran = $this->input->post('status_pembayaran');
        $biaya_tambahan   = intval($this->input->post('biaya_tambahan'));
        $keterangan_biaya = $this->input->post('keterangan_biaya');

        $list_id_jenis     = $this->input->post('id_jenis_sampah');
        $list_berat        = $this->input->post('berat_sampah');
        $list_harga_manual = $this->input->post('harga_manual');

        if (empty($nama_buyer)) {
            $this->session->set_flashdata('failed', 'Gagal: Nama Pembeli / Buyer wajib diisi.');
            redirect('jualSampah');
            return;
        }

        if (empty($list_id_jenis) || !is_array($list_id_jenis) || count($list_id_jenis) == 0) {
            $this->session->set_flashdata('failed', 'Gagal: Daftar item sampah yang dijual tidak boleh kosong.');
            redirect('jualSampah');
            return;
        }

        $has_valid_item = false;
        foreach ($list_id_jenis as $idx => $id_j) {
            $b = isset($list_berat[$idx]) ? floatval($list_berat[$idx]) : 0;
            if (!empty($id_j) && $b > 0) {
                $has_valid_item = true;
            }
        }

        if (!$has_valid_item) {
            $this->session->set_flashdata('failed', 'Gagal: Mohon pilih jenis sampah dan masukkan berat (Kg) penjualan lebih dari 0.');
            redirect('jualSampah');
            return;
        }

        // 1. Validasi Batas Stok (Mencegah Stok Minus)
        foreach ($list_id_jenis as $key => $id_jenis) {
            $berat_jual = floatval($list_berat[$key]);
            $stok_ada   = $this->m_jual->getStokTersisa($id_jenis);

            if ($berat_jual > $stok_ada) {
                $this->session->set_flashdata('failed', 'Stok sampah tidak mencukupi untuk item ke-' . ($key + 1) . '. Stok tersedia: ' . $stok_ada . ' kg');
                redirect('jualSampah');
                return;
            }
        }

        // 2. Jalankan DB Transaction
        $this->db->trans_start();

        $id_transaksi = $this->m_jual->insertJualSampah($nama_buyer, $biaya_tambahan, $keterangan_biaya, $status_pembayaran);
        $subtotal     = $this->m_jual->insertDtJualSampah($id_transaksi, $list_id_jenis, $list_berat, $list_harga_manual);
        $this->m_jual->updateHeaderTotals($id_transaksi, $subtotal);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('failed', 'Penjualan sampah gagal diproses.');
        } else {
            $this->session->set_flashdata('success', 'Transaksi Penjualan Sampah Berhasil Diproses!');
        }

        redirect('jualSampah');
    }
}
