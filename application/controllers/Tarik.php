<?php
    
defined('BASEPATH') OR exit('No direct script access allowed');

class Tarik extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_tarik');
        $this->load->model('m_dashboard');

        if($this->session->userdata('role') != 'admin'){
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

        $this->load->view('template/header');
        $this->load->view('template/sidebar');
        $this->load->view('template/topbar', $top);
        $this->load->view('banksampah/tarik');
        $this->load->view('template/footer');
    }

    /**
     * AJAX: cek apakah nominal tarik <= saldo dinamis.
     */
    public function cekSaldo(){
        $input = floatval($this->input->post('cari'));
        $saldo = floatval($this->input->post('saldo'));

        if($input <= 0){
            $output = '<p class="text-warning">Masukkan nominal yang valid</p>';
        } else if($input <= $saldo){
            $output = '<p class="text-success">Saldo mencukupi untuk ditarik</p>';
        } else {
            $output = '<p class="text-danger">Saldo tidak mencukupi</p>';
        }
        echo $output;
    }

    /**
     * Proses tarik tabungan dengan logika FIFO.
     * Memanggil M_tarik::prosesTarikFifo() yang sudah handle semuanya
     * (validasi, transaction, gerus kantong, catat penarikan_detail, update cache).
     */
    public function tarikTabungan(){
        $result = $this->m_tarik->prosesTarikFifo();

        if($result['success']){
            // Sync ulang cache saldo (jaga2 supaya pasti match dengan saldo dinamis)
            // Ambil id_user_nasabah dari id_tabungan
            $id_tabungan = $this->input->post('id_tabungan');
            $this->db->select('id_user_nasabah');
            $this->db->where('id_tabungan', $id_tabungan);
            $row = $this->db->get('tabungan')->row();
            if($row){
                $this->m_dashboard->syncSaldoCache($row->id_user_nasabah);
            }

            $this->session->set_flashdata('success', $result['message']);
        } else {
            $this->session->set_flashdata('failed', $result['message']);
        }

        redirect('tarik');
    }

    /**
     * AJAX: cari user berdasarkan keyword, return saldo dinamis (bukan cache).
     */
    public function cariUser(){
        $input  = $this->input->post('cari');
        $output = '';

        if($input) {
            $data = $this->m_tarik->cariUserSaldo($input);
            if($data->num_rows() > 0){
                $output  = '<div class="table-responsive">
                            <table class="table table-bordered table-striped">';
                foreach($data->result() as $row){
                    $output .= '<tr class="result-item" 
                                    data-tabungan-id="'.$row->id_tabungan.'" 
                                    data-username="'.$row->username.'" 
                                    data-saldo="'.round($row->saldo).'"> 
                                <td>'.$row->id_tabungan.'</td>
                                <td>'.$row->username.'</td>
                                <td>Rp '.number_format($row->saldo, 0, ',', '.').'</td>
                                </tr>';
                }
                $output .= '</table></div>';
            } else {
                $output = '<tr><td colspan="5">No Data Found</td></tr>';
            }
        }
        echo $output;
    }
}