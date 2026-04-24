<div id="nasabah-table-container" class="container-fluid pt-4 px-4">
    
    <div class="row mb-4 align-items-center">
        <div class="col-lg-6">
            <h3 class="fs-4 mb-0 fw-bold text-dark">Data Nasabah</h3>
            <p class="text-muted mb-0">Kelola informasi lengkap nasabah bank sampah.</p>
        </div>
        <div class="col-lg-6 text-lg-end mt-3 mt-lg-0">
            <button type="button" class="btn btn-success px-4 shadow-sm me-2" data-bs-toggle="modal" data-bs-target="#tambahNasabahModal">
                <i class="fas fa-plus me-1"></i> Tambah Nasabah
            </button>
            <div class="btn-group shadow-sm">
                <a href="<?= base_url() ?>uploads/excel/template_banksampah.xlsx" class="btn btn-primary px-3">
                    <i class="fas fa-download me-1"></i> Template
                </a>
                <button type="button" class="btn btn-warning px-3" data-bs-toggle="modal" data-bs-target="#importNasabahModal">
                    <i class="fas fa-file-import me-1"></i> Import Excel
                </button>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-4">
            <form action="<?= base_url() ?>dashboard/loadNasabah" method="post">
                <div class="input-group shadow-sm rounded">
                    <span class="input-group-text bg-light border-0"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" class="form-control border-0 bg-light py-2" placeholder="Cari username atau email nasabah..." id="keyword" name="keyword" value="<?= isset($_SESSION['keyword_nasabah']) ? $_SESSION['keyword_nasabah'] : '' ?>">
                    <button type="submit" class="btn btn-primary px-4" id="submit" name="submit" value="search">Cari</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-muted">
                        <tr>
                            <th scope="col" class="ps-4 py-3">Username</th>
                            <th scope="col" class="py-3">Nama Lengkap</th>
                            <th scope="col" class="py-3 text-center">Tanggal Lahir</th>
                            <th scope="col" class="py-3">Email</th>
                            <th scope="col" class="pe-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        <?php foreach ($user->result_array() as $key) {
                            if ($key['role'] === 'admin') {
                                continue;
                            }
                        ?>
                            <tr>
                                <td class="ps-4 fw-bold text-primary">@<?php echo $key['username'] ?></td>
                                <td class="fw-medium text-dark"><?php echo $key['nama_lengkap'] ?></td>
                                <td class="text-center text-muted"><?php echo !empty($key['tanggal_lahir']) ? date('d M Y', strtotime($key['tanggal_lahir'])) : '-'; ?></td>
                                <td><?php echo $key['email'] ?></td>
                                <td class="pe-4 text-center">
                                    <button type="button" class="btn btn-sm btn-outline-primary px-3 rounded-pill" data-bs-toggle="modal" data-bs-target="#exampleModal" 
                                        data-profile="<?php echo $key['profile'] ?>" 
                                        data-nama="<?php echo $key['nama_lengkap'] ?>" 
                                        data-tempat-lahir="<?php echo $key['tempat_lahir'] ?>" 
                                        data-tanggal-lahir="<?php echo !empty($key['tanggal_lahir']) ? date('d M Y', strtotime($key['tanggal_lahir'])) : '-'; ?>" 
                                        data-alamat="<?php echo $key['alamat'] ?>" 
                                        data-email="<?php echo $key['email'] ?>" 
                                        data-telepon="<?php echo $key['notelp'] ?>">
                                        <i class="fas fa-info-circle me-1"></i> Detail
                                    </button>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end mb-4" id='pagination'>
        <?= $pagination ?>
    </div>
</div>

