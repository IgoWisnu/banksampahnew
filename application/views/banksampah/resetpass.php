<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - Banksampah</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: { extend: { fontFamily: { sans: ['Inter', 'sans-serif'] }, colors: { brand: { green: '#00926E', dark: '#006c50' } } } }
        }
    </script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4 font-sans antialiased">
    <div class="w-full max-w-md bg-white rounded-3xl shadow-xl p-8 border border-gray-100">
        <div class="text-center mb-8">
            <img src="<?= base_url() ?>img/logo green.png" alt="Logo Banksampah" class="h-16 mx-auto mb-4">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Lupa Password?</h1>
            <p class="text-gray-500 text-sm">Masukkan email yang terdaftar, kami akan mengirimkan tautan untuk mereset password Anda menjadi default.</p>
        </div>
        
        <?php if($this->session->flashdata('failed')): ?>
            <div class="bg-red-50 text-red-600 p-4 rounded-xl text-sm mb-6 border border-red-200 text-center">
                <?= $this->session->flashdata('failed') ?>
            </div>
        <?php elseif($this->session->flashdata('success')): ?>
            <div class="bg-green-50 text-green-600 p-4 rounded-xl text-sm mb-6 border border-green-200 text-center">
                <?= $this->session->flashdata('success') ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('auth/check_email') ?>" method="post" class="space-y-6">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Email</label>
                <input type="email" name="email" placeholder="contoh@email.com" required
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-brand-green focus:border-transparent transition-all duration-200 bg-gray-50 focus:bg-white">
            </div>
            <button type="submit"
                class="w-full bg-brand-green hover:bg-brand-dark text-white font-bold py-3 px-4 rounded-xl transition-colors shadow-lg shadow-brand-green/30 active:scale-95 duration-200">
                Kirim Link Reset
            </button>
        </form>

        <div class="mt-8 text-center">
            <a href="<?= base_url('auth') ?>" class="text-sm font-medium text-gray-500 hover:text-brand-green transition-colors flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Login
            </a>
        </div>
    </div>
</body>
</html>