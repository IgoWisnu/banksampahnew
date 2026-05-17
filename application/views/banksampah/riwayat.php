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

  <div class="w-full mx-auto bg-gray-50 min-h-screen relative shadow-none overflow-x-hidden pb-32">

    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-100 sticky top-0 z-50">
      <div class="flex items-center justify-between px-6 md:px-12 h-20">
        <a href="<?= base_url() ?>home/loadArtikel"
          class="flex items-center text-gray-600 hover:text-brand-green group transition-colors">
          <svg class="w-5 h-5 mr-1 transform group-hover:-translate-x-1 transition-transform" fill="none"
            stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
          </svg>
          <span class="font-medium text-sm">Kembali</span>
        </a>
        <h1 class="text-xl font-bold text-gray-900 absolute left-1/2 transform -translate-x-1/2">Riwayat</h1>
        <div class="w-20"></div>
      </div>
    </div>

    <div class="px-6 md:px-12 mt-8 max-w-4xl mx-auto">

      <!-- ============================================ -->
      <!-- PANEL SALDO DINAMIS (BARU)                  -->
      <!-- ============================================ -->
      <div class="bg-gradient-to-br from-brand-green to-brand-dark rounded-3xl shadow-lg p-6 text-white mb-6 relative overflow-hidden">
        <div class="absolute -right-8 -top-8 w-32 h-32 bg-white/10 rounded-full"></div>
        <div class="absolute -right-12 -bottom-12 w-40 h-40 bg-white/5 rounded-full"></div>
        
        <div class="relative">
          <p class="text-sm text-white/80 font-medium">Saldo Anda Saat Ini</p>
          <h2 class="text-4xl font-bold mt-2 mb-1">
            Rp <?= number_format($saldo_dinamis ?? 0, 0, ',', '.') ?>
          </h2>
          <p class="text-xs text-white/70 flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Saldo mengikuti harga sampah terkini
          </p>
        </div>
      </div>

      <!-- ============================================ -->
      <!-- STOK SAMPAH NASABAH (BARU)                  -->
      <!-- ============================================ -->
      <?php if(isset($stok_jenis) && $stok_jenis->num_rows() > 0): ?>
      <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
          <h3 class="font-bold text-gray-800">Stok Sampah Anda</h3>
          <span class="text-xs text-gray-500"><?= $stok_jenis->num_rows() ?> jenis</span>
        </div>
        <div class="space-y-3">
          <?php foreach($stok_jenis->result_array() as $stok): ?>
          <div class="flex items-center justify-between border-b border-gray-50 pb-3 last:border-0 last:pb-0">
            <div class="flex items-center">
              <div class="w-10 h-10 bg-brand-light rounded-lg flex items-center justify-center mr-3">
                <svg class="w-5 h-5 text-brand-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3"/>
                </svg>
              </div>
              <div>
                <p class="font-semibold text-sm text-gray-800"><?= htmlspecialchars($stok['jenis_sampah']) ?></p>
                <p class="text-xs text-gray-500">
                  <?= number_format($stok['total_berat_sisa'], 2, ',', '.') ?> kg 
                  &middot; Rp <?= number_format($stok['harga_sekarang'], 0, ',', '.') ?>/kg
                </p>
              </div>
            </div>
            <div class="text-right">
              <p class="font-bold text-sm text-brand-green">
                Rp <?= number_format($stok['nilai_sekarang'], 0, ',', '.') ?>
              </p>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
        <div class="mt-4 pt-4 border-t border-gray-100 text-xs text-gray-500 flex items-start gap-2">
          <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
              d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
          <span>Nilai sampah Anda menyesuaikan harga pasar terkini, mirip seperti tabungan emas.</span>
        </div>
      </div>
      <?php endif; ?>

      <!-- ============================================ -->
      <!-- RIWAYAT TRANSAKSI                            -->
      <!-- ============================================ -->
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Riwayat Transaksi</h2>
      </div>

      <div class="space-y-4">
        <?php foreach ($riwayat->result_array() as $key): ?>
          <?php
          if ($key['kredit'] == 0) {
            // SETOR: debit > 0
            $iconClass = "bg-blue-100 text-blue-600";
            $iconPath = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>';
            $nominalClass = "text-blue-600";
            $nominal = '+Rp ' . number_format($key['debit'], 0, ',', '.');
            $ket = 'Setor Sampah';
          } else {
            // TARIK: kredit > 0
            $iconClass = "bg-brand-light text-brand-yellow";
            $iconPath = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>';
            $nominalClass = "text-gray-900";
            $nominal = '-Rp ' . number_format($key['kredit'], 0, ',', '.');
            $ket = 'Tarik Saldo';
          }
          ?>

          <a href="<?= base_url() ?>riwayat/invoice?id=<?= $key['id_tabungan_transaksi'] ?>" class="block group">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center justify-between hover:shadow-md hover:border-brand-green/30 transition-all">
              <div class="flex items-center">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center mr-4 <?= $iconClass ?>">
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <?= $iconPath ?>
                  </svg>
                </div>
                <div>
                  <h4 class="font-bold text-gray-800 text-sm sm:text-base"><?= $ket ?></h4>
                  <p class="text-xs text-gray-500 mt-1"><?= $key['tgl_tabungan_transaksi'] ?></p>
                </div>
              </div>

              <div class="text-right">
                <p class="font-bold text-base sm:text-lg <?= $nominalClass ?>"><?= $nominal ?></p>
                <p class="text-xs text-gray-400 mt-1 flex items-center justify-end group-hover:text-brand-green transition-colors">
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
                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
              </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-800">Tidak ada riwayat</h3>
            <p class="text-gray-500 text-sm mt-1">Kamu belum pernah melakukan transaksi.</p>
          </div>
        <?php endif; ?>
      </div>

    </div>

  </div>

  <?php include('menu.php'); ?>

</body>

</html>