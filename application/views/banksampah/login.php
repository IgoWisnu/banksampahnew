<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Banksampah</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="shortcut icon" type="image/x-icon" href="<?= base_url() ?>img/logo white.png?v=1" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="manifest" href="<?= base_url('manifest.json') ?>">
    <meta name="theme-color" content="#00926E">
    <link rel="apple-touch-icon" href="<?= base_url('img/icon-192.png') ?>">

    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('<?= base_url('sw.js') ?>')
                    .then(reg => console.log('PWA Service Worker terdaftar!', reg))
                    .catch(err => console.error('PWA Service Worker gagal!', err));
            });
        }
    </script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            green: '#00926E',
                            dark: '#006c50',
                            yellow: '#f59e0b',
                            light: '#fef3c7'
                        }
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gradient-to-br from-brand-green via-teal-500 to-brand-yellow min-h-screen flex items-center justify-center p-4 font-sans antialiased text-gray-800">

    <div class="w-full max-w-4xl bg-white/95 backdrop-blur-sm rounded-[2rem] shadow-2xl overflow-hidden flex flex-col md:flex-row transform transition-all hover:scale-[1.01] duration-300">

        <div class="hidden md:block w-1/2 relative bg-brand-green">
            <img src="<?= base_url() ?>img/trash.jpeg" alt="Banksampah Background" class="absolute inset-0 w-full h-full object-cover mix-blend-overlay opacity-60">
            <div class="absolute inset-0 bg-gradient-to-t from-brand-dark/90 to-brand-green/20"></div>
            <div class="absolute inset-0 flex flex-col items-center justify-center p-8 text-center text-white">
                <div class="flex items-center justify-center gap-6 mb-4">
                    <img src="<?= base_url() ?>img/politeknik-negeri-bali-seeklogo.png?v=1" alt="Logo Poltek" class="h-20 w-auto object-contain drop-shadow-lg">
                    <img src="<?= base_url() ?>img/logos.png?v=1" alt="Logo Partner" class="h-20 w-auto object-contain drop-shadow-lg">
                </div>
                <img src="<?= base_url() ?>img/logo white.png?v=1" alt="Banksampah" class="w-150 mb-4 drop-shadow-lg">
                <h2 class="text-3xl font-bold tracking-wide drop-shadow-md">BANKSAMPAH</h2>
                <p class="text-green-50 mt-2 font-medium drop-shadow">Mari Bersama Menjaga Lingkungan</p>
            </div>
        </div>

        <div class="w-full md:w-1/2 p-8 sm:p-10 flex flex-col justify-center">
            <div class="text-center mb-8">
                <div class="flex items-center justify-center gap-6 mb-4">
                    <img src="<?= base_url() ?>img/politeknik-negeri-bali-seeklogo.png?v=1" alt="Logo Poltek" class="h-20 w-auto object-contain drop-shadow-lg">
                    <img src="<?= base_url() ?>img/logos.png?v=1" alt="Logo Partner" class="h-20 w-auto object-contain drop-shadow-lg">
                </div>
                <img src="<?= base_url() ?>img/logo white.png?v=1" alt="Logo Banksampah" class="h-16 mx-auto mb-4 drop-shadow-sm">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Selamat Datang!</h1>
                <p class="text-gray-500">Silahkan masukkan Username & Password kamu</p>
            </div>

            <?php
            $success = $this->session->flashdata('success');
            $failed = $this->session->flashdata('failed');
            if (isset($failed)) {
                echo '<div class="bg-red-50 text-red-600 p-4 rounded-xl text-sm mb-6 border border-red-200">' . $failed . '</div>';
            } else if (isset($success)) {
                echo '<div class="bg-green-50 text-green-600 p-4 rounded-xl text-sm mb-6 border border-green-200">' . $success . '</div>';
            }
            ?>

            <form action="<?= base_url('auth/cekLogin') ?>" method="post" class="space-y-6">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Username</label>
                    <input type="text" name="username" placeholder="Masukkan Username" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-brand-green focus:border-transparent transition-all duration-200 bg-gray-50 focus:bg-white">
                    <small class="text-red-500 text-xs mt-1 block"><?= form_error('username') ?></small>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                    <input type="password" name="password" placeholder="Masukkan Password" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-brand-green focus:border-transparent transition-all duration-200 bg-gray-50 focus:bg-white">
                    <small class="text-red-500 text-xs mt-1 block"><?= form_error('password') ?></small>
                </div>

                <div class="flex items-center justify-end">
                    <a href="<?= base_url('auth/resetpassword') ?>" class="text-sm font-medium text-brand-green hover:text-brand-dark transition-colors">Lupa Password?</a>
                </div>

                <div class="flex justify-center">
                    <div class="g-recaptcha" data-sitekey="<?= RECAPTCHA_SITE_KEY ?>"></div>
                </div>

                <button type="submit" class="w-full bg-brand-green hover:bg-brand-dark text-white font-bold py-3 px-4 rounded-xl transition-colors shadow-lg shadow-brand-green/30 active:scale-95 duration-200">
                    Login
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-gray-100">
                <div class="grid grid-cols-2 gap-4 text-center text-sm">
                    <div class="flex flex-col border-r border-gray-200 pr-4">
                        <span class="text-gray-500 mb-1">Belum Punya Akun?</span>
                        <a href="<?= base_url() ?>auth/goRegister" class="font-semibold text-brand-yellow hover:text-yellow-600 transition-colors">Daftar Sekarang</a>
                    </div>
                    <div class="flex flex-col pl-4">
                        <span class="text-gray-500 mb-1">Tidak ingin login?</span>
                        <a href="<?= base_url('auth/guestAccess') ?>" class="font-semibold text-brand-green hover:text-brand-dark transition-colors">Masuk Guest</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // Cek apakah ada flashdata 'success' (Misal: habis reset password)
            <?php if($this->session->flashdata('success')): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '<?= $this->session->flashdata('success'); ?>',
                    timer: 3000,
                    showConfirmButton: false
                });
            
            // Cek apakah ada flashdata 'failed' (Misal: Password salah)
            <?php elseif($this->session->flashdata('failed')): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops!',
                    text: '<?= $this->session->flashdata('failed'); ?>',
                    confirmButtonColor: '#00926E' // Warna hijau tema bank sampah
                });
            <?php endif; ?>
        });
    </script>
</body>

</html>