<div class="modal fade" id="tambahNasabahModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom-0 pb-0">
                <h1 class="modal-title fs-5 fw-bold ms-2 mt-2">Tambah Nasabah Baru</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4">
                <form id="formTambahNasabah" action="<?= base_url('dashboard/tambahnasabah') ?>" method="post" enctype="multipart/form-data">
                    <div class="row bg-light p-3 rounded-3 mb-3">
                        <h6 class="fw-bold text-dark mb-3">Informasi Akun</h6>
                        <div class="col-md-6 mb-3">
                            <label for="username" class="form-label fw-medium text-muted small">Username</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Buat username" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label fw-medium text-muted small">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Buat password" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <h6 class="fw-bold text-dark mb-3">Data Diri</h6>
                        <div class="col-md-12 mb-3">
                            <label for="nama_lengkap" class="form-label fw-medium text-muted small">Nama Lengkap</label>
                            <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" placeholder="Masukkan nama lengkap" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label fw-medium text-muted small">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="contoh@email.com">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="notelp" class="form-label fw-medium text-muted small">Nomor HP</label>
                            <input type="number" class="form-control" id="notelp" name="notelp" placeholder="08xxxxxx">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tempat_lahir" class="form-label fw-medium text-muted small">Tempat Lahir</label>
                            <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" placeholder="Kota kelahiran">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tanggal_lahir" class="form-label fw-medium text-muted small">Tanggal Lahir</label>
                            <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir">
                        </div>
                        <div class="col-md-12 mb-4">
                            <label for="alamat" class="form-label fw-medium text-muted small">Alamat Lengkap</label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="2" placeholder="Masukkan alamat lengkap"></textarea>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-2">
                        <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-primary px-4 fw-bold" onclick="confirmTambah()">Simpan Nasabah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="importNasabahModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom-0 pb-0">
                <h1 class="modal-title fs-5 fw-bold ms-2 mt-2">Import Excel Nasabah</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 pb-4">
                <form id="formImportNasabah" action="<?= base_url('dashboard/importnasabah') ?>" method="post" enctype="multipart/form-data">
                    <div class="mb-4 mt-2">
                        <label for="excel_nasabah" class="form-label fw-medium text-muted small">Pilih File (.xlsx)</label>
                        <input type="file" class="form-control" id="excel_nasabah" name="excel_nasabah" required>
                    </div>
                    <div class="d-grid">
                        <button type="button" class="btn btn-warning fw-bold text-dark" onclick="confirmImport()">Mulai Import Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom-0 pb-0">
                <h1 class="modal-title fs-5 fw-bold ms-2 mt-2">Profil Nasabah</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <div class="mb-4">
                    <img id="modal-profile-img" src="" alt="Profile Image" class="rounded-circle shadow-sm border" style="width: 120px; height: 120px; object-fit: cover;">
                </div>
                <h4 id="modal-nama" class="fw-bold mb-1"></h4>
                <p id="modal-email" class="text-primary fw-medium mb-4"></p>
                
                <div class="row text-start bg-light rounded-3 p-3 g-3">
                    <div class="col-6">
                        <small class="text-muted d-block fw-medium">Tempat Lahir</small>
                        <span id="modal-tempat-lahir" class="fw-bold text-dark"></span>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block fw-medium">Tanggal Lahir</small>
                        <span id="modal-tanggal-lahir" class="fw-bold text-dark"></span>
                    </div>
                    <div class="col-12 border-top pt-3">
                        <small class="text-muted d-block fw-medium">Nomor Telepon</small>
                        <span id="modal-telepon" class="fw-bold text-dark"></span>
                    </div>
                    <div class="col-12 border-top pt-3">
                        <small class="text-muted d-block fw-medium">Alamat Lengkap</small>
                        <span id="modal-alamat" class="fw-bold text-dark"></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light w-100 fw-bold" data-bs-dismiss="modal">Tutup Profil</button>
            </div>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // JS Untuk memuat data ke Modal Detail
    // JS Untuk memuat data ke Modal Detail
    $('#exampleModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var modal = $(this);
        
        var namaNasabah = button.data('nama');
        var profileImg = button.data('profile');
        
        // 1. Buat default avatar otomatis dari inisial nama (warna acak & elegan)
        var defaultAvatar = 'https://ui-avatars.com/api/?name=' + encodeURIComponent(namaNasabah) + '&background=random&color=fff&size=120';
        
        // 2. Cek apakah ada nama file foto di database
        var profileImageSrc = (profileImg && profileImg !== '') 
                              ? '<?= base_url('uploads/profile/'); ?>' + profileImg 
                              : defaultAvatar;

        // 3. Pasang foto ke tag <img>, dan gunakan onerror jika file lokalnya hilang/rusak
        var imgElement = modal.find('#modal-profile-img');
        imgElement.attr('src', profileImageSrc);
        imgElement.attr('onerror', "this.onerror=null; this.src='" + defaultAvatar + "';");

        // Isi data teks lainnya
        modal.find('#modal-nama').text(namaNasabah);
        modal.find('#modal-tempat-lahir').text(button.data('tempat-lahir'));
        modal.find('#modal-tanggal-lahir').text(button.data('tanggal-lahir'));
        modal.find('#modal-alamat').text(button.data('alamat'));
        modal.find('#modal-email').text(button.data('email'));
        modal.find('#modal-telepon').text(button.data('telepon'));
    });

    // --- SWEETALERT KONFIRMASI TAMBAH NASABAH ---
    function confirmTambah() {
        // Validasi simpel: pastikan username dan nama terisi
        if($('#username').val() == '' || $('#nama_lengkap').val() == '' || $('#password').val() == ''){
            Swal.fire('Oops!', 'Username, Password, dan Nama Lengkap wajib diisi!', 'warning');
            return;
        }

        Swal.fire({
            title: 'Simpan Nasabah?',
            text: "Pastikan data nasabah sudah benar.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#0d6efd',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-save me-1"></i> Ya, Simpan!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $('#formTambahNasabah').submit(); // Submit form jika "Ya"
            }
        });
    }

    // --- SWEETALERT KONFIRMASI IMPORT EXCEL ---
    function confirmImport() {
        if($('#excel_nasabah').val() == ''){
            Swal.fire('Oops!', 'Silakan pilih file Excel terlebih dahulu!', 'warning');
            return;
        }

        Swal.fire({
            title: 'Import Data Excel?',
            text: "Pastikan format tabel Excel sudah sesuai dengan template.",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#ffc107',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-file-import me-1"></i> Ya, Import!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $('#formImportNasabah').submit(); // Submit form jika "Ya"
            }
        });
    }

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