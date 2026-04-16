<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berita - Banksampah</title>
    <!-- Tailwind CSS with Typography Plugin for better article formatting -->
    <script src="https://cdn.tailwindcss.com?plugins=typography"></script>
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

    <div
        class="w-full mx-auto bg-gray-50 min-h-screen relative shadow-none overflow-x-hidden pb-24 md:pb-32 flex flex-col items-center">
        <!-- Header Green Block -->
        <div class="absolute top-0 left-0 right-0 h-[260px] md:h-[340px] bg-brand-green shadow-md z-0 overflow-hidden">
            <img src="<?= base_url() ?>img/trash.jpeg"
                class="hidden md:block absolute right-0 top-0 w-2/3 h-full object-cover mix-blend-overlay opacity-20"
                alt="Background Graphic">
            <div class="absolute inset-0 bg-gradient-to-t from-brand-dark/90 via-brand-green/80 to-transparent"></div>
        </div>

        <div class="relative z-10 w-full max-w-7xl px-4 mt-8 md:mt-16 mb-12">

            <!-- Navigation -->
            <div class="mb-6">
                <a href="<?= base_url() ?>home/loadArtikel"
                    class="inline-flex items-center text-sm font-semibold text-white/90 hover:text-white transition-colors drop-shadow-md">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke Beranda
                </a>
            </div>

            <!-- News Container -->
            <article
                class="bg-white/95 backdrop-blur-sm rounded-[2rem] shadow-2xl overflow-hidden border border-gray-100 relative">

                <!-- Hero Image -->
                <?php if (!empty($artikel['gambar'])): ?>
                    <div class="w-full h-64 md:h-[400px] relative overflow-hidden group">
                        <img src="<?= base_url() ?>uploads/<?= $artikel['gambar'] ?>" alt="News Header Image"
                            class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-gray-900/80 via-transparent to-transparent">
                        </div>

                        <div class="absolute bottom-6 left-6 right-6">
                            <span
                                class="inline-block bg-brand-green text-white text-[10px] font-bold uppercase tracking-widest px-3 py-1.5 rounded-full shadow-md mb-3">
                                Artikel Bank Sampah
                            </span>
                            <!-- Title inside image overlay for wide screens -->
                            <h1 class="text-2xl md:text-4xl font-bold text-white leading-tight text-shadow-sm">
                                <?= $artikel['judul'] ?>
                            </h1>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="p-6 md:px-10 md:pt-10 md:pb-0">
                        <span
                            class="inline-block bg-brand-green text-white text-[10px] font-bold uppercase tracking-widest px-3 py-1.5 rounded-full shadow-md mb-4">
                            Artikel Bank Sampah
                        </span>
                        <h1 class="text-2xl md:text-4xl font-bold text-gray-900 leading-tight">
                            <?= $artikel['judul'] ?>
                        </h1>
                    </div>
                <?php endif; ?>

                <div class="p-6 md:p-10">

                    <!-- Divider (Optional styling touch) -->
                    <div class="hidden md:block w-16 h-1 mt-4 mb-8 bg-brand-yellow rounded-full"></div>

                    <!-- Content (deskripsi) -->
                    <!-- Used Tailwind Typography plugin (.prose) to elegantly format user HTML content -->
                    <div
                        class="prose prose-lg prose-green max-w-none text-gray-700 leading-relaxed md:text-justify marker:text-brand-green prose-img:rounded-xl prose-a:text-brand-green hover:prose-a:text-brand-dark">
                        <?= $artikel['deskripsi'] ?>
                    </div>

                    <!-- Footer of the article -->
                    <div class="mt-12 pt-6 border-t border-gray-100 flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div
                                class="w-10 h-10 rounded-full bg-brand-green text-white flex items-center justify-center font-bold">
                                B
                            </div>
                            <div class="text-sm">
                                <p class="font-semibold text-gray-900">Admin Banksampah</p>
                                <p class="text-gray-500 text-xs">Penulis Artikel</p>
                            </div>
                        </div>

                        <div class="flex space-x-2">
                            <!-- Dummy Share buttons for aesthetics -->
                            <button
                                class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 hover:text-brand-green transition-colors">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z" />
                                </svg>
                            </button>
                            <button
                                class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 hover:text-brand-green transition-colors">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

            </article>
        </div>

        <?php include('menu.php'); ?>
    </div>

</body>

</html>