<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Ditolak - Banksampah</title>
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
        class="w-full max-w-md bg-white/95 backdrop-blur-sm rounded-[2rem] shadow-2xl overflow-hidden transform transition-all hover:scale-[1.01] duration-300 text-center p-8 sm:p-10">

        <div class="mb-8">
            <div
                class="w-20 h-20 bg-brand-light rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
                <svg class="w-10 h-10 text-brand-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                    </path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-3 tracking-tight">BANKSAMPAH</h1>
            <p class="text-gray-500 leading-relaxed mx-auto">
                Maaf, kamu harus login untuk dapat mengakses halaman ini.
            </p>
        </div>

        <div class="space-y-4">
            <a href="<?= base_url() ?>auth/goRegister"
                class="block w-full bg-brand-yellow hover:bg-yellow-500 text-white font-bold py-3 px-4 rounded-xl transition-colors shadow-lg shadow-brand-yellow/30 active:scale-95 duration-200">
                Register Sekarang
            </a>

            <div class="relative flex items-center py-2">
                <div class="flex-grow border-t border-gray-100"></div>
                <span class="flex-shrink-0 mx-4 text-gray-400 text-sm">atau</span>
                <div class="flex-grow border-t border-gray-100"></div>
            </div>

            <a href="<?= base_url() ?>auth/logout"
                class="block w-full bg-brand-green hover:bg-brand-dark text-white font-bold py-3 px-4 rounded-xl transition-colors shadow-lg shadow-brand-green/30 active:scale-95 duration-200">
                Login Akun Banksampah
            </a>
        </div>

    </div>

</body>

</html>