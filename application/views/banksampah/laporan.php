<div class="container-fluid pt-4 px-4">
    <div class="row mb-4 align-items-center">
        <div class="col-lg-12">
            <h3 class="fs-4 mb-0 fw-bold text-dark">Cetak Laporan Kas & Invoice</h3>
            <p class="text-muted mb-0">Cetak rekapitulasi arus kas, utang-piutang, dan riwayat invoice ke format PDF & Excel.</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-4">
            <h6 class="fw-bold text-dark mb-3"><i class="fas fa-file-invoice-dollar text-primary me-2"></i>Laporan Arus Kas & Management Invoice</h6>
            <form method="post" target="_blank">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                
                <div class="row g-3 mb-3">
                    <div class="col-md-3">
                        <label for="date_from" class="form-label fw-medium text-muted small">Dari Tanggal <span class="text-danger">*</span></label>
                        <div class="input-group shadow-sm rounded-3">
                            <span class="input-group-text bg-light border-0"><i class="fas fa-calendar-alt text-muted"></i></span>
                            <input type="date" class="form-control bg-light border-0 py-2" id="date_from" name="date_from" value="<?= date('Y-m-01') ?>" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="date_to" class="form-label fw-medium text-muted small">Sampai Tanggal <span class="text-danger">*</span></label>
                        <div class="input-group shadow-sm rounded-3">
                            <span class="input-group-text bg-light border-0"><i class="fas fa-calendar-check text-muted"></i></span>
                            <input type="date" class="form-control bg-light border-0 py-2" id="date_to" name="date_to" value="<?= date('Y-m-d') ?>" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="tipe" class="form-label fw-medium text-muted small">Tipe Transaksi</label>
                        <select name="tipe" id="tipe" class="form-select bg-light border-0 shadow-sm py-2">
                            <option value="all">Semua Tipe (Beli & Jual)</option>
                            <option value="beli">Beli Sampah (Pengadaan)</option>
                            <option value="jual">Jual Sampah (Penjualan)</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="status" class="form-label fw-medium text-muted small">Status Pembayaran</label>
                        <select name="status" id="status" class="form-select bg-light border-0 shadow-sm py-2">
                            <option value="all">Semua Status</option>
                            <option value="Lunas">Lunas (Sudah Terbayar)</option>
                            <option value="Pending">Pending (Utang / Piutang)</option>
                        </select>
                    </div>
                </div>

                <div class="row g-2 justify-content-end">
                    <div class="col-md-3 col-lg-2">
                        <button type="submit" formaction="<?= base_url('generatepdf/pdftransaksi'); ?>" class="btn btn-danger w-100 py-2 shadow-sm fw-bold rounded-3">
                            <i class="fas fa-file-pdf me-1"></i> Cetak PDF Laporan Kas
                        </button>
                    </div>
                    <div class="col-md-3 col-lg-2">
                        <button type="submit" formaction="<?= base_url('generatepdf/exceltransaksi'); ?>" class="btn btn-success w-100 py-2 shadow-sm fw-bold rounded-3">
                            <i class="fas fa-file-excel me-1"></i> Export Excel Laporan Kas
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Card Laporan Stok Sampah -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h6 class="fw-bold text-dark mb-1"><i class="fas fa-boxes text-success me-2"></i>Laporan Stok Sampah Realtime (Inventory Ledger)</h6>
                    <p class="text-muted small mb-0">Cetak rekapitulasi stok masuk (Beli), stok keluar (Jual), sisa stok, dan audit trail pergerakan barang.</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="<?= base_url('generatepdf/pdfstok') ?>" target="_blank" class="btn btn-outline-danger px-4 py-2 shadow-sm fw-bold rounded-3">
                        <i class="fas fa-file-pdf me-1"></i> Cetak PDF
                    </a>
                    <a href="<?= base_url('generatepdf/excelstok') ?>" target="_blank" class="btn btn-outline-success px-4 py-2 shadow-sm fw-bold rounded-3">
                        <i class="fas fa-file-excel me-1"></i> Export Excel
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Card Laporan Matriks Transaksi -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-4">
            <h6 class="fw-bold text-dark mb-3"><i class="fas fa-table text-info me-2"></i>Laporan Matriks Transaksi per Pihak (Excel)</h6>
            <form method="post" target="_blank" action="<?= base_url('generatepdf/excelmatrix') ?>">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                
                <div class="row g-3 mb-3">
                    <div class="col-md-3">
                        <label for="matrix_date_from" class="form-label fw-medium text-muted small">Dari Tanggal <span class="text-danger">*</span></label>
                        <div class="input-group shadow-sm rounded-3">
                            <span class="input-group-text bg-light border-0"><i class="fas fa-calendar-alt text-muted"></i></span>
                            <input type="date" class="form-control bg-light border-0 py-2" id="matrix_date_from" name="date_from" value="<?= date('Y-m-01') ?>" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="matrix_date_to" class="form-label fw-medium text-muted small">Sampai Tanggal <span class="text-danger">*</span></label>
                        <div class="input-group shadow-sm rounded-3">
                            <span class="input-group-text bg-light border-0"><i class="fas fa-calendar-check text-muted"></i></span>
                            <input type="date" class="form-control bg-light border-0 py-2" id="matrix_date_to" name="date_to" value="<?= date('Y-m-d') ?>" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="matrix_tipe" class="form-label fw-medium text-muted small">Tipe Transaksi</label>
                        <select name="tipe" id="matrix_tipe" class="form-select bg-light border-0 shadow-sm py-2">
                            <option value="beli">Beli Sampah (Pengadaan)</option>
                            <option value="jual">Jual Sampah (Penjualan)</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="pihak_terkait" class="form-label fw-medium text-muted small">Pihak Terkait</label>
                        <select name="pihak_terkait" id="pihak_terkait" class="form-select bg-light border-0 shadow-sm py-2">
                            <?php if(!empty($pihak_terkait)): ?>
                                <?php foreach($pihak_terkait as $p): ?>
                                    <option value="<?= htmlspecialchars($p['nama']) ?>"><?= htmlspecialchars($p['nama']) ?></option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="">- Belum ada data transaksi -</option>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>

                <div class="row g-2 justify-content-end">
                    <div class="col-md-3 col-lg-3">
                        <button type="submit" class="btn btn-info text-white w-100 py-2 shadow-sm fw-bold rounded-3">
                            <i class="fas fa-file-excel me-1"></i> Export Excel Matriks
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        <?php if($this->session->flashdata('success')): ?>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '<?= $this->session->flashdata('success'); ?>',
                timer: 3000,
                showConfirmButton: false
            });
        <?php elseif($this->session->flashdata('failed')): ?>
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '<?= $this->session->flashdata('failed'); ?>'
            });
        <?php endif; ?>
    });
</script>