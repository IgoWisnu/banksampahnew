<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Transaksi - MANKADIBALIRECYCLING</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    <script>
    tailwind.config = {
        theme: {
            extend: {
                fontFamily: { sans: ['Inter', 'sans-serif'], mono: ['Space Mono', 'monospace'] },
                colors: { brand: { green: '#00926E', dark: '#006c50' } }
            }
        }
    }
    </script>
    <style>
        .receipt-edge { position: relative; }
        .receipt-edge::after {
            content: '';
            position: absolute;
            bottom: -6px; left: 0; right: 0;
            height: 12px;
            background-image: radial-gradient(circle at 6px 12px, transparent 8px, #ffffff 8px);
            background-size: 12px 12px;
            background-repeat: repeat-x;
        }
        @media print {
            body { background: #ffffff !important; padding: 0 !important; margin: 0 !important; }
            .receipt-edge::after { display: none !important; }
            .receipt-edge { box-shadow: none !important; border: 1px solid #ddd !important; }
            @page { size: auto; margin: 8mm; }
        }
    </style>
</head>
<body class="bg-gray-100 font-sans antialiased text-gray-800 text-xs">
    
    <div class="w-full mx-auto min-h-screen py-4 px-2 flex flex-col justify-center items-center">
        <?php 
            if (isset($header)) {
                $id_trx = $header->id_transaksi_sampah;
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
                $id_trx = $row_leg['id_tabungan_transaksi'];
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

        <div class="w-full max-w-sm">
            <!-- Top Action Controls -->
            <div class="mb-3 flex justify-between items-center print:hidden">
                <a href="javascript:history.back()" class="inline-flex items-center font-medium text-gray-600 hover:text-gray-900 transition-colors">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali
                </a>
                <div class="flex items-center gap-1.5">
                    <a href="<?= base_url('payment/pdf_invoice/' . $id_trx) ?>" target="_blank" class="bg-red-600 hover:bg-red-700 text-white text-[11px] font-semibold px-2.5 py-1 rounded flex items-center transition-colors shadow-sm">
                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        PDF
                    </a>
                    <button onclick="window.print()" class="bg-gray-800 hover:bg-gray-900 text-white text-[11px] font-semibold px-2.5 py-1 rounded flex items-center transition-colors shadow-sm">
                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        Cetak
                    </button>
                </div>
            </div>

            <!-- Receipt Container -->
            <div class="bg-white w-full rounded-t-xl shadow-lg relative pt-5 pb-8 px-4 receipt-edge border border-gray-100">
                
                <!-- Brand Header -->
                <div class="text-center mb-3">
                    <h2 class="text-base font-bold tracking-tight text-gray-900 leading-tight">MANKADIBALIRECYCLING</h2>
                    <p class="text-[10px] text-gray-500">Bank Sampah & Management Daur Ulang</p>
                </div>

                <div class="w-full border-b border-dashed border-gray-300 my-2.5"></div>

                <!-- Invoice Meta Grid -->
                <div class="space-y-1 text-[11px]">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">No. Invoice:</span>
                        <span class="font-mono bg-gray-100 px-1.5 py-0.5 rounded text-gray-900 font-bold"><?= htmlspecialchars($inv_no) ?></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">Tanggal:</span>
                        <span class="font-medium text-gray-800"><?= date('d/m/Y H:i', strtotime($tgl)) ?> WITA</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">Tipe:</span>
                        <span class="font-bold px-2 py-0.5 rounded text-[10px] <?= ($tipe == 'JUAL') ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' ?>">
                            <?= $tipe ?>
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500"><?= ($tipe == 'JUAL') ? 'Buyer:' : 'Pengepul / Nasabah:' ?></span>
                        <span class="font-semibold text-gray-900"><?= htmlspecialchars($pihak) ?></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">Status Bayar:</span>
                        <?php if ($status_pay == 'Lunas'): ?>
                            <span class="font-bold px-2 py-0.5 rounded text-[10px] bg-emerald-100 text-emerald-800">
                                ✓ LUNAS
                            </span>
                        <?php else: ?>
                            <span class="font-bold px-2 py-0.5 rounded text-[10px] bg-amber-100 text-amber-800">
                                ⏳ PENDING
                            </span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="w-full border-b border-dashed border-gray-300 my-2.5"></div>

                <!-- Items Compact Table -->
                <div class="my-2">
                    <div class="flex justify-between text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-1.5 pb-1 border-b border-gray-100">
                        <span>Item Sampah</span>
                        <span>Total</span>
                    </div>
                    
                    <?php if (!empty($items)): ?>
                        <?php foreach($items as $item): ?>
                            <?php 
                                $nama_item = is_array($item) ? $item['jenis_sampah'] : $item->jenis_sampah;
                                $berat_item = is_array($item) ? $item['berat_sampah'] : $item->berat_sampah;
                                $harga_unit = is_array($item) ? ($item['harga_saat_setor'] ?? 0) : (!empty($item->harga_satuan) ? $item->harga_satuan : $item->harga_saat_setor);
                                $total_item = is_array($item) ? $item['total_harga'] : $item->total_harga;
                            ?>
                            <div class="flex justify-between items-start text-xs py-1.5 border-b border-gray-50 last:border-0">
                                <div>
                                    <span class="text-gray-900 font-semibold block leading-tight"><?= htmlspecialchars($nama_item) ?></span>
                                    <span class="text-[10px] text-gray-500">
                                        <?= number_format($berat_item, 2, ',', '.') ?> kg &times; Rp <?= number_format($harga_unit, 0, ',', '.') ?>
                                    </span>
                                </div>
                                <span class="font-mono font-semibold text-gray-900">
                                    Rp <?= number_format($total_item, 0, ',', '.') ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <div class="w-full border-b border-dashed border-gray-300 my-2.5"></div>

                <!-- Financial Totals -->
                <div class="space-y-1 text-xs text-gray-600 my-2">
                    <div class="flex justify-between">
                        <span>Subtotal Item</span>
                        <span class="font-semibold text-gray-800">Rp <?= number_format($subtotal, 0, ',', '.') ?></span>
                    </div>

                    <?php if (!empty($biaya) && $biaya > 0): ?>
                    <div class="flex justify-between text-amber-700">
                        <span>Biaya Tambahan (<?= htmlspecialchars($ket_biaya ? $ket_biaya : 'Fee') ?>)</span>
                        <span class="font-semibold">+ Rp <?= number_format($biaya, 0, ',', '.') ?></span>
                    </div>
                    <?php endif; ?>

                    <div class="flex justify-between items-center pt-2 border-t border-gray-200 mt-2">
                        <span class="text-gray-900 font-bold text-sm">Grand Total</span>
                        <span class="text-lg font-bold text-brand-green">Rp <?= number_format($grand_total, 0, ',', '.') ?></span>
                    </div>
                </div>

                <div class="w-full border-b border-dashed border-gray-300 my-2.5"></div>

                <!-- Footer Note -->
                <div class="mt-3 text-center text-[10px] text-gray-400">
                    <p>Terima kasih atas partisipasi kelestarian lingkungan bersama MANKADIBALIRECYCLING.</p>
                </div>
            </div>
        </div>

    </div>
</body>
</html>