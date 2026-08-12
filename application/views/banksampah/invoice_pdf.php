<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?= isset($title) ? $title : 'Invoice Transaksi'; ?></title>
    <style>
        @page { margin: 10px; }
        body { font-family: 'Courier', 'Courier New', monospace; font-size: 9px; color: #000; line-height: 1.2; margin: 0; padding: 0; }
        .receipt-card { width: 100%; max-width: 280px; margin: 0 auto; border: 1px dashed #000; padding: 10px; background-color: #ffffff; }
        .header { text-align: center; border-bottom: 1px dashed #000; padding-bottom: 6px; margin-bottom: 6px; }
        .header h2 { margin: 0; color: #000; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: bold; }
        .header p { margin: 2px 0 0 0; font-size: 8px; color: #333; text-transform: uppercase; }
        
        .dashed-divider { border-bottom: 1px dashed #000; margin: 6px 0; }
        .double-divider { border-bottom: 2px dashed #000; margin: 6px 0; }

        .meta-table { width: 100%; border-collapse: collapse; margin-bottom: 4px; }
        .meta-table td { padding: 2px 0; font-size: 9px; }
        .meta-label { color: #000; width: 40%; }
        .meta-value { text-align: right; font-weight: bold; color: #000; }
        
        .items-table { width: 100%; border-collapse: collapse; margin-top: 4px; margin-bottom: 4px; }
        .items-table th, .items-table td { padding: 3px 0; }
        .items-table th { color: #000; text-align: left; font-size: 8px; text-transform: uppercase; border-bottom: 1px solid #000; font-weight: bold; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        
        .summary-table { width: 100%; margin-top: 4px; border-collapse: collapse; }
        .summary-table td { padding: 2px 0; font-size: 9px; }
        .grand-total { font-size: 11px; font-weight: bold; color: #000; }
        .footer-note { text-align: center; margin-top: 8px; font-size: 8px; color: #000; border-top: 1px dashed #000; padding-top: 6px; text-transform: uppercase; }
    </style>
</head>
<body>
    <?php 
        if (isset($header)) {
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
        } else if (isset($detail) && $detail->num_rows() > 0) {
            $row_leg = $detail->row_array();
            $is_tarik = ($row_leg['kredit'] > 0);
            $inv_no = 'INV-' . $row_leg['id_tabungan_transaksi'];
            $tipe = $is_tarik ? 'TARIK' : 'BELI';
            $status_pay = 'Lunas';
            $pihak = $row_leg['username'];
            $tgl = $row_leg['tgl_tabungan_transaksi'];
            $subtotal = $is_tarik ? $row_leg['kredit'] : $row_leg['debit'];
            $biaya = 0;
            $ket_biaya = '';
            $grand_total = $subtotal;
            $items = isset($sampah) ? $sampah->result() : array();
        }
    ?>

    <div class="receipt-card">
        <div class="header">
            <h2>MANKADIBALIRECYCLING</h2>
            <p>Bank Sampah & Daur Ulang</p>
            <p style="font-size: 7px; margin-top: 2px;">Nota Resmi Transaksi</p>
        </div>

        <div class="double-divider"></div>

        <table class="meta-table">
            <tr>
                <td class="meta-label">No Invoice:</td>
                <td class="meta-value"><?= htmlspecialchars($inv_no) ?></td>
            </tr>
            <tr>
                <td class="meta-label">Tanggal:</td>
                <td class="meta-value"><?= date('d/m/Y H:i', strtotime($tgl)) ?></td>
            </tr>
            <tr>
                <td class="meta-label">Tipe Trx:</td>
                <td class="meta-value">[ <?= $tipe ?> ]</td>
            </tr>
            <tr>
                <td class="meta-label"><?= ($tipe == 'JUAL') ? 'Buyer:' : 'Pihak/User:' ?></td>
                <td class="meta-value"><?= htmlspecialchars($pihak) ?></td>
            </tr>
            <tr>
                <td class="meta-label">Status Bayar:</td>
                <td class="meta-value">[ <?= strtoupper($status_pay) ?> ]</td>
            </tr>
        </table>

        <div class="dashed-divider"></div>

        <table class="items-table">
            <thead>
                <tr>
                    <th width="65%">Item</th>
                    <th width="35%" class="text-right">Total</th>
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
                            <td colspan="2" style="font-weight: bold; padding-top: 3px; text-transform: uppercase;">
                                <?= htmlspecialchars($nama_item) ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size: 8px; color: #333; padding-bottom: 3px;">
                                <?= number_format($berat_item, 2, ',', '.') ?> kg x Rp <?= number_format($harga_unit, 0, ',', '.') ?>
                            </td>
                            <td class="text-right font-bold" style="padding-bottom: 3px;">
                                Rp <?= number_format($total_item, 0, ',', '.') ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="dashed-divider"></div>

        <table class="summary-table">
            <tr>
                <td class="meta-label">Subtotal:</td>
                <td class="meta-value">Rp <?= number_format($subtotal, 0, ',', '.') ?></td>
            </tr>
            <?php if (!empty($biaya) && $biaya > 0): ?>
            <tr>
                <td class="meta-label">Biaya (<?= htmlspecialchars($ket_biaya ? $ket_biaya : 'Fee') ?>):</td>
                <td class="meta-value">+ Rp <?= number_format($biaya, 0, ',', '.') ?></td>
            </tr>
            <?php endif; ?>
            <tr>
                <td colspan="2"><div class="double-divider"></div></td>
            </tr>
            <tr>
                <td class="meta-label" style="font-weight: bold; font-size: 10px;">GRAND TOTAL:</td>
                <td class="meta-value grand-total">Rp <?= number_format($grand_total, 0, ',', '.') ?></td>
            </tr>
        </table>

        <div class="footer-note">
            *** TERIMA KASIH ***<br>
            Jaga Lingkungan Bersama Kami<br>
            <span style="font-size: 7px; color: #555;"><?= date('d/m/Y H:i:s') ?> WITA</span>
        </div>
    </div>
</body>
</html>
