<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jenis Sampah - Banksampah</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script>
    tailwind.config = {
        theme: {
        extend: {
            fontFamily: { sans: ['Inter', 'sans-serif'] },
            colors: { brand: { green: '#00926E', dark: '#006c50', yellow: '#f59e0b', light: '#fef3c7' } }
        }
        }
    }
    </script>
</head>
<body class="bg-gray-100 font-sans antialiased text-gray-800">

    <div class="w-full mx-auto bg-gray-50 min-h-screen relative shadow-none overflow-x-hidden pb-24 md:pb-32">
        <div class="absolute top-0 left-0 right-0 h-[220px] md:h-[300px] bg-brand-green shadow-md z-0 overflow-hidden">
            <img src="<?= base_url() ?>img/trash.jpeg" class="hidden md:block absolute right-0 top-0 w-2/3 h-full object-cover mix-blend-overlay opacity-20" alt="Background">
            <div class="hidden md:block absolute inset-0 bg-gradient-to-r from-brand-dark/60 via-brand-green/80 to-transparent"></div>
        </div>

        <div class="relative z-10 pt-8 px-6 md:px-12 flex flex-col justify-center items-center mt-4">
            <div class="w-full max-w-6xl mt-8 z-20">
                
                <div class="bg-white/95 backdrop-blur-sm rounded-[2rem] shadow-2xl p-6 md:p-8 border border-gray-100 relative">
                    <a href="<?=base_url()?>home/loadArtikel" class="inline-flex items-center text-sm font-semibold text-gray-500 hover:text-brand-green transition-colors mb-6">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Kembali Ke Beranda
                    </a>

                    <div class="flex items-center gap-4 mb-6">
                        <div class="bg-brand-light text-brand-yellow p-3 rounded-xl shadow-sm">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                        </div>
                        <div>
                            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 tracking-tight">Katalog & Pergerakan Harga</h2>
                            <p class="text-sm md:text-base text-gray-500 mt-1">Pantau harga pasar terkini secara transparan.</p>
                        </div>
                    </div>

                    <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 mb-6 flex items-start gap-3">
                        <svg class="w-6 h-6 text-blue-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <div>
                            <h4 class="text-sm font-bold text-blue-900">Transparansi Harga Dinamis</h4>
                            <p class="text-xs md:text-sm text-blue-700 mt-1">
                                Jika terjadi perubahan harga, <strong>nilai saldo tabungan Anda akan otomatis menyesuaikan</strong> dengan harga terbaru dari sampah yang Anda simpan!
                            </p>
                        </div>
                    </div>

                    <div class="hidden md:block overflow-x-auto rounded-xl border border-gray-200 shadow-sm">
                        <table class="w-full text-sm text-left text-gray-500 whitespace-nowrap">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-5 py-4 font-semibold">Jenis Sampah</th>
                                    <th class="px-5 py-4 font-semibold text-right">Harga Lama</th>
                                    <th class="px-2 py-4 font-semibold text-center"></th>
                                    <th class="px-5 py-4 font-semibold text-right">Harga Baru</th>
                                    <th class="px-5 py-4 font-semibold text-right">Selisih</th>
                                    <th class="px-5 py-4 font-semibold">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($sampah as $key){ 
                                    $selisih = $key['harga_sampah'] - $key['harga_lama'];
                                    $selisih_class = $selisih > 0 ? 'text-green-600' : ($selisih < 0 ? 'text-red-600' : 'text-gray-400');
                                    $selisih_icon = $selisih > 0 ? '↑' : ($selisih < 0 ? '↓' : '=');
                                ?>
                                <tr class="bg-white border-b border-gray-100 hover:bg-gray-50/80 transition-colors">
                                    <td class="px-5 py-4">
                                        <div class="font-bold text-gray-900"><?=$key['jenis_sampah'] ?></div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            <?=$key['kategori_sampah'] ?> &bull; <?=$key['sub_kategori_sampah'] ?>
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 text-right text-gray-400 line-through">
                                        Rp <?= number_format($key['harga_lama'], 0, ',', '.') ?>
                                    </td>
                                    <td class="px-2 py-4 text-center text-gray-300">→</td>
                                    <td class="px-5 py-4 text-right">
                                        <div class="font-extrabold text-brand-green text-base">Rp <?= number_format($key['harga_sampah'], 0, ',', '.') ?></div>
                                    </td>
                                    <td class="px-5 py-4 text-right font-medium <?=$selisih_class?>">
                                        <?=$selisih_icon?> Rp <?= number_format(abs($selisih), 0, ',', '.') ?>
                                    </td>
                                    <td class="px-5 py-4 text-xs text-gray-500 max-w-[150px] truncate" title="<?=$key['keterangan']?>">
                                        <?=$key['keterangan'] ?>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="grid grid-cols-1 gap-4 md:hidden">
                        <?php foreach($sampah as $key){ 
                            $selisih = $key['harga_sampah'] - $key['harga_lama'];
                            $selisih_class = $selisih > 0 ? 'text-green-600 bg-green-50 border-green-100' : ($selisih < 0 ? 'text-red-600 bg-red-50 border-red-100' : 'text-gray-500 bg-gray-50 border-gray-100');
                            $selisih_icon = $selisih > 0 ? '↑ Naik' : ($selisih < 0 ? '↓ Turun' : '= Tetap');
                        ?>
                        <div class="bg-white border border-gray-200 rounded-2xl p-4 shadow-sm hover:shadow-md transition-all">
                            <div class="flex justify-between items-start mb-2">
                                <div class="pr-3">
                                    <h3 class="font-bold text-gray-900 text-base leading-tight"><?=$key['jenis_sampah'] ?></h3>
                                    <p class="text-[11px] text-gray-500 mt-1"><?=$key['kategori_sampah'] ?> &bull; <?=$key['sub_kategori_sampah'] ?></p>
                                </div>
                                <span class="text-[10px] font-bold px-2 py-1 rounded-md border shrink-0 <?=$selisih_class?>">
                                    <?=$selisih_icon?> Rp <?= number_format(abs($selisih), 0, ',', '.') ?>
                                </span>
                            </div>
                            
                            <div class="mt-3 pt-3 border-t border-gray-100 grid grid-cols-2 gap-2 items-center">
                                <div>
                                    <span class="block text-[10px] font-medium text-gray-400 mb-0.5">Harga Lama</span>
                                    <span class="text-sm font-semibold text-gray-400 line-through">Rp <?= number_format($key['harga_lama'], 0, ',', '.') ?></span>
                                </div>
                                <div class="text-right">
                                    <span class="block text-[10px] font-medium text-gray-400 mb-0.5">Harga Baru</span>
                                    <span class="text-lg font-black text-brand-green">Rp <?= number_format($key['harga_sampah'], 0, ',', '.') ?></span>
                                </div>
                            </div>
                            <?php if($key['keterangan'] != '-' && $key['keterangan'] != 'Harga awal'): ?>
                                <div class="mt-2 text-[10px] text-gray-500 italic text-center bg-gray-50 p-1 rounded">
                                    "<?=$key['keterangan']?>"
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php } ?>
                    </div>

                    <?php if(empty($sampah)): ?>
                        <div class="text-center py-10 text-gray-500">
                            <p class="text-sm font-medium">Belum ada jenis sampah yang tersedia.</p>
                        </div>
                    <?php endif; ?>

                    <div class="mt-6 flex justify-center">
                        <?php if(isset($pagination)) echo $pagination; ?>
                    </div>

                </div>
            </div>
        </div>
        
        <?php include('menu.php'); ?>
    </div>
</body>
</html>