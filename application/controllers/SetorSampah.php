<?php
    
defined('BASEPATH') OR exit('No direct script access allowed');

class SetorSampah extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_setor');
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

        $data['option'] = $this->m_setor->loadSelect();

        $this->load->view('template/header');
        $this->load->view('template/sidebar');
        $this->load->view('template/topbar', $top);
        $this->load->view('banksampah/setor', $data);
        $this->load->view('template/footer');
    }

    public function setor(){
        $input  = $this->input->post('cari');
        $output = '';

        if($input) {
            $data = $this->m_setor->cariUser($input);
            if($data->num_rows() > 0){
                $output  = '<div class="table-responsive">
                            <table class="table table-bordered table-striped">';
                foreach($data->result() as $row){
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

    /**
     * Proses kalkulasi & insert setor sampah.
     * 
     * Flow:
     * 1. Insert header transaksi_sampah
     * 2. Insert detail per jenis (sekarang juga isi berat_sisa & harga_saat_setor)
     * 3. Update total transaksi
     * 4. Insert tabungan_transaksi (debit)
     * 5. Update saldo cache di tabel tabungan
     * 6. Sync ulang saldo cache (jaga2 kalau ada drift dari perubahan harga)
     */
    public function kalkulasi(){
        // Validasi minimal: harus ada user dan minimal 1 jenis sampah
        $id_user = $this->input->post('id_user');
        $list_jenis = $this->input->post('id_jenis_sampah');

        if(empty($id_user) || empty($list_jenis)){
            $this->session->set_flashdata('failed', 'Data setor tidak lengkap.');
            redirect('dashboard');
            return;
        }

        // Pakai DB transaction supaya atomic
        $this->db->trans_start();

        // 1. Insert header transaksi_sampah
        $id_transaksi = $this->m_setor->insertSampah();

        // 2. Insert detail
        $this->m_setor->insertDtSampah($id_transaksi);

        // 3. Update total
        $total = $this->m_setor->updateTotal($id_transaksi);

        // 4. Insert tabungan_transaksi
        $id_tabungan = $this->m_setor->cariIdTabungan();
        $this->m_setor->insertTabungan($id_transaksi, $id_tabungan, $total);

        // 5. Update saldo cache (legacy + dipakai sebagian view)
        $this->m_setor->updateDebitSaldo($id_tabungan, $total);

        $this->db->trans_complete();

        if($this->db->trans_status() === FALSE){
            $this->session->set_flashdata('failed', 'Setor sampah gagal diproses.');
        } else {
            // 6. Sync cache supaya kolom saldo = saldo dinamis sebenarnya
            // (Penting karena harga mungkin sudah berubah sejak setoran lain)
            $this->m_dashboard->syncSaldoCache($id_user);

            $this->session->set_flashdata('success', 'Setor Sampah Berhasil');
        }

        redirect('dashboard');
    }

    /**
     * Hitung total harga otomatis (dipanggil via AJAX saat user input berat).
     */
    public function hitungHarga(){
        $id    = $this->input->post('id');
        $berat = floatval($this->input->post('berat'));

        $harga = $this->m_setor->cariHarga($id);
        $total = floor($harga * $berat);
        echo $total;
    }
}