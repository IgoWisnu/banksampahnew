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
        $sheet->setCellValue('A1', 'MANKADIBALIRECYCLING');
        $sheet->mergeCells('A1:J1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16)->getColor()->setRGB('00926E');
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A2', 'LAPORAN ARUS KAS & INVOICE PEMBAYARAN');
        $sheet->mergeCells('A2:J2');
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(13);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A3', 'Periode: ' . date('d F Y', strtotime($date_from)) . ' s/d ' . date('d F Y', strtotime($date_to)) . ' | Dicetak pada: ' . date('d F Y H:i:s') . ' WITA');
        $sheet->mergeCells('A3:J3');
        $sheet->getStyle('A3')->getFont()->setItalic(true)->setSize(10);
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

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
        $this->load->model('m_payment');
            
        $username = $this->session->userdata('username');
        $top['username']       = $username;
        $top['adminCount']     = $this->m_dashboard->getAdminCount();
        $top['nasabahCount']   = $this->m_dashboard->getNasabahCount();
        $top['transaksiCount'] = $this->m_dashboard->getTransaksiCount();
        $top['artikelCount']   = $this->m_dashboard->getArtikelCount();

        $data['pihak_terkait'] = $this->m_payment->getPihakTerkaitOptions();

        $this->load->view('template/header');
        $this->load->view('template/sidebar');
        $this->load->view('template/topbar', $top);
        $this->load->view('banksampah/laporan', $data);
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

    public function excelstok()
    {
        $this->load->model('m_jenis_sampah');
        $stok = $this->m_jenis_sampah->getLaporanStok();
        $log  = $this->m_jenis_sampah->getStokLogTrail(200); // 200 data terakhir untuk excel

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan Stok Sampah');

        // ==== KOP HEADER ====
        $sheet->setCellValue('A1', 'MANKADIBALIRECYCLING');
        $sheet->mergeCells('A1:F1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16)->getColor()->setRGB('00926E');
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A2', 'LAPORAN STOK SAMPAH (INVENTORY LEDGER)');
        $sheet->mergeCells('A2:F2');
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(13);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A3', 'Dicetak pada: ' . date('d F Y H:i:s') . ' WITA');
        $sheet->mergeCells('A3:F3');
        $sheet->getStyle('A3')->getFont()->setItalic(true)->setSize(10);
        $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // ==== BAGIAN 1: SALDO STOK REALTIME ====
        $sheet->setCellValue('A4', '1. SALDO STOK SAMPAH REALTIME');
        $sheet->getStyle('A4')->getFont()->setBold(true);

        $headerStok = ['No', 'Jenis Sampah', 'Kategori', 'Sub Kategori', 'Harga Catalog', 'Stok Tersisa'];
        $sheet->fromArray($headerStok, NULL, 'A5');
        $sheet->getStyle('A5:F5')->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
        $sheet->getStyle('A5:F5')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('00926E');
        $sheet->getStyle('A5:F5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $rowNum = 6;
        $no = 1;
        foreach($stok->result_array() as $row) {
            $sheet->setCellValue('A'.$rowNum, $no++);
            $sheet->setCellValue('B'.$rowNum, $row['jenis_sampah']);
            $sheet->setCellValue('C'.$rowNum, $row['kategori_sampah']);
            $sheet->setCellValue('D'.$rowNum, $row['sub_kategori_sampah']);
            $sheet->setCellValue('E'.$rowNum, $row['harga_sampah']);
            $sheet->setCellValue('F'.$rowNum, $row['stok_tersisa']);

            $sheet->getStyle('E'.$rowNum)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle('F'.$rowNum)->getNumberFormat()->setFormatCode('#,##0.00');
            $rowNum++;
        }
        
        $styleArray = [
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN]
            ]
        ];
        $sheet->getStyle('A5:F'.($rowNum-1))->applyFromArray($styleArray);

        // ==== BAGIAN 2: AUDIT TRAIL ====
        $rowNum += 3;
        $sheet->setCellValue('A'.$rowNum, '2. AUDIT TRAIL PERGERAKAN STOK');
        $sheet->getStyle('A'.$rowNum)->getFont()->setBold(true);
        $rowNum++;

        $headerLog = ['No', 'Waktu Mutasi', 'Jenis Sampah', 'Tipe Mutasi', 'Jumlah Mutasi (Kg)', 'Stok Sebelum (Kg)', 'Stok Sesudah (Kg)', 'No Invoice'];
        $sheet->fromArray($headerLog, NULL, 'A'.$rowNum);
        $sheet->getStyle('A'.$rowNum.':H'.$rowNum)->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
        $sheet->getStyle('A'.$rowNum.':H'.$rowNum)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('00926E');
        $sheet->getStyle('A'.$rowNum.':H'.$rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        $startLogRow = $rowNum;
        $rowNum++;
        $noLog = 1;
        foreach($log->result_array() as $l) {
            $sheet->setCellValue('A'.$rowNum, $noLog++);
            $sheet->setCellValue('B'.$rowNum, $l['created_at']);
            $sheet->setCellValue('C'.$rowNum, $l['jenis_sampah']);
            $sheet->setCellValue('D'.$rowNum, strtoupper($l['tipe_pergerakan']));
            $sheet->setCellValue('E'.$rowNum, $l['jumlah']);
            $sheet->setCellValue('F'.$rowNum, $l['stok_sebelum']);
            $sheet->setCellValue('G'.$rowNum, $l['stok_sesudah']);
            $sheet->setCellValue('H'.$rowNum, $l['no_invoice']);

            $sheet->getStyle('E'.$rowNum)->getNumberFormat()->setFormatCode('#,##0.00');
            $sheet->getStyle('F'.$rowNum)->getNumberFormat()->setFormatCode('#,##0.00');
            $sheet->getStyle('G'.$rowNum)->getNumberFormat()->setFormatCode('#,##0.00');
            
            if($l['tipe_pergerakan'] == 'masuk') {
                $sheet->getStyle('D'.$rowNum)->getFont()->getColor()->setRGB('198754');
            } else {
                $sheet->getStyle('D'.$rowNum)->getFont()->getColor()->setRGB('dc3545');
            }
            $rowNum++;
        }
        
        $sheet->getStyle('A'.$startLogRow.':H'.($rowNum-1))->applyFromArray($styleArray);

        // Auto size columns
        foreach(range('A','H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Output file
        $writer = new Xlsx($spreadsheet);
        $filename = 'Laporan_Stok_Sampah_'.date('Ymd').'.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }

    public function excelmatrix()
    {
        $this->load->model('m_payment');
        
        $date_from = $this->input->post('date_from') ? $this->input->post('date_from') : ($this->input->get('date_from') ? $this->input->get('date_from') : date('Y-m-01'));
        $date_to   = $this->input->post('date_to') ? $this->input->post('date_to') : ($this->input->get('date_to') ? $this->input->get('date_to') : date('Y-m-d'));
        $tipe      = $this->input->post('tipe') ? $this->input->post('tipe') : ($this->input->get('tipe') ? $this->input->get('tipe') : 'all');
        $pihak     = $this->input->post('pihak_terkait') ? $this->input->post('pihak_terkait') : ($this->input->get('pihak_terkait') ? $this->input->get('pihak_terkait') : 'Semua Pihak');
        
        $dataMatrix = $this->m_payment->getMatrixData($tipe, $pihak, $date_from, $date_to);
        
        $dates = [];
        $items = [];
        $matrix = [];
        
        foreach ($dataMatrix as $row) {
            $tgl = $row['tgl'];
            $jenis = $row['jenis_sampah'];
            
            $dates[$tgl] = true;
            $items[$jenis] = true;
            
            if(!isset($matrix[$jenis][$tgl])) {
                $matrix[$jenis][$tgl] = ['berat' => 0, 'nominal' => 0];
            }
            $matrix[$jenis][$tgl]['berat'] += $row['berat_sampah'];
            $matrix[$jenis][$tgl]['nominal'] += $row['subtotal'];
        }
        
        ksort($dates);
        ksort($items);
        
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle(substr('Matriks_' . str_replace(['/', '\\', '?', '*', ':', '['], '_', $pihak), 0, 31));
        
        // ==== KOP HEADER ====
        $sheet->setCellValue('A1', 'MANKADIBALIRECYCLING');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16)->getColor()->setRGB('00926E');
        
        $sheet->setCellValue('A2', 'LAPORAN MATRIKS TRANSAKSI PER PIHAK');
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(13);
        
        $sheet->setCellValue('A3', 'Pihak Terkait : ' . ($pihak ? $pihak : 'Semua Pihak') . ' | Tipe: ' . strtoupper($tipe));
        $sheet->getStyle('A3')->getFont()->setBold(true)->setSize(11);
        
        $tgl_str = (!empty($date_from) && !empty($date_to)) 
            ? date('d F Y', strtotime($date_from)) . ' s/d ' . date('d F Y', strtotime($date_to))
            : 'Semua Periode';
        $sheet->setCellValue('A4', 'Periode Transaksi : ' . $tgl_str);
        $sheet->getStyle('A4')->getFont()->setItalic(true)->setSize(10);
        
        $sheet->setCellValue('A5', 'Waktu Cetak       : ' . date('d F Y H:i:s') . ' WITA');
        $sheet->getStyle('A5')->getFont()->setItalic(true)->setSize(9)->getColor()->setRGB('555555');
        
        // Dynamic columns for dates
        $sheet->setCellValue('A7', 'No');
        $sheet->setCellValue('B7', 'JENIS SAMPAH / ITEM');
        $sheet->mergeCells('A7:A8');
        $sheet->mergeCells('B7:B8');
        
        $colIndex = 3; // C
        $dateCols = [];
        foreach (array_keys($dates) as $d) {
            $dateFormatted = date('d-M-Y', strtotime($d));
            $colLetter1 = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);
            $colLetter2 = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex+1);
            
            $sheet->setCellValue($colLetter1 . '7', $dateFormatted);
            $sheet->mergeCells($colLetter1.'7:'.$colLetter2.'7');
            
            $sheet->setCellValue($colLetter1 . '8', 'Berat (Kg)');
            $sheet->setCellValue($colLetter2 . '8', 'Nominal (Rp)');
            
            $dateCols[$d] = ['colBerat' => $colIndex, 'colNominal' => $colIndex+1];
            $colIndex += 2;
        }
        
        // Sub Total & Total columns
        $subTotalCol = $colIndex;
        $totalCol = $colIndex + 1;
        $subTotalLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($subTotalCol);
        $totalLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($totalCol);
        
        $sheet->setCellValue($subTotalLetter . '7', 'TOTAL BERAT (KG)');
        $sheet->setCellValue($totalLetter . '7', 'TOTAL NOMINAL (RP)');
        $sheet->mergeCells($subTotalLetter.'7:'.$subTotalLetter.'8');
        $sheet->mergeCells($totalLetter.'7:'.$totalLetter.'8');
        
        $lastHeaderCol = $totalLetter;
        
        // Header styling
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '00926E']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '004D3A']]]
        ];
        $sheet->getStyle('A7:'.$lastHeaderCol.'8')->applyFromArray($headerStyle);
        $sheet->getRowDimension('7')->setRowHeight(22);
        $sheet->getRowDimension('8')->setRowHeight(20);
        
        // Fill Rows
        $rowNum = 9;
        $no = 1;
        
        $grandTotalBerat = []; 
        $grandTotalNominal = []; 
        $grandTotalAllBerat = 0;
        $grandTotalAllNominal = 0;
        
        foreach (array_keys($dates) as $d) {
            $grandTotalBerat[$d] = 0;
            $grandTotalNominal[$d] = 0;
        }
        
        foreach (array_keys($items) as $item) {
            $sheet->setCellValue('A'.$rowNum, $no++);
            $sheet->setCellValue('B'.$rowNum, $item);
            
            $rowSubTotalBerat = 0;
            $rowTotalNominal = 0;
            
            foreach (array_keys($dates) as $d) {
                $bCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($dateCols[$d]['colBerat']);
                $nCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($dateCols[$d]['colNominal']);
                
                $berat = isset($matrix[$item][$d]) ? $matrix[$item][$d]['berat'] : 0;
                $nominal = isset($matrix[$item][$d]) ? $matrix[$item][$d]['nominal'] : 0;
                
                $sheet->setCellValue($bCol.$rowNum, $berat);
                $sheet->setCellValue($nCol.$rowNum, $nominal);
                
                $sheet->getStyle($bCol.$rowNum)->getNumberFormat()->setFormatCode('#,##0.00');
                $sheet->getStyle($nCol.$rowNum)->getNumberFormat()->setFormatCode('#,##0');
                
                $rowSubTotalBerat += $berat;
                $rowTotalNominal += $nominal;
                
                $grandTotalBerat[$d] += $berat;
                $grandTotalNominal[$d] += $nominal;
            }
            
            $sheet->setCellValue($subTotalLetter.$rowNum, $rowSubTotalBerat);
            $sheet->setCellValue($totalLetter.$rowNum, $rowTotalNominal);
            $sheet->getStyle($subTotalLetter.$rowNum)->getNumberFormat()->setFormatCode('#,##0.00');
            $sheet->getStyle($totalLetter.$rowNum)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle($subTotalLetter.$rowNum.':'.$totalLetter.$rowNum)->getFont()->setBold(true);
            
            $sheet->getStyle('A'.$rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            
            $grandTotalAllBerat += $rowSubTotalBerat;
            $grandTotalAllNominal += $rowTotalNominal;
            
            $rowNum++;
        }
        
        // Footer Row immediately after data
        $sheet->setCellValue('A'.$rowNum, 'TOTAL KESELURUHAN');
        $sheet->mergeCells('A'.$rowNum.':B'.$rowNum);
        
        foreach (array_keys($dates) as $d) {
            $bCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($dateCols[$d]['colBerat']);
            $nCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($dateCols[$d]['colNominal']);
            
            $sheet->setCellValue($bCol.$rowNum, $grandTotalBerat[$d]);
            $sheet->setCellValue($nCol.$rowNum, $grandTotalNominal[$d]);
            
            $sheet->getStyle($bCol.$rowNum)->getNumberFormat()->setFormatCode('#,##0.00');
            $sheet->getStyle($nCol.$rowNum)->getNumberFormat()->setFormatCode('#,##0');
        }
        
        $sheet->setCellValue($subTotalLetter.$rowNum, $grandTotalAllBerat);
        $sheet->setCellValue($totalLetter.$rowNum, $grandTotalAllNominal);
        $sheet->getStyle($subTotalLetter.$rowNum)->getNumberFormat()->setFormatCode('#,##0.00');
        $sheet->getStyle($totalLetter.$rowNum)->getNumberFormat()->setFormatCode('#,##0');
        
        $footerStyle = [
            'font' => ['bold' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E8F5E9']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ];
        $sheet->getStyle('A'.$rowNum.':'.$lastHeaderCol.$rowNum)->applyFromArray($footerStyle);
        
        // Clean Borders Across All Header, Data, and Footer Rows
        $styleArray = [
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '333333']]
            ]
        ];
        $sheet->getStyle('A7:'.$lastHeaderCol.$rowNum)->applyFromArray($styleArray);
        
        // Auto size cols
        for ($i = 1; $i <= $totalCol; $i++) {
            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i);
            $sheet->getColumnDimension($colLetter)->setAutoSize(true);
        }
        
        $writer = new Xlsx($spreadsheet);
        $cleanPihak = preg_replace('/[^A-Za-z0-9_\-]/', '_', $pihak);
        $filename = 'Laporan_Matriks_'.($cleanPihak ? $cleanPihak : 'Semua').'_'.date('Ymd_His').'.xlsx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }
}