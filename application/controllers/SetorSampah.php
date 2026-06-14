<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SetorSampah extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_setor');
        $this->load->model('m_dashboard');

        if ($this->session->userdata('role') != 'admin') {
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

        $data['option']        = $this->m_setor->loadSelect();
        $data['margin_value']  = $this->m_setor->getMargin(); // Penting: Menampilkan margin di view

        $this->load->view('template/header');
        $this->load->view('template/sidebar');
        $this->load->view('template/topbar', $top);
        $this->load->view('banksampah/setor', $data);
        $this->load->view('template/footer');
    }

    public function setor()
    {
        $input  = $this->input->post('cari');
        $output = '';

        if ($input) {
            $data = $this->m_setor->cariUser($input);
            if ($data->num_rows() > 0) {
                $output  = '<div class="table-responsive">
                            <table class="table table-bordered table-striped">';
                foreach ($data->result() as $row) {
                    $output .= '<tr class="result-item" 
                                    data-user-id="'.$row->id_user.'" 
                                    data-username="'.$row->username.'">
                                <td>'.$row->id_user.'</td>
                                <td>'.$row->username.'</td>
                                <td>'.$row->email.'</td>
                                </tr>';
                }
                $output .= '</table></div>';
            } else {
                $output = '<tr><td colspan="5">No Data Found</td></tr>';
            }
        }
        echo $output;
    }

    public function insert_data()
    {
        $trSampah = array(
            'id_user_staff' => $this->session->userdata('id'),
            'id_user_nasabah' => $this->input->post('id_user'),
            'tgl_transaksi' => date('y-m-d')
        );
        $id = $this->m_setor->insertData($trSampah);

        $DtSampah = array(
            'id_transaksi_sampah' => $id,
            'id_jenis_sampah' => $this->input->post('id_jenis_sampah'),
            'total_harga' => $this->input->post('total_harga'),
            'berat_sampah' => $this->input->post('berat_sampah')
        );
    }

    /**
     * Proses kalkulasi & insert setor sampah dengan hitungan Margin Banjar.
     */
    public function kalkulasi()
    {
        // 1. Validasi minimal dari update-feature-1
        $id_user = $this->input->post('id_user');
        $list_jenis = $this->input->post('id_jenis_sampah');

        if (empty($id_user) || empty($list_jenis)) {
            $this->session->set_flashdata('failed', 'Data setor tidak lengkap.');
            redirect('dashboard');
            return;
        }

        // 2. Pakai DB transaction dari update-feature-1 agar aman & atomic
        $this->db->trans_start();

        // Header transaksi
        $id_transaksi = $this->m_setor->insertSampah();

        // Detail per jenis sampah
        $this->m_setor->insertDtSampah($id_transaksi);

        // Update total kotor transaksi sampah
        $total = $this->m_setor->updateTotal($id_transaksi);

        // 3. Logika Hitung Margin (Fitur Utama Kamu)
        $margin_value = $this->m_setor->getMargin();
        $margin = round($total * ($margin_value / 100));
        $totalWithMargin = $total - $margin;

        // Cari ID Tabungan
        $id_tabungan = $this->m_setor->cariIdTabungan();
        
        // Simpan ke tabungan_transaksi dengan membawa parameter margin & totalWithMargin
        $this->m_setor->insertTabungan($id_transaksi, $id_tabungan, $total, $margin, $totalWithMargin);

        // Update saldo menggunakan nominal yang sudah dipotong margin
        $this->m_setor->updateDebitSaldo($id_tabungan, $totalWithMargin);

        $this->db->trans_complete();

        // 4. Flashdata & Sync Cache dari update-feature-1
        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('failed', 'Setor sampah gagal diproses.');
        } else {
            // Sinkronisasi cache saldo dinamis nasabah
            $this->m_dashboard->syncSaldoCache($id_user);
            $this->session->set_flashdata('success', 'Setor Sampah Berhasil');
        }

        redirect('setorSampah');
    }

    /**
     * Hitung total harga otomatis (AJAX).
     */
    public function hitungHarga()
    {
        $id    = $this->input->post('id');
        $berat = floatval($this->input->post('berat'));

        $harga = $this->m_setor->cariHarga($id);
        $total = floor($harga * $berat);
        echo $total;
    }
}