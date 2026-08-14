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
        $tipe        = $this->input->get('tipe') ? $this->input->get('tipe') : 'all';
        $status      = $this->input->get('status') ? $this->input->get('status') : 'all';
        $tgl_mulai   = $this->input->get('tgl_mulai') ? $this->input->get('tgl_mulai') : null;
        $tgl_selesai = $this->input->get('tgl_selesai') ? $this->input->get('tgl_selesai') : null;

        $username = $this->session->userdata('username');
        $top['username']       = $username;
        $top['adminCount']     = $this->m_dashboard->getAdminCount();
        $top['nasabahCount']   = $this->m_dashboard->getNasabahCount();
        $top['transaksiCount'] = $this->m_dashboard->getTransaksiCount();
        $top['artikelCount']   = $this->m_dashboard->getArtikelCount();

        $data['invoices']    = $this->m_payment->getInvoices($tipe, $status, $tgl_mulai, $tgl_selesai);
        $data['summary']     = $this->m_payment->getSummaryStats($tipe, $status, $tgl_mulai, $tgl_selesai);
        $data['fin_summary'] = $this->m_payment->getFinancialSummary($tgl_mulai, $tgl_selesai);
        $data['tipe']        = $tipe;
        $data['status']      = $status;
        $data['tgl_mulai']   = $tgl_mulai;
        $data['tgl_selesai'] = $tgl_selesai;

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

    public function pdf_invoice($id)
    {
        $header  = $this->m_payment->getInvoiceHeader($id);
        $details = $this->m_payment->getInvoiceDetails($id);

        if (!$header) {
            show_404();
            return;
        }

        $data['header']  = $header;
        $data['details'] = $details;
        $data['title']   = "Invoice_" . ($header->no_invoice ? $header->no_invoice : $header->id_transaksi_sampah);

        $html = $this->load->view('banksampah/invoice_pdf', $data, true);

        $this->load->library('pdfgenerator');
        $file_pdf = "Invoice_" . ($header->no_invoice ? $header->no_invoice : $header->id_transaksi_sampah);
        
        // Dynamic Paper Size Calculation for Thermal Receipt Style
        $jumlah_item = count($details);
        $base_height = 290; // Header, info, divider, summary, footer approx height in pts
        $item_height = 30;  // Height per item row
        $biaya_height = (!empty($header->biaya_tambahan) && $header->biaya_tambahan > 0) ? 20 : 0;
        
        $total_height = $base_height + ($jumlah_item * $item_height) + $biaya_height;
        $paper_width = 300; // receipt width
        
        $paper = array(0, 0, $paper_width, $total_height);
        $orientation = "portrait";

        $this->pdfgenerator->generate($html, $file_pdf, $paper, $orientation);
    }
}
