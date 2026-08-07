<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?= isset($title) ? $title : 'Laporan Arus Kas & Invoice'; ?></title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 10px; color: #333; line-height: 1.4; }
        .header { text-align: center; border-bottom: 2px solid #00926E; padding-bottom: 10px; margin-bottom: 15px; }
        .header h2 { margin: 0; color: #00926E; font-size: 20px; text-transform: uppercase; letter-spacing: 1px; }
        .header p { margin: 4px 0 0 0; font-size: 11px; color: #555; }
        
        .summary-box { width: 100%; margin-bottom: 15px; border-collapse: collapse; }
        .summary-box td { padding: 8px; border: 1px solid #c1efde; background-color: #f4fbf8; vertical-align: top; }
        .summary-title { font-size: 9px; text-transform: uppercase; color: #555; font-weight: bold; margin-bottom: 3px; }
        .summary-value { font-size: 13px; font-weight: bold; }
        .text-success { color: #198754; }
        .text-danger { color: #dc3545; }
        .text-warning { color: #d39e00; }
        .text-info { color: #0dcaf0; }
        
        table.data-table { width: 100%; border-collapse: collapse; margin-top: 5px; }
        table.data-table th, table.data-table td { padding: 6px 8px; border: 1px solid #ddd; vertical-align: top; }
        table.data-table th { background-color: #00926E; color: #ffffff; text-align: center; font-weight: bold; text-transform: uppercase; font-size: 9px; }
        table.data-table tbody tr:nth-child(even) { background-color: #fcfcfc; }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .badge-lunas { background-color: #d1e7dd; color: #0f5132; padding: 2px 6px; border-radius: 4px; font-weight: bold; font-size: 8px; }
        .badge-pending { background-color: #fff3cd; color: #664d03; padding: 2px 6px; border-radius: 4px; font-weight: bold; font-size: 8px; }
        .badge-tipe { background-color: #e2e3e5; color: #41464b; padding: 2px 6px; border-radius: 4px; font-weight: bold; font-size: 8px; }
    </style>
</head>

<body>
    <div class="header">
        <h2>Laporan Arus Kas & Management Invoice</h2>
        <p>
            Periode: <strong><?= date('d F Y', strtotime($date_from)) ?></strong> s/d <strong><?= date('d F Y', strtotime($date_to)) ?></strong>
            <?php if (!empty($banjar_name)): ?>
                | Banjar: <strong><?= htmlspecialchars($banjar_name) ?></strong>
            <?php endif; ?>
        </p>
    </div>

    <!-- Ringkasan Arus Kas & Buku Besar -->
    <table class="summary-box">
        <tr>
            <td width="20%">
                <div class="summary-title">Kas Masuk (Jual Lunas)</div>
                <div class="summary-value text-success">+ Rp <?= number_format($fin_summary->kas_masuk ?? 0, 0, ',', '.') ?></div>
                <small style="font-size: 8px; color: #777;">Omset: Rp <?= number_format($fin_summary->total_omset_jual ?? 0, 0, ',', '.') ?></small>
            </td>
            <td width="20%">
                <div class="summary-title">Kas Keluar (Beli Lunas)</div>
                <div class="summary-value text-danger">- Rp <?= number_format($fin_summary->kas_keluar ?? 0, 0, ',', '.') ?></div>
                <small style="font-size: 8px; color: #777;">Beli: Rp <?= number_format($fin_summary->total_pembelian ?? 0, 0, ',', '.') ?></small>
            </td>
            <td width="20%">
                <div class="summary-title">Utang Usaha (Beli Pending)</div>
                <div class="summary-value text-warning">Rp <?= number_format($fin_summary->utang_beli ?? 0, 0, ',', '.') ?></div>
                <small style="font-size: 8px; color: #777;">Kewajiban Pengepul</small>
            </td>
            <td width="20%">
                <div class="summary-title">Piutang Usaha (Jual Pending)</div>
                <div class="summary-value text-info">Rp <?= number_format($fin_summary->piutang_jual ?? 0, 0, ',', '.') ?></div>
                <small style="font-size: 8px; color: #777;">Tagihan Buyer</small>
            </td>
            <td width="20%">
                <div class="summary-title">Saldo Arus Kas Net</div>
                <div class="summary-value <?= ($fin_summary->saldo_kas ?? 0) >= 0 ? 'text-success' : 'text-danger' ?>">
                    Rp <?= number_format($fin_summary->saldo_kas ?? 0, 0, ',', '.') ?>
                </div>
                <small style="font-size: 8px; color: #777;">Fee: Rp <?= number_format($fin_summary->total_biaya_tambahan ?? 0, 0, ',', '.') ?></small>
            </td>
        </tr>
    </table>

    <!-- Tabel Rincian Invoice -->
    <table class="data-table">
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="12%">No Invoice</th>
                <th width="8%">Tipe</th>
                <th width="11%">Tanggal</th>
                <th width="15%">Pihak Terkait</th>
                <th width="10%">Subtotal</th>
                <th width="9%">Fee</th>
                <th width="11%">Grand Total</th>
                <th width="8%">Status</th>
                <th width="12%">Rincian Sampah</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            $grand_subtotal = 0;
            $grand_fee = 0;
            $grand_total_all = 0;

            if ($invoices && $invoices->num_rows() > 0):
                foreach ($invoices->result() as $row):
                    $grand_subtotal += $row->total_transaksi;
                    $grand_fee += $row->biaya_tambahan;
                    $grand_total_all += $row->grand_total;
            ?>
            <tr>
                <td class="text-center"><?= $no++ ?></td>
                <td class="font-bold text-center" style="color:#00926E;"><?= htmlspecialchars($row->no_invoice) ?></td>
                <td class="text-center">
                    <span class="badge-tipe"><?= strtoupper($row->tipe_transaksi) ?></span>
                </td>
                <td class="text-center"><?= date('d/m/Y H:i', strtotime($row->tgl_transaksi)) ?></td>
                <td>
                    <?php if ($row->tipe_transaksi == 'jual'): ?>
                        <strong><?= htmlspecialchars($row->nama_pihak_luar ? $row->nama_pihak_luar : 'Buyer Eksternal') ?></strong>
                    <?php else: ?>
                        <strong><?= htmlspecialchars($row->nasabah_username ? $row->nasabah_username : ($row->nama_pihak_luar ? $row->nama_pihak_luar : 'Nasabah')) ?></strong>
                    <?php endif; ?>
                </td>
                <td class="text-right">Rp <?= number_format($row->total_transaksi, 0, ',', '.') ?></td>
                <td class="text-right">Rp <?= number_format($row->biaya_tambahan, 0, ',', '.') ?></td>
                <td class="text-right font-bold">Rp <?= number_format($row->grand_total, 0, ',', '.') ?></td>
                <td class="text-center">
                    <?php if ($row->status_pembayaran == 'Lunas'): ?>
                        <span class="badge-lunas">LUNAS</span>
                    <?php else: ?>
                        <span class="badge-pending">PENDING</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php 
                        $items_text = [];
                        if (isset($details_map[$row->id_transaksi_sampah])) {
                            foreach ($details_map[$row->id_transaksi_sampah] as $dt) {
                                $items_text[] = htmlspecialchars($dt->jenis_sampah) . ' (' . $dt->berat_sampah . ' kg)';
                            }
                        }
                        echo !empty($items_text) ? implode(', ', $items_text) : '-';
                    ?>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php else: ?>
            <tr>
                <td colspan="10" class="text-center" style="padding: 15px; color: #777;">
                    <i>Tidak ada data invoice transaksi pada periode ini.</i>
                </td>
            </tr>
            <?php endif; ?>
        </tbody>

        <tfoot>
            <tr>
                <th colspan="5" class="text-right">TOTAL KESELURUHAN PERIODE</th>
                <th class="text-right">Rp <?= number_format($grand_subtotal, 0, ',', '.') ?></th>
                <th class="text-right">Rp <?= number_format($grand_fee, 0, ',', '.') ?></th>
                <th class="text-right">Rp <?= number_format($grand_total_all, 0, ',', '.') ?></th>
                <th colspan="2"></th>
            </tr>
        </tfoot>
    </table>
</body>
</html>