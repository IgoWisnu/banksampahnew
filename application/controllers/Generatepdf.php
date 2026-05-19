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
        
        if(!$this->session->userdata('role') == 'admin'){
            redirect('auth');
        }
    }

    public function exceltransaksi()
    {
        $date_from = $this->input->post('date_from');
        $date_to = $this->input->post('date_to');

        // Ambil data dari database
        $laporan = $this->m_transaksi->loadTransaksi($date_from, $date_to);
        $detail = $this->m_transaksi->loadDetail($date_from, $date_to);

        // Buat file Excel Baru
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan Transaksi');

        // ==== DESAIN HEADER (KOP SURAT) ====
        $sheet->setCellValue('A1', 'LAPORAN TRANSAKSI BANK SAMPAH');
        $sheet->mergeCells('A1:F1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16)->getColor()->setRGB('00926E');
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A2', 'Periode: ' . date('d F Y', strtotime($date_from)) . ' s/d ' . date('d F Y', strtotime($date_to)));
        $sheet->mergeCells('A2:F2');
        $sheet->getStyle('A2')->getFont()->setItalic(true)->setSize(11);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // ==== DESAIN TABEL HEADER ====
        $headers = ['No', 'Tanggal', 'Nama Nasabah', 'Setor (Debit)', 'Tarik (Kredit)', 'Keterangan Sampah'];
        $columnLetter = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($columnLetter . '4', $header);
            $columnLetter++;
        }

        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '00926E']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'DDDDDD']]]
        ];
        $sheet->getStyle('A4:F4')->applyFromArray($headerStyle);
        $sheet->getRowDimension('4')->setRowHeight(25);

        // ==== MASUKKAN DATA TRANSAKSI ====
        $rowNum = 5;
        $no = 1;
        $total_setor = 0;
        $total_tarik = 0;

        foreach ($laporan->result_array() as $row) {
            $total_setor += $row['debit'];
            $total_tarik += $row['kredit'];

            $sheet->setCellValue('A' . $rowNum, $no++);
            $sheet->setCellValue('B' . $rowNum, date('d/m/Y H:i', strtotime($row['tgl_tabungan_transaksi'])));
            $sheet->setCellValue('C' . $rowNum, $row['nasabah_username']);
            $sheet->setCellValue('D' . $rowNum, $row['debit']);
            $sheet->setCellValue('E' . $rowNum, $row['kredit']);

            // Menggabungkan detail sampah
            $detail_text = [];
            foreach ($detail->result_array() as $res) {
                if ($res['id_transaksi_sampah'] == $row['id_transaksi_sampah']) {
                    $detail_text[] = $res['jenis_sampah'] . ' (' . $res['berat_sampah'] . ' kg)';
                }
            }
            $keterangan = !empty($detail_text) ? implode(', ', $detail_text) : 'Penarikan Saldo Tabungan';
            $sheet->setCellValue('F' . $rowNum, $keterangan);

            // Styling baris data
            $sheet->getStyle('A' . $rowNum . ':B' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('D' . $rowNum . ':E' . $rowNum)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle('A' . $rowNum . ':F' . $rowNum)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setRGB('EEEEEE');
            
            $rowNum++;
        }

        // ==== BARIS TOTAL ====
        $sheet->setCellValue('A' . $rowNum, 'TOTAL KESELURUHAN');
        $sheet->mergeCells('A' . $rowNum . ':C' . $rowNum);
        $sheet->setCellValue('D' . $rowNum, $total_setor);
        $sheet->setCellValue('E' . $rowNum, $total_tarik);

        $totalStyle = [
            'font' => ['bold' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E8F5E9']],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '00926E']]]
        ];
        $sheet->getStyle('A' . $rowNum . ':F' . $rowNum)->applyFromArray($totalStyle);
        $sheet->getStyle('A' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('D' . $rowNum . ':E' . $rowNum)->getNumberFormat()->setFormatCode('#,##0');

        // Otomatis sesuaikan lebar kolom
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // ==== PROSES DOWNLOAD FILE ====
        $filename = 'Laporan_Bank_Sampah_' . $date_from . '_to_' . $date_to . '.xlsx';
        
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
        $top['username'] = $username;
        $top['adminCount'] = $this->m_dashboard->getAdminCount();
        $top['nasabahCount'] = $this->m_dashboard->getNasabahCount();
        $top['transaksiCount'] = $this->m_dashboard->getTransaksiCount();
        $top['artikelCount'] = $this->m_dashboard->getArtikelCount();

        $this->load->view('template/header');
        $this->load->view('template/sidebar');
        $this->load->view('template/topbar', $top);
        $this->load->view('banksampah/laporan');
        $this->load->view('template/footer');
    }

    function tesview(){
        $date_from = $this->input->post('date_from');
        $date_to = $this->input->post('date_to');

        $data['date_from'] = $date_from;
        $data['date_to'] = $date_to;
        $data['laporan'] = $this->m_transaksi->loadTransaksi($date_from, $date_to);
        $data['detail'] = $this->m_transaksi->loadDetail($date_from, $date_to);
        $this->load->view('v_laporan', $data);
        ?><script>console.log('pppp')</script> <?php
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
    }

    function pdftransaksi()
    {
        $date_from = $this->input->post('date_from');
        $date_to = $this->input->post('date_to');
        $data['title'] = "Laporan Transaksi Sampah";

        $data['date_from'] = $date_from;
        $data['date_to'] = $date_to;
        $data['laporan'] = $this->m_transaksi->loadTransaksi($date_from, $date_to);
        $data['detail'] = $this->m_transaksi->loadDetail($date_from, $date_to);
        
        // Memuat file desain PDF
        $html = $this->load->view('v_laporan', $data, true);

        $this->load->library('pdfgenerator');
        $data['title'] = "Laporan Transaksi Sampah";
        $file_pdf = $data['title'];
        $paper = 'A4';
        
        // === UPDATE DI SINI ===
        // Mengubah orientasi dari portrait menjadi landscape
        $orientation = "landscape"; 
        // ======================
        
        $this->pdfgenerator->generate($html, $file_pdf, $paper, $orientation);
    }


}