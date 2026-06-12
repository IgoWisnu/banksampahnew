<div class="container-fluid pt-4 px-4">
    <div class="row mb-4 align-items-center">
        <div class="col-lg-6">
            <h3 class="fs-4 mb-0 fw-bold text-dark">Manajemen Admin</h3>
            <p class="text-muted mb-0">Kelola akun Admin untuk setiap Banjar.</p>
        </div>
        <div class="col-lg-6 text-end">
            <button class="btn btn-primary rounded-pill shadow-sm px-4 py-2" data-bs-toggle="modal"
                data-bs-target="#addAdminModal">
                <i class="fas fa-plus me-2"></i> Tambah Admin
            </button>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-muted">
                        <tr>
                            <th class="py-3">Username</th>
                            <th class="py-3">Email</th>
                            <th class="py-3">Banjar</th>
                            <th class="pe-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        <?php if (count($admins) > 0): ?>
                            <?php foreach ($admins as $a): ?>
                                <tr>
                                    <td>@<?= $a['username'] ?></td>
                                    <td><?= $a['email'] ?></td>
                                    <td>
                                        <?php if ($a['nama']): ?>
                                            <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill text-white-50">
                                                <?= $a['nama'] ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-pill">
                                                Belum Ada Banjar
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="pe-4 text-center">
                                        <button class="btn btn-sm btn-outline-primary px-3 rounded-pill me-1" 
                                            data-bs-toggle="modal" data-bs-target="#editAdminModal<?= $a['id_user'] ?>">
                                            Edit
                                        </button>

                                        <form action="<?= base_url('superadmin/reset_password_admin/' . $a['id_user']) ?>" method="post" class="d-inline">
                                            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                                            <button type="button" class="btn btn-sm btn-outline-warning px-3 rounded-pill me-1" onclick="confirmReset(this, 'Yakin ingin mereset password admin?')">Reset Pass</button>
                                        </form>

                                        <form action="<?= base_url('superadmin/delete_admin/' . $a['id_user']) ?>" method="post" class="d-inline">
                                            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                                            <button type="button" class="btn btn-sm btn-outline-danger px-3 rounded-pill" onclick="confirmDelete(this, 'Yakin ingin menghapus admin ini?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                <div class="modal fade" id="editAdminModal<?= $a['id_user'] ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header border-0 pb-0">
                                                <h5 class="modal-title fw-bold text-start">Edit Data Admin</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="<?= base_url('superadmin/update_admin') ?>" method="post">
                                                <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                                                <div class="modal-body text-start">
                                                    <input type="hidden" name="id_user" value="<?= $a['id_user'] ?>">
                                                    <div class="mb-3">
                                                        <label class="form-label text-muted small fw-medium">Username</label>
                                                        <input type="text" class="form-control" name="username" value="<?= $a['username'] ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label text-muted small fw-medium">Email</label>
                                                        <input type="email" class="form-control" name="email" value="<?= $a['email'] ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label text-muted small fw-medium">Pilih Banjar</label>
                                                        <select class="form-select" name="banjar_id" required>
                                                            <option value="" disabled>-- Pilih Banjar --</option>
                                                            <?php foreach ($banjars as $b): ?>
                                                                <option value="<?= $b->id ?>" <?= ($a['banjar_id'] == $b->id) ? 'selected' : '' ?>><?= $b->nama ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
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
                                <td colspan="5" class="text-center py-4 text-muted">Belum ada data admin.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addAdminModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Tambah Admin Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('superadmin/add_admin') ?>" method="post">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-medium">Username</label>
                        <input type="text" class="form-control" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-medium">Email</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-medium">Password</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-medium">Pilih Banjar</label>
                        <select class="form-select" name="banjar_id" required>
                            <option value="" disabled selected>-- Pilih Banjar --</option>
                            <?php foreach ($banjars as $b): ?>
                                <option value="<?= $b->id ?>"><?= $b->nama ?></option>
                            <?php endforeach; ?>
                        </select>
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

    // 3. Fungsi SweetAlert untuk Konfirmasi Reset Password
    function confirmReset(button, message) {
        Swal.fire({
            title: 'Reset Password?',
            text: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f59e0b',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-key me-1"></i> Ya, Reset!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Jika user klik "Ya", submit form reset password
                button.closest('form').submit();
            }
        });
    }

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