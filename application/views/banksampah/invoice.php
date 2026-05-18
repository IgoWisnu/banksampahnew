<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Transaksi - Banksampah</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    <script>
    tailwind.config = {
        theme: {
        extend: {
            fontFamily: { 
                sans: ['Inter', 'sans-serif'],
                mono: ['Space Mono', 'monospace']
            },
            colors: { brand: { green: '#00926E', dark: '#006c50', yellow: '#f59e0b', light: '#fef3c7' } }
        }
        }
    }
    </script>
    <style>
        .receipt-edge {
            position: relative;
        }
        .receipt-edge::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            right: 0;
            height: 16px;
            background-image: radial-gradient(circle at 8px 16px, transparent 10px, #ffffff 10px);
            background-size: 16px 16px;
            background-repeat: repeat-x;
        }
    </style>
</head>
<body class="bg-gray-100 font-sans antialiased text-gray-800">
    
    <div class="w-full mx-auto bg-gray-50 min-h-screen relative shadow-none overflow-x-hidden pb-24 md:pb-32 flex flex-col justify-center items-center">
        <!-- Header Green Block -->
        <div class="absolute top-0 left-0 right-0 h-[280px] bg-brand-green shadow-md z-0 overflow-hidden">
            <img src="<?= base_url() ?>img/trash.jpeg"
                class="hidden md:block absolute right-0 top-0 w-2/3 h-full object-cover mix-blend-overlay opacity-20"
                alt="Background Graphic">
            <div class="absolute inset-0 bg-gradient-to-b from-brand-dark/80 via-brand-green/90 to-brand-green"></div>
        </div>

        <div class="relative z-10 w-full max-w-sm md:max-w-md px-4 mt-8">
            <div class="mb-6 flex">
                <?php if($this->session->userdata('role') == 'admin'): ?>
                    <a href="<?=base_url()?>dashboard/loadTransaksi" class="inline-flex items-center text-sm font-semibold text-white/90 hover:text-white transition-colors">
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Kembali ke Dashboard
                    </a>
                <?php else: ?>
                    <a href="<?=base_url()?>home/loadArtikel" class="inline-flex items-center text-sm font-semibold text-white/90 hover:text-white transition-colors">
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Kembali ke Beranda
                    </a>
                <?php endif; ?>
            </div>

            <!-- Receipt Container -->
            <?php foreach($detail->result_array() as $key){ ?>
            <div class="bg-white w-full rounded-t-2xl shadow-2xl relative pt-10 pb-12 px-6 receipt-edge mb-8">
                
                <!-- Success Icon -->
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 scale-110 shadow-inner">
                    <svg class="w-8 h-8 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                
                <h2 class="text-2xl font-bold text-center text-gray-900 mb-1">Transaksi Berhasil</h2>
                <p class="text-center text-gray-500 text-sm mb-6">Terima kasih telah menggunakan layanan kami</p>

                <!-- Divider -->
                <div class="w-full border-b-2 border-dashed border-gray-200 my-4"></div>

                <!-- Info Block -->
                <div class="flex justify-between items-center text-sm text-gray-600 my-3">
                    <span class="font-medium"><?=$key['tgl_tabungan_transaksi'] ?></span>
                    <span class="font-mono bg-gray-100 px-2 py-1 rounded text-xs">User: <?=$key['id_user']?></span>
                </div>

                <!-- Divider -->
                <div class="w-full border-b-2 border-dashed border-gray-200 my-4"></div>

                <!-- Total Payment -->
                <?php if ($key['kredit'] == 0): // Setor Sampah ?>
                    <div class="flex justify-between items-center my-2 text-sm">
                        <span class="text-gray-500 font-medium">Subtotal</span>
                        <span class="font-medium text-gray-800">Rp <?= number_format($key['debit'], 0, ',', '.') ?></span>
                    </div>
                    <?php if (isset($key['margin']) && $key['margin'] > 0): ?>
                    <div class="flex justify-between items-center my-2 text-sm">
                        <span class="text-gray-500 font-medium">Margin</span>
                        <span class="font-medium text-red-500">- Rp <?= number_format($key['margin'], 0, ',', '.') ?></span>
                    </div>
                    <?php endif; ?>
                    <div class="w-full border-b border-gray-100 my-3"></div>
                <?php endif; ?>

                <div class="flex justify-between items-center my-4">
                    <span class="text-gray-600 font-semibold">Total Nilai</span>
                    <?php $totalNilai = (isset($key['debit_final']) && $key['debit_final'] > 0 ? $key['debit_final'] : $key['debit']) + $key['kredit']; ?>
                    <span class="text-2xl font-bold text-brand-green">Rp <?= number_format($totalNilai, 0, ',', '.') ?></span>
                </div>

                <!-- Divider -->
                <div class="w-full border-b-2 border-dashed border-gray-200 my-4"></div>

                <!-- Transaction Details -->
                <div class="my-4">
                    <h3 class="text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-3">Detail Transaksi</h3>
                    
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-gray-500">ID Transaksi</span>
                        <span class="font-mono font-medium text-gray-900">#<?=$key['id_tabungan_transaksi']?></span>
                    </div>
                    
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-gray-500">Jenis Transaksi</span>
                        <span class="font-medium text-gray-900 uppercase text-xs px-2 py-1 bg-gray-100 rounded">
                            <?php echo ($key['kredit'] > 0) ? 'Setor Sampah' : 'Tarik Saldo'; ?>
                        </span>
                    </div>
                </div>

                <!-- Divider -->
                <div class="w-full border-b-2 border-dashed border-gray-200 my-4"></div>

                <!-- Itemized Breakdown (Jenis Sampah) -->
                <?php if($sampah->num_rows() > 0): ?>
                <div class="my-4">
                    <div class="flex justify-between text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-2">
                        <span>Jenis Sampah</span>
                        <span>Harga</span>
                    </div>
                    
                    <?php foreach($sampah->result_array() as $some){ ?>
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-gray-800 font-medium"><?=$some['jenis_sampah'] ?></span>
                        <span class="text-gray-600 text-right">
                            <span class="text-xs mr-2 border-r border-gray-300 pr-2"><?=$some['berat_sampah']?> kg</span>
                            <span class="font-mono">Rp <?=number_format($some['harga_sampah'], 0, ',', '.')?></span>
                        </span>
                    </div>
                    <?php } ?>
                </div>
                <?php endif; ?>
                
                <!-- Bottom barcode placeholder -->
                <div class="mt-8 flex flex-col items-center opacity-40">
                    <svg class="w-48 h-12" viewBox="0 0 100 20" preserveAspectRatio="none">
                        <!-- Barcode pattern -->
                        <rect x="0" y="0" width="2" height="20" fill="currentColor"/>
                        <rect x="4" y="0" width="1" height="20" fill="currentColor"/>
                        <rect x="7" y="0" width="4" height="20" fill="currentColor"/>
                        <rect x="13" y="0" width="1" height="20" fill="currentColor"/>
                        <rect x="16" y="0" width="3" height="20" fill="currentColor"/>
                        <rect x="21" y="0" width="2" height="20" fill="currentColor"/>
                        <rect x="25" y="0" width="1" height="20" fill="currentColor"/>
                        <rect x="28" y="0" width="3" height="20" fill="currentColor"/>
                        <rect x="34" y="0" width="4" height="20" fill="currentColor"/>
                        <rect x="40" y="0" width="1" height="20" fill="currentColor"/>
                        <rect x="43" y="0" width="2" height="20" fill="currentColor"/>
                        <rect x="47" y="0" width="3" height="20" fill="currentColor"/>
                        <rect x="52" y="0" width="1" height="20" fill="currentColor"/>
                        <rect x="55" y="0" width="4" height="20" fill="currentColor"/>
                        <rect x="61" y="0" width="2" height="20" fill="currentColor"/>
                        <rect x="65" y="0" width="1" height="20" fill="currentColor"/>
                        <rect x="68" y="0" width="3" height="20" fill="currentColor"/>
                        <rect x="73" y="0" width="5" height="20" fill="currentColor"/>
                        <rect x="80" y="0" width="1" height="20" fill="currentColor"/>
                        <rect x="83" y="0" width="2" height="20" fill="currentColor"/>
                        <rect x="87" y="0" width="4" height="20" fill="currentColor"/>
                        <rect x="93" y="0" width="1" height="20" fill="currentColor"/>
                        <rect x="96" y="0" width="3" height="20" fill="currentColor"/>
                    </svg>
                    <span class="text-[10px] font-mono mt-1 tracking-[0.3em] uppercase text-gray-500"><?=$key['id_tabungan_transaksi']?>-OK</span>
                </div>

            </div>
            <?php } ?>
        </div>

    </div>
</body>
</html>