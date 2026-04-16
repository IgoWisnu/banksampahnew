<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jenis Sampah - Banksampah</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts -->
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
        <!-- Header Green Block -->
        <div class="absolute top-0 left-0 right-0 h-[220px] md:h-[300px] bg-brand-green shadow-md z-0 overflow-hidden">
            <img src="<?= base_url() ?>img/trash.jpeg"
                class="hidden md:block absolute right-0 top-0 w-2/3 h-full object-cover mix-blend-overlay opacity-20"
                alt="Background Graphic">
            <div class="hidden md:block absolute inset-0 bg-gradient-to-r from-brand-dark/60 via-brand-green/80 to-transparent"></div>
        </div>

        <div class="relative z-10 pt-8 px-6 md:px-12 flex flex-col justify-center items-center mt-4">
            <div class="w-full max-w-5xl mt-8 z-20">
                
                <div class="bg-white/95 backdrop-blur-sm rounded-[2rem] shadow-2xl p-6 md:p-8 border border-gray-100 relative">
                    <a href="<?=base_url()?>home/loadArtikel" class="inline-flex items-center text-sm font-semibold text-gray-500 hover:text-brand-green transition-colors mb-6">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Kembali
                    </a>

                    <div class="flex items-center gap-4 mb-6">
                        <div class="bg-brand-light text-brand-yellow p-3 rounded-xl">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16">
                              </path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">List Jenis Sampah</h2>
                            <p class="text-sm text-gray-500 mt-1">Daftar sampah yang kami terima</p>
                        </div>
                    </div>

                    <div class="overflow-x-auto rounded-xl border border-gray-200 shadow-sm">
                        <table class="w-full text-sm text-left text-gray-500 whitespace-nowrap">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th scope="col" class="px-6 py-4 font-semibold">No</th>
                                    <th scope="col" class="px-6 py-4 font-semibold">Jenis Sampah</th>
                                    <th scope="col" class="px-6 py-4 font-semibold">Kategori</th>
                                    <th scope="col" class="px-6 py-4 font-semibold">Sub Kategori</th>
                                    <th scope="col" class="px-6 py-4 font-semibold text-right">Harga/kg</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($sampah->result_array() as $key){ ?>
                                <tr class="bg-white border-b border-gray-100 hover:bg-gray-50/80 transition-colors">
                                    <td class="px-6 py-4 font-medium text-gray-900"><?=$key['id'] ?></td>
                                    <td class="px-6 py-4 text-gray-700 font-medium"><?=$key['jenis_sampah'] ?></td>
                                    <td class="px-6 py-4">
                                        <span class="bg-green-50 text-brand-green text-xs font-medium px-2.5 py-1 rounded-full border border-green-200"><?=$key['kategori_sampah'] ?></span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-600"><?=$key['sub_kategori_sampah'] ?></td>
                                    <td class="px-6 py-4 text-right font-bold text-brand-green">Rp <?= number_format($key['harga_sampah'], 0, ',', '.') ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        <?php if($sampah->num_rows() == 0): ?>
                        <div class="text-center py-8 text-gray-500 text-sm">Belum ada jenis sampah yang tersedia.</div>
                        <?php endif; ?>
                    </div>

                    <!-- Pagination Links -->
                    <?php if(isset($pagination)) echo $pagination; ?>
                </div>

            </div>
        </div>
        
        <?php include('menu.php'); ?>
    </div>

</body>
</html>