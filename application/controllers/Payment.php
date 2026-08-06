<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_payment');
        $this->load->model('m_dashboard');

        if ($this->session->userdata('role') != 'admin' && $this->session->userdata('role') != 'superadmin') {
            redirect('auth');
        }
    }

    public function index()
    {
        $tipe   = $this->input->get('tipe') ? $this->input->get('tipe') : 'all';
        $status = $this->input->get('status') ? $this->input->get('status') : 'all';

        $username = $this->session->userdata('username');
        $top['username']       = $username;
        $top['adminCount']     = $this->m_dashboard->getAdminCount();
        $top['nasabahCount']   = $this->m_dashboard->getNasabahCount();
        $top['transaksiCount'] = $this->m_dashboard->getTransaksiCount();
        $top['artikelCount']   = $this->m_dashboard->getArtikelCount();

        $data['invoices'] = $this->m_payment->getInvoices($tipe, $status);
        $data['summary']  = $this->m_payment->getSummaryStats();
        $data['tipe']     = $tipe;
        $data['status']   = $status;

        $this->load->view('template/header');
        $this->load->view('template/sidebar');
        $this->load->view('template/topbar', $top);
        $this->load->view('banksampah/v_payment', $data);
        $this->load->view('template/footer');
    }

    public function update_status()
    {
        $id_transaksi = $this->input->post('id_transaksi');
        $status       = $this->input->post('status_pembayaran');

        if (!empty($id_transaksi) && in_array($status, ['Lunas', 'Pending'])) {
            $this->m_payment->updateStatus($id_transaksi, $status);
            $this->session->set_flashdata('success', 'Status Pembayaran Invoice Berhasil Diperbarui!');
        } else {
            $this->session->set_flashdata('failed', 'Gagal mengupdate status pembayaran.');
        }

        redirect('payment');
    }

    public function detail($id)
    {
        $header  = $this->m_payment->getInvoiceHeader($id);
        $details = $this->m_payment->getInvoiceDetails($id);

        if (!$header) {
            show_404();
            return;
        }

        $data['header']  = $header;
        $data['details'] = $details;

        $this->load->view('banksampah/invoice', $data);
    }
}
