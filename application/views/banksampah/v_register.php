<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SignUp - Banksampah</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="shortcut icon" type="image/x-icon" href="<?= base_url() ?>img/logo white.png" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
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

<body
    class="bg-gradient-to-br from-brand-green via-teal-500 to-brand-yellow min-h-screen flex items-center justify-center p-4 font-sans antialiased text-gray-800">

    <div
        class="w-full max-w-5xl bg-white/95 backdrop-blur-sm rounded-[2rem] shadow-2xl overflow-hidden flex flex-col md:flex-row transform transition-all hover:scale-[1.01] duration-300">

        <!-- Left Side Image Background for Desktop -->
        <div class="hidden md:block w-5/12 relative bg-brand-green">
            <img src="<?= base_url() ?>img/trash.jpeg" alt="Banksampah Background"
                class="absolute inset-0 w-full h-full object-cover mix-blend-overlay opacity-60">
            <div class="absolute inset-0 bg-gradient-to-t from-brand-dark/90 to-brand-green/20"></div>
            <div class="absolute inset-0 flex flex-col items-center justify-center p-8 text-center text-white">
                <img src="<?= base_url() ?>img/logo white.png" alt="Banksampah" class="w-24 mb-4 drop-shadow-lg">
                <h2 class="text-3xl font-bold tracking-wide drop-shadow-md">BANKSAMPAH</h2>
                <p class="text-green-50 mt-2 font-medium drop-shadow leading-relaxed">Bergabunglah bersama kami<br>untuk
                    bumi yang lebih hijau.</p>
            </div>
        </div>

        <!-- Right Side Register Form -->
        <div class="w-full md:w-7/12 p-8 sm:p-10 flex flex-col justify-center max-h-[90vh] overflow-hidden">
            <div class="text-center mb-6 flex-shrink-0">
                <img src="<?= base_url() ?>img/logo green.png" alt="Logo Banksampah"
                    class="h-12 mx-auto mb-3 drop-shadow-sm md:hidden">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Salam Kenal</h1>
                <p class="text-gray-500 text-sm">Silahkan lengkapi data diri di bawah ini</p>
            </div>

            <!-- Scrollable form container -->
            <div class="overflow-y-auto overflow-x-hidden pr-2 custom-scrollbar flex-grow">
                <form action="<?= base_url('auth/mail') ?>" method="post" class="space-y-4 pb-4 px-1">

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Username</label>
                        <input type="text" name="username" placeholder="Masukkan Username"
                            value="<?= set_value('username') ?>"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-brand-green bg-gray-50 focus:bg-white transition-colors">
                        <small class="text-red-500 text-xs mt-1 block"><?= form_error('username') ?></small>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" placeholder="example@email.com"
                            value="<?= set_value('email') ?>"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-brand-green bg-gray-50 focus:bg-white transition-colors">
                        <small class="text-red-500 text-xs mt-1 block"><?= form_error('email') ?></small>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" placeholder="Nama Lengkap"
                                value="<?= set_value('nama_lengkap') ?>"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-brand-green bg-gray-50 focus:bg-white transition-colors">
                            <small class="text-red-500 text-xs mt-1 block"><?= form_error('nama_lengkap') ?></small>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">No Telp</label>
                            <input type="text" name="notelp" placeholder="08xxxxxxxxxx"
                                value="<?= set_value('notelp') ?>"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-brand-green bg-gray-50 focus:bg-white transition-colors">
                            <small class="text-red-500 text-xs mt-1 block"><?= form_error('notelp') ?></small>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" placeholder="Kota Kelahiran"
                                value="<?= set_value('tempat_lahir') ?>"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-brand-green bg-gray-50 focus:bg-white transition-colors">
                            <small class="text-red-500 text-xs mt-1 block"><?= form_error('tempat_lahir') ?></small>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" value="<?= set_value('tanggal_lahir') ?>"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-brand-green bg-gray-50 focus:bg-white transition-colors">
                            <small class="text-red-500 text-xs mt-1 block"><?= form_error('tanggal_lahir') ?></small>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Alamat Lengkap</label>
                        <input type="text" name="alamat" placeholder="Alamat rumah / domisili"
                            value="<?= set_value('alamat') ?>"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-brand-green bg-gray-50 focus:bg-white transition-colors">
                        <small class="text-red-500 text-xs mt-1 block"><?= form_error('alamat') ?></small>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Pilih Banjar</label>
                        <select name="banjar_id" required
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-brand-green bg-gray-50 focus:bg-white transition-colors">
                            <option value="">-- Pilih Banjar --</option>
                            <?php if (isset($banjars)):
                                foreach ($banjars as $b): ?>
                                    <option value="<?= $b->id ?>" <?= set_select('banjar_id', $b->id) ?>><?= $b->nama ?></option>
                                <?php endforeach; endif; ?>
                        </select>
                        <small class="text-red-500 text-xs mt-1 block"><?= form_error('banjar_id') ?></small>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                            <input type="password" name="password" placeholder="Buat Password"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-brand-green bg-gray-50 focus:bg-white transition-colors">
                            <small class="text-red-500 text-xs mt-1 block"><?= form_error('password') ?></small>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Ulangi Password</label>
                            <input type="password" name="verify_password" placeholder="Ulangi Password"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-brand-green bg-gray-50 focus:bg-white transition-colors">
                            <small class="text-red-500 text-xs mt-1 block"><?= form_error('verify_password') ?></small>
                        </div>
                    </div>

                    <div class="pt-2 pb-2">
                        <label class="flex items-start space-x-3 cursor-pointer">
                            <input type="checkbox" id="agreementCheck" name="agreementCheck"
                                class="mt-1 w-4 h-4 text-brand-green rounded border-gray-300 focus:ring-brand-green">
                            <span class="text-sm text-gray-600">Saya menyetujui semua ketentuan dan kebijakan layanan
                                Bank Sampah yang berlaku.</span>
                        </label>
                    </div>

                    <button type="submit"
                        class="w-full bg-brand-green hover:bg-brand-dark text-white font-bold py-3.5 px-4 rounded-xl transition-colors shadow-lg shadow-brand-green/30 active:scale-95 duration-200 mt-2">
                        Daftar Akun Sekarang
                    </button>

                    <div class="text-center mt-6 pt-4 border-t border-gray-100">
                        <span class="text-gray-500 text-sm">Sudah punya akun? </span>
                        <a href="<?= base_url('auth') ?>"
                            class="text-sm font-semibold text-brand-green hover:text-brand-dark transition-colors">Login
                            disini</a>
                    </div>
                </form>
            </div>

            <style>
                .custom-scrollbar::-webkit-scrollbar {
                    width: 6px;
                }

                .custom-scrollbar::-webkit-scrollbar-track {
                    background: transparent;
                }

                .custom-scrollbar::-webkit-scrollbar-thumb {
                    background-color: #cbd5e1;
                    border-radius: 20px;
                }

                .custom-scrollbar::-webkit-scrollbar-thumb:hover {
                    background-color: #94a3b8;
                }
            </style>
        </div>
    </div>
<script>
        // Mengunci tombol daftar sampai checkbox dicentang
        document.addEventListener('DOMContentLoaded', function() {
            const checkbox = document.getElementById('agreementCheck');
            const submitBtn = document.getElementById('submitBtn');

            // Tambahkan ID 'submitBtn' ke tombol submitmu
            // Cari tag <button type="submit"... lalu tambahkan id="submitBtn" di dalamnya.
            
            // Set disable di awal
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');

            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                } else {
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                }
            });
        });
    </script>
</body>

</html>