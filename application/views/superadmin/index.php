<div class="container-fluid pt-2 px-4 mb-5">
    
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-3 text-white" style="background: linear-gradient(135deg, #198754, #20c997);">
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex align-items-center">
                        <div class="me-4 d-none d-md-block">
                            <i class="fas fa-shield-alt fa-4x text-white opacity-50"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-2">Selamat Datang, Superadmin! 👋</h3>
                            <p class="mb-0 fs-6 text-white-50" style="max-width: 800px;">
                                Ini adalah Pusat Kendali Utama sistem MANKADIBALIRECYCLING. Di sini Anda memiliki otoritas penuh untuk memperluas jaringan operasional dengan menambah cabang (Banjar) baru, serta menunjuk Admin untuk mengelola cabang tersebut.
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
                    <h5 class="fw-bold text-dark mb-0"><i class="fas fa-bolt text-warning me-2"></i>Akses Cepat</h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-grid gap-3">
                        <a href="<?= base_url('superadmin/banjar') ?>" class="btn btn-outline-success p-3 text-start rounded-3 fw-medium transition-all" style="transition: 0.3s;">
                            <i class="fas fa-building me-2 fs-5 align-middle"></i> Kelola Data Banjar
                            <i class="fas fa-chevron-right float-end mt-1"></i>
                        </a>
                        <a href="<?= base_url('superadmin/admin') ?>" class="btn btn-outline-primary p-3 text-start rounded-3 fw-medium" style="transition: 0.3s;">
                            <i class="fas fa-user-shield me-2 fs-5 align-middle"></i> Kelola Akun Admin
                            <i class="fas fa-chevron-right float-end mt-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
             <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                    <h5 class="fw-bold text-dark mb-0"><i class="fas fa-server text-info me-2"></i>Status Sistem</h5>
                </div>
                <div class="card-body p-4">
                    <ul class="list-group list-group-flush mb-3">
                        <li class="list-group-item px-0 text-muted border-0 py-2"><i class="fas fa-check-circle text-success me-2"></i> Keamanan Anti-CSRF <span class="badge bg-success float-end mt-1">Aktif</span></li>
                        <li class="list-group-item px-0 text-muted border-0 py-2"><i class="fas fa-check-circle text-success me-2"></i> Enkripsi Password (Bcrypt) <span class="badge bg-success float-end mt-1">Aktif</span></li>
                        <li class="list-group-item px-0 text-muted border-0 py-2"><i class="fas fa-check-circle text-success me-2"></i> Proteksi Mode Produksi <span class="badge bg-success float-end mt-1">Aktif</span></li>
                    </ul>
                    
                    <div class="alert alert-light border shadow-sm mb-0 rounded-3">
                        <small class="text-muted"><i class="fas fa-lightbulb text-warning me-1"></i> <b>Tips:</b> Pastikan setiap Banjar memiliki minimal 1 Admin aktif agar operasional transaksi bank sampah di wilayah tersebut dapat berjalan lancar.</small>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>