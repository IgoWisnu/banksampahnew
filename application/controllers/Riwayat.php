<?php
    
defined('BASEPATH') OR exit('No direct script access allowed');

class Riwayat extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_riwayat');
        $this->load->model('m_dashboard');

        if($this->session->userdata('role') == ''){
            redirect('auth');
        } elseif($this->session->userdata('role') == 'guest'){
            redirect('auth/regisGuest');
        }
    }
    
    /**
     * Halaman riwayat transaksi nasabah.
     * Kirim juga saldo dinamis & stok kantong ke view.
     */
    public function index()
    {
        $id = $this->session->userdata('id');

        $data['riwayat']       = $this->m_riwayat->loadRiwayat($id);
        $data['saldo_dinamis'] = $this->m_dashboard->getSaldoDinamis($id);
        $data['stok_jenis']    = $this->m_dashboard->getStokPerJenis($id);

        $this->load->view('banksampah/riwayat', $data);
    }

    /**
     * Halaman invoice / detail transaksi.
     * Untuk setor: tampilkan jenis sampah yang disetor + harga saat setor.
     * Untuk tarik: tampilkan kantong mana saja yang digerus + harga saat tarik (FIFO trail).
     */
    public function invoice(){
        $id_transaksi = $this->input->get('id', TRUE);  // TRUE = XSS filter

        if(empty($id_transaksi) || !is_numeric($id_transaksi)){
            show_error('ID transaksi tidak valid', 400);
            return;
        }

        $data['detail']         = $this->m_riwayat->getDetail($id_transaksi);
        $data['sampah']         = $this->m_riwayat->getDetailSampah($id_transaksi);
        $data['penarikan_fifo'] = $this->m_riwayat->getDetailPenarikan($id_transaksi);

        $this->load->view('banksampah/invoice', $data);
    }

    public function tesLoad(){
        $this->load->view('banksampah/riwayat');
    }

    public function tesLoadDetail(){
        $this->load->view('banksampah/invoice');
    }
}