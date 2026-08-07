<div class="container-fluid pt-2 px-4 mb-5">

    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-3 text-white"
                style="background: linear-gradient(135deg, #198754, #20c997);">
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <div>
                            <h3 class="fw-bold mb-2">Halo, <?= $username; ?>! 🌿</h3>
                            <p class="mb-0 fs-6 text-white-50" style="max-width: 800px;">
                                Selamat datang di ruang kerja MANKADIBALIRECYCLING. Di sini Anda bisa memantau pergerakan arus kas realtime, melayani pengadaan & penjualan sampah, serta mengelola invoice pembayaran.
                            </p>
                        </div>
                        <div class="d-none d-md-block">
                            <i class="fas fa-leaf fa-4x text-white opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- WIDGET RINGKASAN ARUS KAS & UTANG-PIUTANG REALTIME -->
    <div class="row g-3 mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <h5 class="fw-bold text-dark mb-0"><i class="fas fa-wallet text-success me-2"></i>Ringkasan Arus Kas & Utang-Piutang</h5>
                <a href="<?= base_url('payment') ?>" class="btn btn-sm btn-outline-success rounded-pill fw-bold">
                    Detail Invoice & Pelunasan <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm rounded-3 h-100 bg-white">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small fw-medium d-block">Saldo Kas Realtime</span>
                            <h4 class="fw-bold mb-0 <?= (isset($fin_summary->saldo_kas) && $fin_summary->saldo_kas >= 0) ? 'text-success' : 'text-danger' ?>">
                                Rp <?= number_format(isset($fin_summary->saldo_kas) ? $fin_summary->saldo_kas : 0, 0, ',', '.') ?>
                            </h4>
                            <small class="text-muted" style="font-size:0.75rem;">(Kas Masuk - Kas Keluar)</small>
                        </div>
                        <div class="p-3 bg-success bg-opacity-10 text-success rounded-circle">
                            <i class="fas fa-vault fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm rounded-3 h-100 bg-white">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small fw-medium d-block">Kas Masuk (Penjualan Lunas)</span>
                            <h4 class="fw-bold text-primary mb-0">
                                + Rp <?= number_format(isset($fin_summary->kas_masuk) ? $fin_summary->kas_masuk : 0, 0, ',', '.') ?>
                            </h4>
                            <small class="text-muted" style="font-size:0.75rem;">Pendapatan dari Pembeli</small>
                        </div>
                        <div class="p-3 bg-primary bg-opacity-10 text-primary rounded-circle">
                            <i class="fas fa-arrow-down fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm rounded-3 h-100 bg-white">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small fw-medium d-block">Kas Keluar (Pembelian Lunas)</span>
                            <h4 class="fw-bold text-danger mb-0">
                                - Rp <?= number_format(isset($fin_summary->kas_keluar) ? $fin_summary->kas_keluar : 0, 0, ',', '.') ?>
                            </h4>
                            <small class="text-muted" style="font-size:0.75rem;">Pengeluaran ke Pengepul</small>
                        </div>
                        <div class="p-3 bg-danger bg-opacity-10 text-danger rounded-circle">
                            <i class="fas fa-arrow-up fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-6">
            <div class="card border-0 shadow-sm rounded-3 h-100 bg-white border-start border-4 border-warning">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small fw-medium d-block">Utang Usaha (Beli Pending)</span>
                            <h4 class="fw-bold text-warning mb-0">
                                Rp <?= number_format(isset($fin_summary->utang_beli) ? $fin_summary->utang_beli : 0, 0, ',', '.') ?>
                            </h4>
                            <small class="text-muted" style="font-size:0.75rem;">Kewajiban Bayar Sampah Masuk</small>
                        </div>
                        <div class="p-3 bg-warning bg-opacity-10 text-warning rounded-circle">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-6">
            <div class="card border-0 shadow-sm rounded-3 h-100 bg-white border-start border-4 border-info">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small fw-medium d-block">Piutang Usaha (Jual Pending)</span>
                            <h4 class="fw-bold text-info mb-0">
                                Rp <?= number_format(isset($fin_summary->piutang_jual) ? $fin_summary->piutang_jual : 0, 0, ',', '.') ?>
                            </h4>
                            <small class="text-muted" style="font-size:0.75rem;">Tagihan ke Buyer / Pabrik Daur Ulang</small>
                        </div>
                        <div class="p-3 bg-info bg-opacity-10 text-info rounded-circle">
                            <i class="fas fa-file-invoice-dollar fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                    <h5 class="fw-bold text-dark mb-0"><i class="fas fa-exchange-alt text-success me-2"></i>Transaksi Cepat</h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-grid gap-3">
                        <a href="<?= base_url('setorsampah') ?>"
                            class="btn btn-outline-success p-3 text-start rounded-3 fw-medium" style="transition: 0.3s;">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-cart-plus me-2 fs-5 align-middle"></i>
                                    <span>Beli Sampah (Setor dari Pengepul)</span>
                                </div>
                                <i class="fas fa-chevron-right mt-1"></i>
                            </div>
                        </a>
                        <a href="<?= base_url('jualSampah') ?>"
                            class="btn btn-outline-primary p-3 text-start rounded-3 fw-medium" style="transition: 0.3s;">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-truck-loading me-2 fs-5 align-middle"></i>
                                    <span>Jual Sampah (Pengeluaran ke Buyer)</span>
                                </div>
                                <i class="fas fa-chevron-right mt-1"></i>
                            </div>
                        </a>
                        <a href="<?= base_url('tarik') ?>"
                            class="btn btn-outline-danger p-3 text-start rounded-3 fw-medium" style="transition: 0.3s;">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-money-bill-wave me-2 fs-5 align-middle"></i>
                                    <span>Tarik Saldo Nasabah</span>
                                </div>
                                <i class="fas fa-chevron-right mt-1"></i>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                    <h5 class="fw-bold text-dark mb-0"><i class="fas fa-bullhorn text-warning me-2"></i>Pusat Informasi</h5>
                </div>
                <div class="card-body p-4">
                    <div class="alert alert-primary border-0 shadow-sm rounded-3 mb-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle fs-3 me-3 text-primary"></i>
                            <div>
                                <h6 class="mb-1 fw-bold">Perbarui Harga Sampah</h6>
                                <small class="text-muted">Pastikan selalu mengecek menu <b>Data Sampah</b> agar nasabah mendapatkan harga terbaru sesuai harga pengepul.</small>
                            </div>
                        </div>
                    </div>

                    <a href="<?= base_url('dashboard/loadBerita') ?>"
                        class="btn btn-light w-100 text-start p-3 rounded-3 text-dark shadow-sm">
                        <i class="fas fa-newspaper text-info me-2"></i> Tulis Berita / Edukasi Baru
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>