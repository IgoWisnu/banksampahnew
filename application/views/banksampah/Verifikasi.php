<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email - Banksampah</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: { brand: { green: '#00926E', dark: '#006c50' } }
                }
            }
        }
    </script>
</head>
<body class="bg-gradient-to-br from-brand-green via-teal-500 to-brand-green min-h-screen flex items-center justify-center p-4 font-sans antialiased">
    
    <div class="w-full max-w-md bg-white rounded-3xl shadow-2xl p-8 text-center relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-32 bg-brand-green/10 rounded-t-3xl"></div>
        
        <div class="relative z-10">
            <div class="w-24 h-24 bg-brand-green/20 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
                <svg class="w-12 h-12 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
            </div>
            
            <h1 class="text-3xl font-bold text-gray-900 mb-3">Cek Email Anda</h1>
            <p class="text-gray-500 mb-8 leading-relaxed">
                Kami telah mengirimkan tautan verifikasi akun ke alamat email Anda. Silakan periksa kotak masuk atau folder spam Anda.
            </p>
            
            <div class="space-y-4">
                <a href="https://mail.google.com" target="_blank" class="block w-full bg-brand-green hover:bg-brand-dark text-white font-bold py-3.5 px-4 rounded-xl transition-colors shadow-lg shadow-brand-green/30">
                    Buka Gmail
                </a>
                
                <a href="<?= base_url('auth') ?>" class="block w-full bg-gray-50 hover:bg-gray-100 text-gray-700 font-semibold py-3.5 px-4 rounded-xl transition-colors border border-gray-200">
                    Kembali ke Login
                </a>
            </div>
            
            <div class="mt-8 pt-6 border-t border-gray-100 text-sm text-gray-500">
                Terdapat masalah? <a href="https://wa.me/6285866763327" class="text-brand-green font-semibold hover:underline">Laporkan kepada kami</a>
            </div>
        </div>
    </div>

</body>
</html>