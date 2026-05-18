<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Riwayat Transaksi - Banksampah</title>
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
  <div class="w-full mx-auto bg-gray-50 min-h-screen relative shadow-none overflow-x-hidden pb-32">

    <!-- Header block without graphic overlay -->
    <div class="bg-white shadow-sm border-b border-gray-100 sticky top-0 z-50">
      <div class="flex items-center justify-between px-6 md:px-12 h-20">
        <!-- Back Button -->
        <a href="<?= base_url() ?>home/loadArtikel"
          class="flex items-center text-gray-600 hover:text-brand-green group transition-colors">
          <svg class="w-5 h-5 mr-1 transform group-hover:-translate-x-1 transition-transform" fill="none"
            stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
          </svg>
          <span class="font-medium text-sm">Kembali</span>
        </a>
        <h1 class="text-xl font-bold text-gray-900 absolute left-1/2 transform -translate-x-1/2">Riwayat</h1>
        <div class="w-20"></div> <!-- Placeholder for alignment -->
      </div>
    </div>

    <div class="px-6 md:px-12 mt-8 max-w-4xl mx-auto">
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Riwayat Transaksi</h2>
        <a href=""
          class="text-red-500 hover:text-red-700 hover:bg-red-50 text-sm font-semibold rounded-lg px-3 py-1.5 transition-colors border border-red-200">
          Hapus Transaksi
        </a>
      </div>

      <div class="space-y-4">
        <?php foreach ($riwayat->result_array() as $key): ?>
          <?php
          if ($key['kredit'] == 0) {
            // Setor
            $iconClass = "bg-blue-100 text-blue-600";
            $iconPath = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>';
            $nominalClass = "text-blue-600";
            $debitValue = isset($key['debit_final']) && $key['debit_final'] > 0 ? $key['debit_final'] : $key['debit'];
            $nominal = '+' . number_format($debitValue, 0, ',', '.');
            $ket = 'Setor Sampah';
          } else {
            // Tarik
            $iconClass = "bg-brand-light text-brand-green";
            $iconPath = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>';
            $nominalClass = "text-gray-900";
            $nominal = '-' . number_format($key['kredit'], 0, ',', '.');
            $ket = 'Tarik Saldo';
          }
          ?>

          <a href="<?= base_url() ?>riwayat/invoice?id=<?= $key['id_tabungan_transaksi'] ?>" class="block group">
            <div
              class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center justify-between hover:shadow-md hover:border-brand-green/30 transition-all">

              <div class="flex items-center">
                <!-- Transaction Icon -->
                <div class="w-12 h-12 rounded-xl flex items-center justify-center mr-4 <?= $iconClass ?>">
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <?= $iconPath ?>
                  </svg>
                </div>

                <!-- Meta Text -->
                <div>
                  <h4 class="font-bold text-gray-800 text-sm sm:text-base"><?= $ket ?></h4>
                  <p class="text-xs text-gray-500 mt-1"><?= $key['tgl_tabungan_transaksi'] ?></p>
                </div>
              </div>

              <!-- Amount & Keterangan -->
              <div class="text-right">
                <p class="font-bold text-base sm:text-lg <?= $nominalClass ?>"><?= $nominal ?></p>
                <p
                  class="text-xs text-gray-400 mt-1 flex items-center justify-end group-hover:text-brand-green transition-colors">
                  Lihat Detail
                  <svg class="w-3 h-3 ml-1 transform group-hover:translate-x-0.5 transition-transform" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                  </svg>
                </p>
              </div>
            </div>
          </a>

        <?php endforeach; ?>

        <?php if (empty($riwayat->result_array())): ?>
          <div class="text-center py-16 bg-white rounded-3xl border border-gray-100 shadow-sm">
            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                </path>
              </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-800">Tidak ada riwayat</h3>
            <p class="text-gray-500 text-sm mt-1">Kamu belum pernah melakukan transaksi.</p>
          </div>
        <?php endif; ?>
      </div>

    </div>

  </div>

  <!-- Include Bottom Navigation Menu -->
  <?php include('menu.php'); ?>

</body>

</html>