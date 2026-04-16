<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penarikan Saldo - Banksampah</title>
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
    <style>
        .shiny-card-yellow {
            background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 50%, #f59e0b 100%);
            background-size: 200% 200%;
            animation: gradientMove 3s ease infinite;
        }
        @keyframes gradientMove {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
    </style>
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

        <div class="relative z-10 pt-8 px-6 md:px-12 flex flex-col items-center mt-4 md:mt-12">
            <div class="w-full max-w-sm md:max-w-md mt-4 z-20">
                <!-- Session Flashdata Messages -->
                <?php 
                    $success = $this->session->flashdata('success');
                    $failed = $this->session->flashdata('failed');

                    if($success){
                    echo '<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-r" role="alert">'.$success.'</div>';
                    } elseif($failed){
                    echo '<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-r" role="alert">'.$failed.'</div>';
                    }
                ?>
                
                <div class="flex justify-between items-center w-full mb-6 text-white relative z-20">
                    <a href="<?=base_url()?>home/loadArtikel" class="inline-flex items-center text-sm font-semibold hover:text-brand-light transition-colors drop-shadow-md">
                        <svg class="w-6 h-6 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Kembali
                    </a>
                </div>

                <div class="group perspective-1000">
                    <div class="bg-white/95 backdrop-blur-sm rounded-[2rem] shadow-2xl overflow-hidden border border-gray-100 relative transition-transform duration-500 group-hover:-translate-y-2 group-hover:shadow-[0_20px_50px_rgba(245,158,11,0.3)]">
                        <?php foreach($profile->result_array() as $key){ ?>
                        
                        <div class="p-8 pb-6 flex flex-col items-center">
                            <div class="relative shrink-0 mb-4 z-10 group-hover:scale-105 transition-transform duration-300">
                                <img src="<?=base_url()?>uploads/profile/<?=$key['profile']?>" alt="" class="w-24 h-24 rounded-full object-cover border-4 border-brand-yellow shadow-lg bg-gray-100">
                                <div class="absolute inset-0 rounded-full border-4 border-brand-yellow animate-ping opacity-20"></div>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-900 text-center"><?=$key['username'] ?></h2>
                            <p class="text-gray-500 font-medium text-sm text-center mb-6"><?=$key['email'] ?></p>

                            <!-- The Digital ID Card -->
                            <div class="shiny-card-yellow w-full rounded-2xl p-6 text-white shadow-lg shadow-brand-yellow/30 relative overflow-hidden">
                                <img src="<?=base_url()?>img/logo white.png" alt="" class="absolute -right-6 -bottom-6 w-32 opacity-20 pointer-events-none object-contain transform -rotate-12">
                                
                                <div class="flex items-center justify-between mb-2 opacity-90">
                                    <h3 class="text-xs uppercase tracking-widest font-bold flex items-center text-yellow-900">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        Digital ID Member
                                    </h3>
                                </div>
                                <div class="space-y-4 relative z-10 mt-4">
                                    <div>
                                        <span class="block text-xs opacity-90 text-yellow-900 uppercase font-bold mb-1">ID Tabungan Kamu</span>
                                        <span class="font-mono text-2xl font-bold tracking-widest text-shadow-sm text-yellow-900"><?=$key['id_tabungan']?></span>
                                    </div>
                                    <div class="border-t border-yellow-600/30 pt-3">
                                        <span class="block text-xs opacity-90 text-yellow-900 uppercase font-bold mb-1">Saldo Saat Ini</span>
                                        <span class="font-bold text-xl text-yellow-900"><?=$key['saldo'] ?></span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Instruction text -->
                            <div class="mt-8 text-center bg-yellow-50 p-4 rounded-xl border border-yellow-200 w-full animate-pulse transition-all cursor-default">
                                <svg class="w-8 h-8 text-brand-yellow mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <p class="text-sm text-yellow-800 font-semibold leading-relaxed">
                                    Tunjukkan Kartu Ini ke Staf<br>Untuk Melakukan Penarikan
                                </p>
                                <p class="text-[10px] uppercase tracking-wide text-yellow-600 font-bold mt-2 pt-2 border-t border-yellow-200/50">
                                    Penarikan 6 bulan sekali (Tgl 06/06/2024)<br>Di Politeknik Negeri Bali
                                </p>
                            </div>
                        </div>

                        <?php } ?>
                    </div>
                </div>

            </div>
        </div>
        
    </div>
</body>
</html>