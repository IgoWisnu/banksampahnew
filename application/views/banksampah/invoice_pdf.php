<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?= isset($title) ? $title : 'Invoice Transaksi'; ?></title>
    <style>
        @page { margin: 15px; }
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 10px; color: #222; line-height: 1.3; margin: 0; padding: 0; }
        .invoice-card { width: 100%; border: 1px solid #e0e0e0; border-radius: 6px; padding: 12px; background-color: #ffffff; }
        .header { text-align: center; border-bottom: 1px dashed #00926E; padding-bottom: 8px; margin-bottom: 8px; }
        .header h2 { margin: 0; color: #00926E; font-size: 15px; text-transform: uppercase; letter-spacing: 0.5px; }
        .header p { margin: 2px 0 0 0; font-size: 9px; color: #666; }
        
        .meta-table { width: 100%; margin-bottom: 8px; border-collapse: collapse; }
        .meta-table td { padding: 3px 0; font-size: 9px; }
        .meta-label { color: #555; }
        .meta-value { text-align: right; font-weight: bold; color: #111; }
        
        .badge-lunas { background-color: #d1e7dd; color: #0f5132; padding: 1px 5px; border-radius: 3px; font-weight: bold; font-size: 8px; }
        .badge-pending { background-color: #fff3cd; color: #664d03; padding: 1px 5px; border-radius: 3px; font-weight: bold; font-size: 8px; }
        .badge-tipe { background-color: #e2e3e5; color: #41464b; padding: 1px 5px; border-radius: 3px; font-weight: bold; font-size: 8px; }
        
        .items-table { width: 100%; border-collapse: collapse; margin-top: 6px; margin-bottom: 8px; }
        .items-table th, .items-table td { padding: 4px 5px; border-bottom: 1px solid #f0f0f0; }
        .items-table th { background-color: #f8f9fa; color: #444; text-align: left; font-size: 8px; text-transform: uppercase; border-top: 1px solid #ddd; border-bottom: 1px solid #ddd; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        
        .summary-table { width: 100%; margin-top: 6px; border-collapse: collapse; }
        .summary-table td { padding: 3px 0; font-size: 9.5px; }
        .grand-total { font-size: 13px; font-weight: bold; color: #00926E; }
        .footer-note { text-align: center; margin-top: 10px; font-size: 8px; color: #888; border-top: 1px dashed #e0e0e0; padding-top: 6px; }
    </style>
</head>
<body>
    <?php 
        $inv_no = !empty($header->no_invoice) ? $header->no_invoice : 'INV-' . $header->id_transaksi_sampah;
        $tipe = strtoupper($header->tipe_transaksi ?? 'beli');
        $status_pay = $header->status_pembayaran ?? 'Lunas';
        $pihak = ($header->tipe_transaksi == 'jual') ? ($header->nama_pihak_luar ?? 'Buyer') : ($header->nasabah_username ?? 'Nasabah');
        $tgl = $header->tgl_transaksi;
        $subtotal = $header->total_transaksi ?? 0;
        $biaya = $header->biaya_tambahan ?? 0;
        $ket_biaya = $header->keterangan_biaya ?? '';
        $grand_total = !empty($header->grand_total) ? $header->grand_total : ($subtotal + $biaya);
        $items = isset($details) ? $details : array();
    ?>

    <div class="invoice-card">
        <div class="header">
            <h2>MANKADIBALIRECYCLING</h2>
            <p>Bank Sampah & Management Daur Ulang</p>
        </div>

        <table class="meta-table">
            <tr>
                <td class="meta-label">No Invoice:</td>
                <td class="meta-value" style="font-family: monospace; font-size: 10px;"><?= htmlspecialchars($inv_no) ?></td>
            </tr>
            <tr>
                <td class="meta-label">Tanggal Transaksi:</td>
                <td class="meta-value"><?= date('d/m/Y H:i', strtotime($tgl)) ?> WITA</td>
            </tr>
            <tr>
                <td class="meta-label">Tipe Transaksi:</td>
                <td class="meta-value"><span class="badge-tipe"><?= $tipe ?></span></td>
            </tr>
            <tr>
                <td class="meta-label"><?= ($tipe == 'JUAL') ? 'Buyer:' : 'Pengepul / Nasabah:' ?></td>
                <td class="meta-value"><?= htmlspecialchars($pihak) ?></td>
            </tr>
            <tr>
                <td class="meta-label">Status Bayar:</td>
                <td class="meta-value">
                    <?php if ($status_pay == 'Lunas'): ?>
                        <span class="badge-lunas">LUNAS</span>
                    <?php else: ?>
                        <span class="badge-pending">PENDING</span>
                    <?php endif; ?>
                </td>
            </tr>
        </table>

        <table class="items-table">
            <thead>
                <tr>
                    <th width="45%">Jenis Sampah</th>
                    <th width="25%" class="text-right">Qty & Price</th>
                    <th width="30%" class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($items)): ?>
                    <?php foreach($items as $item): ?>
                        <?php 
                            $nama_item = is_array($item) ? $item['jenis_sampah'] : $item->jenis_sampah;
                            $berat_item = is_array($item) ? $item['berat_sampah'] : $item->berat_sampah;
                            $harga_unit = is_array($item) ? ($item['harga_saat_setor'] ?? 0) : (!empty($item->harga_satuan) ? $item->harga_satuan : $item->harga_saat_setor);
                            $total_item = is_array($item) ? $item['total_harga'] : $item->total_harga;
                        ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($nama_item) ?></strong></td>
                            <td class="text-right">
                                <?= number_format($berat_item, 2, ',', '.') ?> kg &times;<br>
                                Rp <?= number_format($harga_unit, 0, ',', '.') ?>
                            </td>
                            <td class="text-right font-bold">
                                Rp <?= number_format($total_item, 0, ',', '.') ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <table class="summary-table">
            <tr>
                <td class="meta-label">Subtotal Item:</td>
                <td class="meta-value">Rp <?= number_format($subtotal, 0, ',', '.') ?></td>
            </tr>
            <?php if (!empty($biaya) && $biaya > 0): ?>
            <tr>
                <td class="meta-label">Biaya Tambahan (<?= htmlspecialchars($ket_biaya ? $ket_biaya : 'Fee') ?>):</td>
                <td class="meta-value">+ Rp <?= number_format($biaya, 0, ',', '.') ?></td>
            </tr>
            <?php endif; ?>
            <tr>
                <td class="meta-label" style="font-weight: bold; font-size: 11px;">Grand Total:</td>
                <td class="meta-value grand-total">Rp <?= number_format($grand_total, 0, ',', '.') ?></td>
            </tr>
        </table>

        <div class="footer-note">
            Terima kasih atas partisipasi kelestarian lingkungan bersama MANKADIBALIRECYCLING.
        </div>
    </div>
</body>
</html>
