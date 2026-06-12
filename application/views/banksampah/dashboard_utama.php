<div class="container-fluid pt-2 px-4 mb-5">

    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-3 text-white"
                style="background: linear-gradient(135deg, #198754, #20c997);">
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex align-items-center">
                        <div class="me-4 d-none d-md-block">
                            <i class="fas fa-leaf fa-4x text-white opacity-50"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-2">Halo, <?= $username; ?>! 🌿</h3>
                            <p class="mb-0 fs-6 text-white-50" style="max-width: 800px;">
                                Selamat datang di ruang kerja Admin Banjar. Di sini Anda bisa melayani penyetoran sampah
                                dari nasabah, melakukan penarikan saldo, serta memantau pergerakan harga sampah hari
                                ini.
                            </p>
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
                    <h5 class="fw-bold text-dark mb-0"><i class="fas fa-exchange-alt text-success me-2"></i>Transaksi
                        Cepat</h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-grid gap-3">
                        <a href="<?= base_url('setorsampah') ?>"
                            class="btn btn-outline-success p-3 text-start rounded-3 fw-medium"
                            style="transition: 0.3s;">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-balance-scale me-2 fs-5 align-middle"></i>
                                    <span>Setor Sampah</span>
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
                    <h5 class="fw-bold text-dark mb-0"><i class="fas fa-bullhorn text-warning me-2"></i>Pusat Informasi
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="alert alert-primary border-0 shadow-sm rounded-3 mb-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle fs-3 me-3 text-primary"></i>
                            <div>
                                <h6 class="mb-1 fw-bold">Perbarui Harga Sampah</h6>
                                <small class="text-muted">Pastikan selalu mengecek menu <b>Data Sampah</b> agar nasabah
                                    mendapatkan harga terbaru sesuai harga pengepul.</small>
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