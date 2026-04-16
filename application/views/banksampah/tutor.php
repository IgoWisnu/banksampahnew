<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cara Menggunakan - Banksampah</title>
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
            <div
                class="hidden md:block absolute inset-0 bg-gradient-to-r from-brand-dark/60 via-brand-green/80 to-transparent">
            </div>
        </div>

        <div class="relative z-10 pt-8 px-6 md:px-12 flex flex-col md:flex-row justify-center mt-4">
            <div class="w-full max-w-7xl md:w-8/12 lg:w-10/12 mt-8 z-20">

                <div
                    class="bg-white/95 backdrop-blur-sm rounded-[2rem] shadow-2xl p-6 md:p-10 border border-gray-100 relative">
                    <a href="<?= base_url() ?>home/loadArtikel"
                        class="inline-flex items-center text-sm font-semibold text-gray-500 hover:text-brand-green transition-colors mb-8">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali
                    </a>

                    <div class="flex items-center gap-4 mb-8">
                        <div class="bg-green-100 text-brand-green p-3 rounded-xl">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                        </div>
                        <h2 class="text-2xl md:text-3xl font-bold text-gray-900">Cara Menggunakan</h2>
                    </div>

                    <div
                        class="space-y-6 md:space-y-8 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-gray-200 before:to-transparent">

                        <!-- Step 1 -->
                        <div
                            class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                            <!-- Icon -->
                            <div
                                class="flex items-center justify-center w-10 h-10 rounded-full border-4 border-white bg-brand-green text-white shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 ml-0 z-10 font-bold text-sm">
                                1
                            </div>
                            <!-- Content -->
                            <div
                                class="w-[calc(100%-4rem)] md:w-[calc(50%-2.5rem)] p-4 rounded-2xl bg-gray-50 border border-gray-100 shadow-sm ml-4 md:ml-0 hover:shadow-md transition-shadow">
                                <h3 class="font-bold text-gray-900 text-lg mb-1">Register</h3>
                                <p class="text-sm text-gray-600 leading-relaxed">Daftarkan akun untuk mulai menggunakan
                                    aplikasi Bank Sampah.</p>
                            </div>
                        </div>

                        <!-- Step 2 -->
                        <div
                            class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                            <!-- Icon -->
                            <div
                                class="flex items-center justify-center w-10 h-10 rounded-full border-4 border-white bg-brand-green text-white shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 ml-0 z-10 font-bold text-sm">
                                2
                            </div>
                            <!-- Content -->
                            <div
                                class="w-[calc(100%-4rem)] md:w-[calc(50%-2.5rem)] p-4 rounded-2xl bg-gray-50 border border-gray-100 shadow-sm ml-4 md:ml-0 hover:shadow-md transition-shadow">
                                <h3 class="font-bold text-gray-900 text-lg mb-1">Login</h3>
                                <p class="text-sm text-gray-600 leading-relaxed">Masuk ke akun yang sudah ada atau yang
                                    telah dibuat sebelumnya.</p>
                            </div>
                        </div>

                        <!-- Step 3 -->
                        <div
                            class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                            <!-- Icon -->
                            <div
                                class="flex items-center justify-center w-10 h-10 rounded-full border-4 border-white bg-brand-yellow text-white shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 ml-0 z-10 font-bold text-sm">
                                3
                            </div>
                            <!-- Content -->
                            <div
                                class="w-[calc(100%-4rem)] md:w-[calc(50%-2.5rem)] p-4 rounded-2xl bg-gray-50 border border-gray-100 shadow-sm ml-4 md:ml-0 hover:shadow-md transition-shadow">
                                <h3 class="font-bold text-gray-900 text-lg mb-2">Setor Sampah</h3>
                                <ul class="text-sm text-gray-600 leading-relaxed space-y-2 list-disc list-outside ml-4">
                                    <li>Setorkan sampah pada staf bank sampah pada lokasi.</li>
                                    <li>Admin akan menghitung jumlah sampah yang kamu berikan. Tunjukkan
                                        username/ID/email kamu kepada staf bank sampah.</li>
                                    <li>Pastikan saldo di akun kamu sudah bertambah.</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Step 4 -->
                        <div
                            class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                            <!-- Icon -->
                            <div
                                class="flex items-center justify-center w-10 h-10 rounded-full border-4 border-white bg-teal-500 text-white shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 ml-0 z-10 font-bold text-sm">
                                4
                            </div>
                            <!-- Content -->
                            <div
                                class="w-[calc(100%-4rem)] md:w-[calc(50%-2.5rem)] p-4 rounded-2xl bg-gray-50 border border-gray-100 shadow-sm ml-4 md:ml-0 hover:shadow-md transition-shadow">
                                <h3 class="font-bold text-gray-900 text-lg mb-1">Kumpulkan Poin</h3>
                                <p class="text-sm text-gray-600 leading-relaxed">Kumpulkan terus sampahmu, simpan
                                    saldonya, dan tukarkan menjadi uang tunai.</p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
</body>

</html>