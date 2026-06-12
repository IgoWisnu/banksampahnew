<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Beranda - Banksampah</title>
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

  <!-- Main Full-Screen Responsive Container -->
  <div class="w-full mx-auto bg-gray-50 min-h-screen relative shadow-none overflow-x-hidden pb-24 md:pb-32">

    <!-- Combined Header & Floating Card -->
    <div class="relative mx-auto">

      <!-- Green Background Block -->
      <div class="absolute top-0 left-0 right-0 h-[280px] md:h-[400px] bg-brand-green  shadow-md z-0 overflow-hidden">
        <!-- Desktop Image Graphic -->
        <img src="<?= base_url() ?>img/trash.jpeg"
          class="hidden md:block absolute right-0 top-0 w-2/3 h-full object-cover mix-blend-overlay opacity-20"
          alt="Background Graphic">
        <div
          class="hidden md:block absolute inset-0 bg-gradient-to-r from-brand-dark/60 via-brand-green/80 to-transparent">
        </div>
      </div>

      <!-- Content Container -->
      <div class="relative z-10 pt-8 px-6 md:px-12 flex flex-col md:flex-row md:items-center">

        <!-- Left Side / Header Text -->
        <div class="w-full md:w-7/12 lg:w-2/3 md:pr-8 mb-6 md:mb-0 md:py-20 lg:py-24">
          <div class="flex justify-between items-center mb-2 md:mb-6">
            <div
              class="w-12 h-12 bg-white rounded-full flex md:hidden items-center justify-center p-2 shadow-lg shadow-black/10">
              <img src="<?= base_url() ?>img/logo green.png" alt="Logo">
            </div>
            <div>
              <p class="text-green-100 md:text-xl text-sm font-medium drop-shadow-sm">Selamat Datang,</p>
              <h2
                class="text-white text-2xl md:text-5xl lg:text-6xl font-bold truncate max-w-[300px] md:max-w-full drop-shadow-md tracking-tight">
                <?= $username ?>
              </h2>
            </div>
          </div>

          <!-- Desktop Subtitle / Logo -->
          <div class="hidden md:flex items-center gap-6 mt-6">
            <div
              class="w-20 bg-white rounded-full flex items-center justify-center p-3 shadow-lg shadow-black/20 shrink-0">
              <img src="<?= base_url() ?>img/logo green.png" alt="Logo" class="w-full h-full object-contain">
            </div>
            <p class="text-green-50 text-base lg:text-lg font-medium max-w-md leading-relaxed drop-shadow-sm">
              Bersama wujudkan bumi yang lebih hijau. Kumpulkan sampah, setor, dan nikmati hasilnya sekarang juga.
            </p>
          </div>
        </div>

        <!-- Right Side / Floating Balance Card -->
        <!-- On mobile: pushes down with margin. On desktop: takes remaining width -->
        <div class="w-full md:w-5/12 lg:w-1/3 mt-8 md:mt-0 relative md:-mr-4 lg:mr-0 z-20">
          <div
            class="bg-white rounded-3xl shadow-2xl shadow-gray-300/60 p-6 md:p-8 border border-gray-100 backdrop-blur-sm bg-white/95">

            <div class="flex flex-col mb-3 border-b border-gray-100 pb-6 gap-2 text-center text-left">
              <p class="text-gray-500 text-sm md:text-base font-medium">Total Saldo Kamu</p>
              <div class="flex items-center justify-start">
                <span class="text-brand-yellow bg-yellow-50 rounded-full p-2 mr-3 shrink-0">
                  <svg class="w-6 h-6 md:w-8 md:h-8" fill="currentColor" viewBox="0 0 20 20">
                    <path
                      d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z" />
                    <path fill-rule="evenodd"
                      d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.311c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.311c-.563-.649-1.413-1.076-2.354-1.253V5z"
                      clip-rule="evenodd" />
                  </svg>
                </span>
                <span
                  class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-gray-900 tracking-tight"><?= $saldo ?></span>
              </div>
              <p class="text-[11px] md:text-sm text-gray-400 font-medium mt-2">Tukarkan Sampah Menjadi Uang</p>
            </div>

            <div class="grid grid-cols-2 gap-3 md:gap-4">
              <a href="<?= base_url() ?>home/loadSetor"
                class="group flex flex-col items-center justify-center bg-blue-50/50 hover:bg-blue-100 text-blue-600 rounded-2xl py-4 transition-all active:scale-95 duration-200 border border-blue-100">
                <svg class="w-7 h-7 md:w-8 md:h-8 mb-2 transform group-hover:-translate-y-1 transition-transform"
                  fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                </svg>
                <span class="font-bold text-sm md:text-base">Setor</span>
              </a>
              <a href="<?= base_url() ?>home/loadTarik"
                class="group flex flex-col items-center justify-center bg-teal-50/50 hover:bg-teal-100 text-brand-green rounded-2xl py-4 transition-all active:scale-95 duration-200 border border-teal-100">
                <svg class="w-7 h-7 md:w-8 md:h-8 mb-2 transform group-hover:-translate-y-1 transition-transform"
                  fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                </svg>
                <span class="font-bold text-sm md:text-base">Tarik</span>
              </a>
            </div>

          </div>
        </div>

      </div>
    </div>

    <!-- Menu Section -->
    <div class="px-6 md:px-12 mt-8 md:mt-12">
      <h3 class="text-lg md:text-xl font-bold text-gray-800 mb-6">Menu Bantuan</h3>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
        <a href="<?= base_url() ?>jenissampah"
          class="flex items-center bg-white hover:bg-gray-50 rounded-2xl p-5 md:p-6 transition-colors shadow-sm border border-gray-100 active:bg-gray-100">
          <div class="bg-brand-light text-brand-yellow rounded-xl p-3 mr-4 md:mr-6">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16">
              </path>
            </svg>
          </div>
          <div>
            <h4 class="font-bold text-gray-800 text-base md:text-lg">List Jenis Sampah</h4>
            <p class="text-sm text-gray-500 mt-1">Sampah apa saja yang ditukar?</p>
          </div>
          <svg class="w-6 h-6 ml-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
          </svg>
        </a>

        <a href="<?= base_url() ?>tutorial"
          class="flex items-center bg-white hover:bg-gray-50 rounded-2xl p-5 md:p-6 transition-colors shadow-sm border border-gray-100 active:bg-gray-100">
          <div class="bg-green-100 text-brand-green rounded-xl p-3 mr-4 md:mr-6">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
              </path>
            </svg>
          </div>
          <div>
            <h4 class="font-bold text-gray-800 text-base md:text-lg">Cara Menggunakan</h4>
            <p class="text-sm text-gray-500 mt-1">Panduan penggunaan aplikasi</p>
          </div>
          <svg class="w-6 h-6 ml-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
          </svg>
        </a>
      </div>
    </div>

    <!-- Artikel Section -->
    <div class="px-6 md:px-12 mt-8 md:mt-12 mb-12">
      <h3 class="text-lg md:text-xl font-bold text-gray-800 mb-6">Artikel Terkini</h3>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
        <?php foreach ($artikel as $key): ?>
          <a href="<?= base_url("artikel/detailArtikel?id={$key['id']}") ?>"
            class="block bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md hover:-translate-y-1 transition-all">
            <img src="<?= base_url() ?>uploads/<?= $key['gambar'] ?>"
              class="w-full h-40 md:h-48 object-cover object-center bg-gray-200" alt="Artikel image">
            <div class="p-5">
              <p class="font-bold text-base text-gray-800 line-clamp-2"><?= $key['judul'] ?></p>
            </div>
          </a>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Include Bottom Navigation Menu -->
    <?php include('menu.php'); ?>

  </div>
</body>

</html>