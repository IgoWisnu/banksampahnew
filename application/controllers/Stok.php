<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stok extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // Restriksi khusus ADMIN
        if($this->session->userdata('role') != 'admin'){
            redirect('auth');
        }
        $this->load->model('M_stok');
        $this->load->model('m_dashboard'); // Untuk data topbar
    }

    public function index()
    {
        // Siapkan data untuk komponen Topbar (sama seperti halaman lain)
        $username = $this->session->userdata('username');
        $top['username'] = $username;
        $top['adminCount']     = $this->m_dashboard->getAdminCount();
        $top['nasabahCount']   = $this->m_dashboard->getNasabahCount();
        $top['transaksiCount'] = $this->m_dashboard->getTransaksiCount();
        $top['artikelCount']   = $this->m_dashboard->getArtikelCount();

        // Ambil banjar_id dari session admin
        $banjar_id = $this->session->userdata('banjar_id');
        
        $data['stok_realtime'] = $this->M_stok->get_stok_realtime($banjar_id);
        $data['stok_ledger'] = $this->M_stok->get_stok_ledger($banjar_id);

        $this->load->view('template/header');
        $this->load->view('template/sidebar');
        $this->load->view('template/topbar', $top);
        $this->load->view('banksampah/v_stok', $data);
        $this->load->view('template/footer');
    }
}
