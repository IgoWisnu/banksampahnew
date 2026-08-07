<?php 
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class Generatepdf extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('m_transaksi');
        $this->load->model('m_payment');
        
        if (!$this->session->userdata('role') == 'admin') {
            redirect('auth');
        }
    }

    public function exceltransaksi()
    {
        $date_from = $this->input->post('date_from') ? $this->input->post('date_from') : ($this->input->get('date_from') ? $this->input->get('date_from') : date('Y-m-01'));
        $date_to   = $this->input->post('date_to') ? $this->input->post('date_to') : ($this->input->get('date_to') ? $this->input->get('date_to') : date('Y-m-d'));
        $tipe      = $this->input->post('tipe') ? $this->input->post('tipe') : ($this->input->get('tipe') ? $this->input->get('tipe') : 'all');
        $status    = $this->input->post('status') ? $this->input->post('status') : ($this->input->get('status') ? $this->input->get('status') : 'all');

        $invoices    = $this->m_payment->getInvoices($tipe, $status, $date_from, $date_to);
        $fin_summary = $this->m_payment->getFinancialSummary($date_from, $date_to);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan Kas & Invoice');

        // ==== KOP HEADER ====
        $sheet->setCellValue('A1', 'LAPORAN ARUS KAS & INVOICE PEMBAYARAN');
        $sheet->mergeCells('A1:J1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16)->getColor()->setRGB('00926E');
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A2', 'Periode: ' . date('d F Y', strtotime($date_from)) . ' s/d ' . date('d F Y', strtotime($date_to)));
        $sheet->mergeCells('A2:J2');
        $sheet->getStyle('A2')->getFont()->setItalic(true)->setSize(11);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // ==== FINANCIAL SUMMARY LEDGER BOX IN EXCEL ====
        $sheet->setCellValue('A4', 'RINGKASAN ARUS KAS & UTANG-PIUTANG REALTIME');
        $sheet->mergeCells('A4:J4');
        $sheet->getStyle('A4')->getFont()->setBold(true)->setSize(11)->getColor()->setRGB('FFFFFF');
        $sheet->getStyle('A4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('00926E');

        $sheet->setCellValue('A5', 'Kas Masuk (Jual Lunas)');
        $sheet->setCellValue('B5', $fin_summary->kas_masuk ?? 0);
        $sheet->setCellValue('D5', 'Kas Keluar (Beli Lunas)');
        $sheet->setCellValue('E5', $fin_summary->kas_keluar ?? 0);
        $sheet->setCellValue('G5', 'Saldo Kas Net');
        $sheet->setCellValue('H5', $fin_summary->saldo_kas ?? 0);

        $sheet->setCellValue('A6', 'Utang Usaha (Beli Pending)');
        $sheet->setCellValue('B6', $fin_summary->utang_beli ?? 0);
        $sheet->setCellValue('D6', 'Piutang Usaha (Jual Pending)');
        $sheet->setCellValue('E6', $fin_summary->piutang_jual ?? 0);
        $sheet->setCellValue('G6', 'Total Fee / Angkut');
        $sheet->setCellValue('H6', $fin_summary->total_biaya_tambahan ?? 0);

        $sheet->getStyle('B5:B6')->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle('E5:E6')->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle('H5:H6')->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle('A5:J6')->getFont()->setBold(true);

        // ==== TABEL HEADER INVOICE ====
        $headers = ['No', 'No Invoice', 'Tipe Transaksi', 'Tanggal & Waktu', 'Pihak Terkait (Nasabah/Buyer)', 'Subtotal (Rp)', 'Fee Angkut (Rp)', 'Grand Total (Rp)', 'Status Bayar', 'Rincian Item Sampah'];
        $colIndex = 'A';
        foreach ($headers as $h) {
            $sheet->setCellValue($colIndex . '8', $h);
            $colIndex++;
        }

        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '198754']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']]]
        ];
        $sheet->getStyle('A8:J8')->applyFromArray($headerStyle);
        $sheet->getRowDimension('8')->setRowHeight(25);

        // ==== DATA INVOICE ====
        $rowNum = 9;
        $no = 1;
        $sum_subtotal = 0;
        $sum_fee = 0;
        $sum_grand = 0;

        if ($invoices && $invoices->num_rows() > 0) {
            foreach ($invoices->result() as $row) {
                $sum_subtotal += $row->total_transaksi;
                $sum_fee += $row->biaya_tambahan;
                $sum_grand += $row->grand_total;

                $pihak = ($row->tipe_transaksi == 'jual') 
                    ? ($row->nama_pihak_luar ? $row->nama_pihak_luar : 'Buyer Eksternal')
                    : ($row->nasabah_username ? $row->nasabah_username : ($row->nama_pihak_luar ? $row->nama_pihak_luar : 'Nasabah'));

                // Fetch details
                $dt_list = $this->m_payment->getInvoiceDetails($row->id_transaksi_sampah);
                $dt_text = [];
                if (!empty($dt_list)) {
                    foreach ($dt_list as $dt) {
                        $dt_text[] = $dt->jenis_sampah . ' (' . $dt->berat_sampah . ' kg)';
                    }
                }
                $rincian_sampah = !empty($dt_text) ? implode(', ', $dt_text) : '-';

                $sheet->setCellValue('A' . $rowNum, $no++);
                $sheet->setCellValue('B' . $rowNum, $row->no_invoice);
                $sheet->setCellValue('C' . $rowNum, strtoupper($row->tipe_transaksi));
                $sheet->setCellValue('D' . $rowNum, date('d/m/Y H:i', strtotime($row->tgl_transaksi)));
                $sheet->setCellValue('E' . $rowNum, $pihak);
                $sheet->setCellValue('F' . $rowNum, $row->total_transaksi);
                $sheet->setCellValue('G' . $rowNum, $row->biaya_tambahan);
                $sheet->setCellValue('H' . $rowNum, $row->grand_total);
                $sheet->setCellValue('I' . $rowNum, strtoupper($row->status_pembayaran));
                $sheet->setCellValue('J' . $rowNum, $rincian_sampah);

                $sheet->getStyle('A' . $rowNum . ':D' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('I' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('F' . $rowNum . ':H' . $rowNum)->getNumberFormat()->setFormatCode('#,##0');
                $sheet->getStyle('A' . $rowNum . ':J' . $rowNum)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setRGB('EEEEEE');

                $rowNum++;
            }
        }

        // ==== TOTAL ROW ====
        $sheet->setCellValue('A' . $rowNum, 'TOTAL KESELURUHAN PERIODE');
        $sheet->mergeCells('A' . $rowNum . ':E' . $rowNum);
        $sheet->setCellValue('F' . $rowNum, $sum_subtotal);
        $sheet->setCellValue('G' . $rowNum, $sum_fee);
        $sheet->setCellValue('H' . $rowNum, $sum_grand);

        $totalStyle = [
            'font' => ['bold' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E8F5E9']],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '00926E']]]
        ];
        $sheet->getStyle('A' . $rowNum . ':J' . $rowNum)->applyFromArray($totalStyle);
        $sheet->getStyle('A' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('F' . $rowNum . ':H' . $rowNum)->getNumberFormat()->setFormatCode('#,##0');

        foreach (range('A', 'J') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = 'Laporan_Kas_dan_Invoice_' . $date_from . '_to_' . $date_to . '.xlsx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function index(){
        $this->load->model('m_dashboard');
            
        $username = $this->session->userdata('username');
        $top['username']       = $username;
        $top['adminCount']     = $this->m_dashboard->getAdminCount();
        $top['nasabahCount']   = $this->m_dashboard->getNasabahCount();
        $top['transaksiCount'] = $this->m_dashboard->getTransaksiCount();
        $top['artikelCount']   = $this->m_dashboard->getArtikelCount();

        $this->load->view('template/header');
        $this->load->view('template/sidebar');
        $this->load->view('template/topbar', $top);
        $this->load->view('banksampah/laporan');
        $this->load->view('template/footer');
    }

    public function pdftransaksi()
    {
        $date_from = $this->input->post('date_from') ? $this->input->post('date_from') : ($this->input->get('date_from') ? $this->input->get('date_from') : date('Y-m-01'));
        $date_to   = $this->input->post('date_to') ? $this->input->post('date_to') : ($this->input->get('date_to') ? $this->input->get('date_to') : date('Y-m-d'));
        $tipe      = $this->input->post('tipe') ? $this->input->post('tipe') : ($this->input->get('tipe') ? $this->input->get('tipe') : 'all');
        $status    = $this->input->post('status') ? $this->input->post('status') : ($this->input->get('status') ? $this->input->get('status') : 'all');

        $data['title']     = "Laporan Arus Kas & Invoice Pembayaran";
        $data['date_from'] = $date_from;
        $data['date_to']   = $date_to;
        $data['tipe']      = $tipe;
        $data['status']    = $status;

        $banjar_id = $this->session->userdata('banjar_id');
        $banjar_name = 'Semua Banjar';
        if ($banjar_id) {
            $this->load->model('M_banjar');
            $banjar = $this->M_banjar->get_by_id($banjar_id);
            if ($banjar) {
                $banjar_name = $banjar->nama;
            }
        }
        $data['banjar_name'] = $banjar_name;

        $invoices = $this->m_payment->getInvoices($tipe, $status, $date_from, $date_to);
        $data['invoices']    = $invoices;
        $data['fin_summary'] = $this->m_payment->getFinancialSummary($date_from, $date_to);

        $details_map = array();
        if ($invoices && $invoices->num_rows() > 0) {
            foreach ($invoices->result() as $inv) {
                $details_map[$inv->id_transaksi_sampah] = $this->m_payment->getInvoiceDetails($inv->id_transaksi_sampah);
            }
        }
        $data['details_map'] = $details_map;

        $html = $this->load->view('v_laporan', $data, true);

        $this->load->library('pdfgenerator');
        $file_pdf = "Laporan_Arus_Kas_Bank_Sampah_" . $date_from . "_to_" . $date_to;
        $paper = 'A4';
        $orientation = "landscape"; 

        $this->pdfgenerator->generate($html, $file_pdf, $paper, $orientation);
    }

    public function pdfstok()
    {
        $this->load->model('m_jenis_sampah');
        $data['title'] = "Laporan Stok Sampah Realtime";
        $data['stok']  = $this->m_jenis_sampah->getLaporanStok();
        $data['log']   = $this->m_jenis_sampah->getStokLogTrail(50);

        $html = $this->load->view('v_laporan_stok', $data, true);

        $this->load->library('pdfgenerator');
        $file_pdf = "Laporan_Stok_Sampah";
        $paper = 'A4';
        $orientation = "landscape"; 

        $this->pdfgenerator->generate($html, $file_pdf, $paper, $orientation);
    }
}