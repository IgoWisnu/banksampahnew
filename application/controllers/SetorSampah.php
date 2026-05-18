<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class SetorSampah extends CI_Controller
{


    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_setor');


        if (!$this->session->userdata('role') == 'admin') {
            redirect('auth');
        }
    }


    public function index()
    {
        $this->load->model('m_dashboard');

        $username = $this->session->userdata('username');
        $top['username'] = $username;
        $data['option'] = $this->m_setor->loadSelect();
        $top['adminCount'] = $this->m_dashboard->getAdminCount();
        $top['nasabahCount'] = $this->m_dashboard->getNasabahCount();
        $top['transaksiCount'] = $this->m_dashboard->getTransaksiCount();
        $top['artikelCount'] = $this->m_dashboard->getArtikelCount();
        $data['margin_value'] = $this->m_setor->getMargin();

        $this->load->view('template/header');
        $this->load->view('template/sidebar');
        $this->load->view('template/topbar', $top);
        $this->load->view('banksampah/setor', $data);
        $this->load->view('template/footer');
    }

    public function setor()
    {
        $input = $this->input->post('cari');
        $output = '';

        if ($input) {
            $data = $this->m_setor->cariUser($input);
            if ($data->num_rows() > 0) {
                $output = '<div class="table-responsive">
                    <table class="table table-bordered table-striped">';
                foreach ($data->result() as $row) {
                    $output .= '
                        <tr class="result-item" data-user-id="' . $row->id_user . '" data-username="' . $row->username . '">
                                <td>' . $row->id_user . '</td>
                                <td>' . $row->username . '</td>
                                <td>' . $row->email . '</td>
                                </tr>
                        ';
                }
                $output .= '</table>
                </div>';
            } else {
                $output .= '<tr>
                            <td colspan="5">No Data Found</td>
                        </tr>';
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

    public function kalkulasi()
    {
        //insert data to table transaksi_sampah
        $id_transaksi = $this->m_setor->insertSampah();

        // insert data to table transaksi_sampahdetail
        $this->m_setor->insertDtSampah($id_transaksi);

        //update total transaksi in transaksi_sampah
        $total = $this->m_setor->updateTotal($id_transaksi);

        //get margin from banjar
        $margin_value = $this->m_setor->getMargin();
        $margin = $total * ($margin_value / 100);
        $totalWithMargin = $total - $margin;

        //insert data to table tabungan_transaksi
        $id_tbUser = $this->m_setor->cariIdTabungan();
        // Here, total is actual harga sampah, margin is margin cut, totalWithMargin is debit_final
        $this->m_setor->insertTabungan($id_transaksi, $id_tbUser, $total, $margin, $totalWithMargin);

        //update data in table saldo
        $this->m_setor->updateDebitSaldo($id_tbUser, $totalWithMargin);

        //set flashdata
        $this->session->set_flashdata('success', 'Setor Sampah Berhasil');
        redirect('dashboard');
    }

    public function hitungHarga()
    {
        $id = $this->input->post('id');
        $berat = $this->input->post('berat');

        $harga = $this->m_setor->cariHarga($id);
        $total = floor($harga * $berat);
        echo $total;
    }
}

/* End of file setorSampah.php */

?>