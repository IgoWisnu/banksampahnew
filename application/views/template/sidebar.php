<div class="d-flex" id="wrapper">
    <div class="bg-white" id="sidebar-wrapper">
        <div class="sidebar-heading text-center py-4 primary-text fs-4 fw-bold text-uppercase border-bottom">
            <img src="<?= base_url() ?>img/logo green.png?v=1" alt="Bank Sampah" class="me-2" style="width: 50px;"/>
            Bank Sampah
        </div>
        <div class="list-group list-group-flush my-3">
            <?php if ($this->session->userdata('role') == 'superadmin'): ?>
                <a href="<?= base_url() ?>superadmin" class="btn list-group-item list-group-item-action bg-transparent second-text active">
                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard</a>
                <a href="<?=base_url()?>superadmin/banjar" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                    <i class="fas fa-building me-2"></i>Data Banjar</a>
                <a href="<?=base_url()?>superadmin/admin" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                    <i class="fas fa-user-shield me-2"></i>Data Admin</a>
            <?php else: ?>
                <a href="<?= base_url() ?>dashboard/index" class="btn list-group-item list-group-item-action bg-transparent second-text active">
                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard</a>
                <a href="<?=base_url()?>dashboard/loadNasabah" id="nasabah-link" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                    <i class="fas fa-user fa-beat me-2"></i>Nasabah</a>
                <a href="<?=base_url()?>dashboard/loadBerita" id="berita-link" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                    <i class="fas fa-chart-line me-2"></i>Berita</a>
                <a href="<?=base_url()?>dashboard/loadTransaksi" id="transaksi-link" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                    <i class="fa fa-credit-card me-2"></i>Transaksi</a>
                <a href="<?=base_url()?>dashboard/loadSampah" id="sampah-link" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                    <i class="fa fa-trash me-2"></i>Sampah</a>
                <a href="<?=base_url()?>dashboard/loadRiwayatHarga" id="riwayat-link" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                    <i class="fa fa-history me-2"></i>Riwayat Harga</a>
                <a href="<?=base_url()?>setorSampah" id="setor-link" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                    <i class="fas fa-shopping-cart me-2"></i>Beli Sampah</a>
                <a href="<?=base_url()?>jualSampah" id="jual-link" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                    <i class="fas fa-truck-loading me-2"></i>Jual Sampah</a>
                <a href="<?=base_url()?>payment" id="payment-link" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                    <i class="fas fa-file-invoice-dollar me-2"></i>Payment Invoice</a>
                <a href="<?=base_url()?>generatepdf" id="pdf-link" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                    <i class="fas fa-file-pdf me-2"></i>Laporan PDF</a>
                <a href="<?=base_url()?>stok" id="stok-link" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
                    <i class="fas fa-boxes me-2"></i>Sistem Stok</a>
            <?php endif; ?>
            
            <a type="button" data-bs-toggle="modal" data-bs-target="#passwordModal" class="list-group-item list-group-item-action bg-transparent second-text fw-bold" style="cursor: pointer;">
                <i class="fas fa-key me-2"></i>Ubah Password
            </a>

            <!-- Setting Banjar hidden as requested -->

            <a type="button" onclick="logoutModal()" class="list-group-item list-group-item-action bg-transparent text-danger fw-bold" style="cursor: pointer;">
                <i class="fas fa-power-off me-2"></i>Logout
            </a>
        </div>
    </div>

    <div id="sidebar-overlay"></div>

    <?php if ($this->session->userdata('role') == 'admin'): 
        // Trik sakti CI3: Ambil data Banjar yang sedang login langsung di view agar tidak perlu edit semua method controller
        $CI =& get_instance();
        $my_banjar_id = $CI->session->userdata('banjar_id');
        $my_banjar = $CI->db->get_where('banjar', ['id' => $my_banjar_id])->row_array();
    ?>
    <div class="modal fade" id="settingBanjarModal" tabindex="-1" aria-labelledby="settingBanjarLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold" id="settingBanjarLabel">Setting Data Banjar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="<?= base_url('dashboard/updateBanjarAdmin') ?>" method="POST">
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                    <div class="modal-body px-4 pb-4">
                        <p class="text-muted small mb-4">Ubah nama operasional cabang Banjar Anda beserta persentase potongan margin tabungan.</p>
                        
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-medium">Nama Banjar <span class="text-danger">*</span></label>
                            <input type="text" name="nama_banjar" class="form-control fw-bold" value="<?= htmlspecialchars($my_banjar['nama'] ?? '') ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-medium">Margin Potongan Banjar (%) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="margin_value" step="0.01" min="0" max="100" class="form-control fw-bold text-success" value="<?= $my_banjar['margin_value'] ?? 0 ?>" required>
                                <span class="input-group-text bg-light fw-bold">%</span>
                            </div>
                            <div class="form-text text-warning small mt-1">
                                <i class="fas fa-exclamation-circle me-1"></i> Perubahan margin hanya berdampak pada transaksi <strong>Setor Sampah baru</strong> ke depannya.
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light px-4 rounded-pill" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary px-4 rounded-pill fw-bold">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h1 class="modal-title fs-5 fw-bold" id="exampleModalLabel">Logout Dashboard</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center pb-4">
                    <i class="fas fa-sign-out-alt fa-3x text-danger mb-3"></i>
                    <p class="mb-0 text-muted">Apakah Anda yakin ingin mengakhiri sesi dan keluar?</p>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger px-4" id="confirmDelete" onclick="logout()">Ya, Logout</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="passwordModal" tabindex="-1" aria-labelledby="passwordModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold" id="passwordModalLabel">Ubah Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="<?= base_url('auth/ubahPasswordAdmin') ?>" method="POST" id="formUbahPasswordAdmin">
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                    <div class="modal-body px-4 pb-4">
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-medium">Password Lama <span class="text-danger">*</span></label>
                            <input type="password" name="old_password" id="old_password_admin" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-medium">Password Baru <span class="text-danger">*</span></label>
                            <input type="password" name="new_password" id="new_password_admin" class="form-control" placeholder="Min. 8 karakter (Huruf & Angka)" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-medium">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                            <input type="password" name="confirm_password" id="confirm_password_admin" class="form-control" placeholder="Ketik ulang password baru" required>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light px-4 rounded-pill" data-bs-dismiss="modal">Batal</button>
                        <button type="button" onclick="prosesUbahPasswordAdmin()" class="btn btn-success px-4 rounded-pill">Simpan Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function prosesUbahPasswordAdmin() {
        var oldPass = document.getElementById('old_password_admin').value;
        var newPass = document.getElementById('new_password_admin').value;
        var confPass = document.getElementById('confirm_password_admin').value;

        if (oldPass === '' || newPass === '' || confPass === '') {
            Swal.fire('Form Belum Lengkap', 'Pastikan semua kolom password diisi.', 'warning');
            return;
        }

        // Regex: Minimal 8 karakter, mengandung setidaknya 1 huruf dan 1 angka
        var regex = /^(?=.*[A-Za-z])(?=.*\d).{8,}$/;

        if (!regex.test(newPass)) {
            Swal.fire('Format Tidak Sesuai!', 'Password baru harus minimal 8 karakter serta mengandung kombinasi huruf dan angka.', 'warning');
            return;
        }

        if (newPass !== confPass) {
            Swal.fire('Oops!', 'Konfirmasi password tidak cocok dengan password baru.', 'error');
            return;
        }

        // Jika semua validasi lolos, submit form
        document.getElementById('formUbahPasswordAdmin').submit();
    }

    // =========================================================
    // TAMBAHKAN BLOK INI UNTUK MENANGKAP FLASHDATA SWEETALERT
    // =========================================================
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
                title: 'Gagal!',
                text: '<?= $this->session->flashdata('failed'); ?>',
                confirmButtonColor: '#198754'
            });
        <?php endif; ?>
    });
</script>