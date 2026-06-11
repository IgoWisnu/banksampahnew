<div class="container-fluid pt-4 px-4">
    <div class="row mb-4 align-items-center">
        <div class="col-lg-6">
            <h3 class="fs-4 mb-0 fw-bold text-dark">Manajemen Banjar</h3>
            <p class="text-muted mb-0">Kelola data Banjar dan pengaturan Margin.</p>
        </div>
        <div class="col-lg-6 text-end">
            <button class="btn btn-primary rounded-pill shadow-sm px-4 py-2" data-bs-toggle="modal" data-bs-target="#addBanjarModal">
                <i class="fas fa-plus me-2"></i> Tambah Banjar
            </button>
        </div>
    </div>


    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-muted">
                        <tr>
                            <th class="ps-4 py-3">ID</th>
                            <th class="py-3">Nama Banjar</th>
                            <th class="py-3">Alamat</th>
                            <th class="py-3">Margin (%)</th>
                            <th class="pe-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        <?php if (count($banjars) > 0): ?>
                            <?php foreach ($banjars as $b): ?>
                            <tr>
                                <td class="ps-4 text-muted">#<?= $b->id ?></td>
                                <td class="fw-bold text-dark"><?= $b->nama ?></td>
                                <td><?= $b->alamat ?></td>
                                <td>
                                    <span class="badge bg-warning text-dark px-3 py-2 fs-6 rounded-pill">
                                        <?= isset($b->margin_value) ? $b->margin_value : 0 ?>%
                                    </span>
                                </td>
                                <td class="pe-4 text-center">
                                    <button class="btn btn-sm btn-outline-primary px-3 rounded-pill me-1" 
                                        data-bs-toggle="modal" data-bs-target="#editBanjarModal<?= $b->id ?>">
                                        Edit
                                    </button>
                                    <form action="<?= base_url('superadmin/delete_banjar/' . $b->id) ?>" method="post" class="d-inline">
                                        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                                        <button type="button" class="btn btn-sm btn-outline-danger px-3 rounded-pill" onclick="confirmDelete(this, 'Yakin ingin menghapus banjar ini? Semua user terkait mungkin kehilangan relasi.')">Hapus</button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editBanjarModal<?= $b->id ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header border-0 pb-0">
                                            <h5 class="modal-title fw-bold">Edit Banjar</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="<?= base_url('superadmin/update_banjar') ?>" method="post">
                                            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                                            <div class="modal-body">
                                                <input type="hidden" name="id" value="<?= $b->id ?>">
                                                <div class="mb-3">
                                                    <label class="form-label text-muted small fw-medium">Nama Banjar</label>
                                                    <input type="text" class="form-control" name="nama" value="<?= $b->nama ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label text-muted small fw-medium">Alamat</label>
                                                    <textarea class="form-control" name="alamat" required><?= $b->alamat ?></textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label text-muted small fw-medium">Margin (%)</label>
                                                    <input type="number" step="0.01" class="form-control" name="margin_value" value="<?= isset($b->margin_value) ? $b->margin_value : 0 ?>" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0">
                                                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary rounded-pill px-4">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">Belum ada data banjar.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addBanjarModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Tambah Banjar Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('superadmin/add_banjar') ?>" method="post">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-medium">Nama Banjar</label>
                        <input type="text" class="form-control" name="nama" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-medium">Alamat</label>
                        <textarea class="form-control" name="alamat" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-medium">Margin (%)</label>
                        <input type="number" step="0.01" class="form-control" name="margin_value" value="0" required>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // 1. Menangkap Flashdata untuk Notifikasi Sukses/Gagal
    document.addEventListener("DOMContentLoaded", function() {
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
                title: 'Oops!',
                text: '<?= $this->session->flashdata('failed'); ?>'
            });
        <?php endif; ?>
    });

    // 2. Fungsi SweetAlert untuk Konfirmasi Hapus
    function confirmDelete(button, message) {
        Swal.fire({
            title: 'Apakah Anda Yakin?',
            text: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-trash me-1"></i> Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Jika user klik "Ya", cari form tempat tombol ini berada lalu submit otomatis
                button.closest('form').submit();
            }
        });
    }
</script>
