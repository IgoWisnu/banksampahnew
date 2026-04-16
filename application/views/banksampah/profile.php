<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Banksampah</title>
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

        <div class="relative z-10 pt-8 px-6 md:px-12 flex flex-col md:flex-row justify-center mt-4">
            <div class="w-full max-w-4xl md:w-8/12 lg:w-7/12 mt-8 z-20">
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
                <div class="bg-white/95 backdrop-blur-sm rounded-[2rem] shadow-2xl p-6 md:p-8 border border-gray-100 relative">
                    <?php if($this->session->userdata('role') == 'admin'): ?>
                        <a href="<?= base_url('dashboard/index') ?>" class="inline-flex items-center text-sm font-semibold text-gray-500 hover:text-brand-green transition-colors mb-6">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            Kembali
                        </a>
                    <?php else: ?>
                        <a href="home/loadArtikel" class="inline-flex items-center text-sm font-semibold text-gray-500 hover:text-brand-green transition-colors mb-6">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            Kembali
                        </a>
                    <?php endif; ?>

                    <?php foreach($profile->result_array() as $key){ ?>

                    <div class="flex flex-col md:flex-row items-center md:items-start gap-6 border-b border-gray-100 pb-8">
                        <div class="relative shrink-0">
                            <img src="<?=base_url()?>uploads/profile/<?=$key['profile']?>" alt="" class="w-24 h-24 md:w-32 md:h-32 rounded-2xl object-cover border-4 border-white shadow-lg bg-gray-100">
                        </div>
                        <div class="text-center md:text-left flex-1 w-full">
                            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 break-words"><?=$key['username'] ?></h2>
                            <p class="text-gray-500 font-medium text-sm md:text-base break-words mb-4"><?=$key['email'] ?></p>
                            
                            <!-- Tabungan Card Info inside profile -->
                            <div class="bg-gradient-to-br from-teal-500 to-brand-green rounded-2xl p-5 text-white shadow-lg shadow-brand-green/20 relative overflow-hidden">
                                <img src="<?=base_url()?>img/logo white.png" alt="" class="absolute right-0 bottom-0 top-0 h-full opacity-10 -mr-4 pointer-events-none object-contain">
                                <h3 class="text-lg font-bold mb-4 opacity-90">Detail Tabungan</h3>
                                <div class="space-y-2 relative z-10">
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="opacity-80">ID Tabungan</span>
                                        <span class="font-bold"><?=$key['id_tabungan']?></span>
                                    </div>
                                    <div class="flex justify-between items-center border-t border-white/20 pt-2 text-sm mt-2">
                                        <span class="opacity-80">Saldo</span>
                                        <span class="font-bold text-xl"><?=$key['saldo'] ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="mt-8 space-y-3">
                        <a href="<?=base_url()?>profile/editprofile?id=<?=$key['id_user']?>" class="flex items-center justify-between p-4 rounded-xl bg-gray-50 hover:bg-gray-100 border border-gray-100 transition-colors group">
                            <div class="flex items-center gap-4">
                                <div class="p-2 bg-white rounded-lg shadow-sm group-hover:text-brand-green text-gray-400 transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </div>
                                <span class="font-semibold text-gray-700">Edit Profile</span>
                            </div>
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                        
                        <a href="https://wa.me/6285866763327/" class="flex items-center justify-between p-4 rounded-xl bg-gray-50 hover:bg-gray-100 border border-gray-100 transition-colors group">
                            <div class="flex items-center gap-4">
                                <div class="p-2 bg-white rounded-lg shadow-sm group-hover:text-brand-green text-gray-400 transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                </div>
                                <span class="font-semibold text-gray-700">Kontak Kami</span>
                            </div>
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>

                        <button onclick="logoutModal()" class="w-full flex items-center justify-between p-4 rounded-xl bg-red-50 hover:bg-red-100 border border-red-100 transition-colors group">
                            <div class="flex items-center gap-4">
                                <div class="p-2 bg-white rounded-lg shadow-sm text-red-500 transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                </div>
                                <span class="font-semibold text-red-600">Keluar</span>
                            </div>
                        </button>
                    </div>

                    <?php } ?>
                </div>
            </div>
        </div>
        
        <?php 
        if ($this->session->userdata('role') != 'admin') {
            include('menu.php');
        }
        ?>
    </div>

    <!-- Tailwind Logout Modal Overlay -->
    <div id="logoutModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-full bg-gray-900/50 backdrop-blur-sm transition-opacity">
        <div class="relative p-4 w-full max-w-md max-h-full mx-auto mt-32">
            <div class="relative bg-white rounded-2xl shadow-xl dark:bg-gray-700">
                <button type="button" onclick="closeLogoutModal()" class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
                <div class="p-4 md:p-5 text-center">
                    <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                    <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Apakah anda yakin ingin logout?</h3>
                    <button onclick="logout()" type="button" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                        Ya, Logout
                    </button>
                    <button onclick="closeLogoutModal()" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-brand-green focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Batal</button>
                </div>
            </div>
        </div>
    </div>


    <script>
        function logout() {
            window.location.href = "<?php echo site_url('auth/logout'); ?>"
        }
        function logoutModal() {
            document.getElementById('logoutModal').classList.remove('hidden');
        }
        function closeLogoutModal() {
            document.getElementById('logoutModal').classList.add('hidden');
        }
    </script>
</body>
</html>