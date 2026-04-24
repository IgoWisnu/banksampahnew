<div class="container-fluid pt-4 px-4">
    <div class="row mb-4 align-items-center">
        <div class="col-lg-12">
            <h3 class="fs-4 mb-0 fw-bold text-dark">Cetak Laporan Transaksi</h3>
            <p class="text-muted mb-0">Cetak riwayat transaksi ke format PDF berdasarkan rentang tanggal yang dipilih.</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-4">
            <form method="post" action="<?= base_url('generatepdf/pdftransaksi'); ?>">
                <div class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label for="date_from" class="form-label fw-medium text-muted small">Dari Tanggal</label>
                        <div class="input-group shadow-sm rounded-3">
                            <span class="input-group-text bg-light border-0"><i class="fas fa-calendar-alt text-muted"></i></span>
                            <input type="date" class="form-control bg-light border-0 py-2" id="date_from" name="date_from" required>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <label for="date_to" class="form-label fw-medium text-muted small">Sampai Tanggal</label>
                        <div class="input-group shadow-sm rounded-3">
                            <span class="input-group-text bg-light border-0"><i class="fas fa-calendar-check text-muted"></i></span>
                            <input type="date" class="form-control bg-light border-0 py-2" id="date_to" name="date_to" required>
                        </div>
                    </div>
                    <div class="col-md-2 mt-4 mt-md-0">
                        <button type="submit" class="btn btn-danger w-100 py-2 shadow-sm fw-bold rounded-3">
                            <i class="fas fa-file-pdf me-1"></i> Cetak PDF
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
    // --- Deteksi Flashdata untuk Notifikasi Sukses/Gagal (SweetAlert2) ---
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