<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Transaksi - MANKADIBALIRECYCLING</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Courier+Prime:wght@400;700&display=swap" rel="stylesheet">
    <script>
    tailwind.config = {
        theme: {
            extend: {
                fontFamily: { mono: ['Courier Prime', 'Courier', 'monospace'] }
            }
        }
    }
    </script>
    <style>
        body { font-family: 'Courier Prime', 'Courier', monospace; color: #000; }
        .dashed-line { border-bottom: 1px dashed #000; margin: 8px 0; }
        .double-dashed-line { border-bottom: 2px dashed #000; margin: 8px 0; }
        @media print {
            body { background: #ffffff !important; padding: 0 !important; margin: 0 !important; color: #000 !important; }
            .print\:hidden { display: none !important; }
            .receipt-box { box-shadow: none !important; border: 1px solid #000 !important; }
            @page { size: 80mm auto; margin: 0; }
        }
    </style>
</head>
<body class="bg-gray-200 font-mono antialiased text-black text-xs min-h-screen py-6 px-2 flex flex-col justify-center items-center">
    
    <div class="w-full max-w-[340px]">
        <!-- Top Action Controls (Hidden on Print) -->
        <div class="mb-4 flex justify-between items-center print:hidden">
            <a href="javascript:history.back()" class="inline-flex items-center font-bold text-black hover:underline">
                &larr; Kembali
            </a>
            <div class="flex items-center gap-2">
                <a href="<?= base_url('payment/pdf_invoice/' . (isset($header) ? $header->id_transaksi_sampah : ($detail->row_array()['id_tabungan_transaksi'] ?? ''))) ?>" target="_blank" class="bg-black hover:bg-gray-800 text-white text-[11px] font-bold px-3 py-1 rounded transition-colors shadow">
                    PDF
                </a>
                <button onclick="window.print()" class="bg-black hover:bg-gray-800 text-white text-[11px] font-bold px-3 py-1 rounded transition-colors shadow">
                    Cetak
                </button>
            </div>
        </div>

        <?php 
            if (isset($header)) {
                $id_trx = $header->id_transaksi_sampah;
                $inv_no = !empty($header->no_invoice) ? $header->no_invoice : 'INV-' . $header->id_transaksi_sampah;
                $tipe = strtoupper($header->tipe_transaksi ?? 'beli');
                $status_pay = $header->status_pembayaran ?? 'Lunas';
                $pihak = ($header->tipe_transaksi == 'jual') ? ($header->nama_pihak_luar ?? 'Buyer') : ($header->nasabah_username ?? 'Mitra');
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

        <!-- Thermal Receipt Container -->
        <div class="bg-white w-full shadow-2xl p-5 receipt-box border border-black rounded-sm text-black">
            
            <!-- Header Store Info -->
            <div class="text-center">
                <h1 class="text-sm font-bold tracking-tight uppercase border-b border-black pb-1 mb-1">MANKADIBALIRECYCLING</h1>
                <p class="text-[10px] uppercase tracking-wider">Bank Sampah & Daur Ulang</p>
                <p class="text-[9px] text-gray-700">Nota Resmi Transaksi</p>
            </div>

            <div class="double-dashed-line"></div>

            <!-- Transaction Details -->
            <div class="space-y-1 text-[11px]">
                <div class="flex justify-between">
                    <span>No. Invoice :</span>
                    <span class="font-bold"><?= htmlspecialchars($inv_no) ?></span>
                </div>
                <div class="flex justify-between">
                    <span>Tanggal     :</span>
                    <span><?= date('d/m/Y H:i', strtotime($tgl)) ?></span>
                </div>
                <div class="flex justify-between">
                    <span>Tipe Trx    :</span>
                    <span class="font-bold">[ <?= $tipe ?> ]</span>
                </div>
                <div class="flex justify-between">
                    <span><?= ($tipe == 'JUAL') ? 'Buyer       :' : 'Pihak/User  :' ?></span>
                    <span class="font-bold uppercase"><?= htmlspecialchars($pihak) ?></span>
                </div>
                <div class="flex justify-between">
                    <span>Status Bayar:</span>
                    <span class="font-bold">[ <?= strtoupper($status_pay) ?> ]</span>
                </div>
            </div>

            <div class="dashed-line"></div>

            <!-- Itemized List -->
            <div class="my-2 text-[11px]">
                <div class="flex justify-between font-bold border-b border-black pb-1 mb-1">
                    <span>ITEM</span>
                    <span>TOTAL</span>
                </div>
                
                <?php if (!empty($items)): ?>
                    <?php foreach($items as $item): ?>
                        <?php 
                            $nama_item = is_array($item) ? $item['jenis_sampah'] : $item->jenis_sampah;
                            $berat_item = is_array($item) ? $item['berat_sampah'] : $item->berat_sampah;
                            $harga_unit = is_array($item) ? ($item['harga_saat_setor'] ?? 0) : (!empty($item->harga_satuan) ? $item->harga_satuan : $item->harga_saat_setor);
                            $total_item = is_array($item) ? $item['total_harga'] : $item->total_harga;
                        ?>
                        <div class="py-1">
                            <div class="font-bold uppercase"><?= htmlspecialchars($nama_item) ?></div>
                            <div class="flex justify-between text-[10px]">
                                <span><?= number_format($berat_item, 2, ',', '.') ?> kg x Rp <?= number_format($harga_unit, 0, ',', '.') ?></span>
                                <span class="font-bold">Rp <?= number_format($total_item, 0, ',', '.') ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="dashed-line"></div>

            <!-- Financial Totals -->
            <div class="space-y-1 text-[11px]">
                <div class="flex justify-between">
                    <span>Subtotal</span>
                    <span>Rp <?= number_format($subtotal, 0, ',', '.') ?></span>
                </div>

                <?php if (!empty($biaya) && $biaya > 0): ?>
                <div class="flex justify-between">
                    <span>Biaya (<?= htmlspecialchars($ket_biaya ? $ket_biaya : 'Fee') ?>)</span>
                    <span>+ Rp <?= number_format($biaya, 0, ',', '.') ?></span>
                </div>
                <?php endif; ?>

                <div class="double-dashed-line"></div>

                <div class="flex justify-between text-sm font-bold pt-0.5">
                    <span>GRAND TOTAL</span>
                    <span>Rp <?= number_format($grand_total, 0, ',', '.') ?></span>
                </div>
            </div>

            <div class="double-dashed-line"></div>

            <!-- Footer Message -->
            <div class="mt-3 text-center text-[9px] uppercase space-y-0.5">
                <p class="font-bold">*** TERIMA KASIH ***</p>
                <p>Jaga Lingkungan Bersama Kami</p>
                <p class="text-[8px] text-gray-600 mt-2"><?= date('d/m/Y H:i:s') ?> WITA</p>
            </div>
        </div>
    </div>

</body>
</html>