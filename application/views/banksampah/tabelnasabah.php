<div id="nasabah-table-container" class="container-fluid pt-4 px-4">
    
    <div class="row mb-4 align-items-center">
        <div class="col-lg-6">
            <h3 class="fs-4 mb-0 fw-bold text-dark">Data Mitra</h3>
            <p class="text-muted mb-0">Kelola informasi lengkap mitra daur ulang.</p>
        </div>
        <div class="col-lg-6 mt-3 mt-lg-0">
            <div class="d-flex flex-wrap gap-2 justify-content-start justify-content-lg-end">
                <button type="button" class="btn btn-success px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#tambahNasabahModal">
                    <i class="fas fa-plus me-1"></i> Tambah Mitra
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
    </div>

    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-4">
            <form action="<?= base_url() ?>dashboard/loadNasabah" method="post">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                <div class="input-group shadow-sm rounded">
                    <span class="input-group-text bg-light border-0"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" class="form-control border-0 bg-light py-2" placeholder="Cari username atau email mitra..." id="keyword" name="keyword" value="<?= isset($_SESSION['keyword_nasabah']) ? $_SESSION['keyword_nasabah'] : '' ?>">
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
                                    <div class="d-flex justify-content-center gap-1">
                                        <button type="button" class="btn btn-sm btn-outline-primary px-3 rounded-pill" data-bs-toggle="modal" data-bs-target="#exampleModal" 
                                            data-id="<?php echo $key['id_user'] ?>" data-profile="<?php echo $key['profile'] ?>" 
                                            data-nama="<?php echo $key['nama_lengkap'] ?>" 
                                            data-tempat-lahir="<?php echo $key['tempat_lahir'] ?>" 
                                            data-tanggal-lahir="<?php echo !empty($key['tanggal_lahir']) ? date('d M Y', strtotime($key['tanggal_lahir'])) : '-'; ?>" 
                                            data-alamat="<?php echo $key['alamat'] ?>" 
                                            data-email="<?php echo $key['email'] ?>" 
                                            data-telepon="<?php echo $key['notelp'] ?>">
                                            <i class="fas fa-info-circle me-1"></i> Detail
                                        </button>
                                        
                                        <button type="button" class="btn btn-sm btn-outline-warning px-3 rounded-pill" data-bs-toggle="modal" data-bs-target="#editNasabahModal" 
                                            data-id="<?php echo $key['id_user'] ?>"
                                            data-username="<?php echo $key['username'] ?>"
                                            data-nama="<?php echo $key['nama_lengkap'] ?>" 
                                            data-tempat-lahir="<?php echo $key['tempat_lahir'] ?>" 
                                            data-tanggal-lahir="<?php echo $key['tanggal_lahir']; ?>" 
                                            data-alamat="<?php echo $key['alamat'] ?>" 
                                            data-email="<?php echo $key['email'] ?>" 
                                            data-telepon="<?php echo $key['notelp'] ?>">
                                            <i class="fas fa-edit me-1"></i> Edit
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger px-3 rounded-pill" onclick="confirmReset('<?php echo $key['id_user'] ?>', '<?php echo $key['username'] ?>')">
                                            <i class="fas fa-key me-1"></i> Reset
                                        </button>
                                    </div>
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
                <h1 class="modal-title fs-5 fw-bold ms-2 mt-2">Tambah Mitra Baru</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4">
                <form id="formTambahNasabah" action="<?= base_url('dashboard/tambahnasabah') ?>" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                    <div class="row bg-light p-3 rounded-3 mb-3">
                        <h6 class="fw-bold text-dark mb-3">Informasi Akun</h6>
                        <div class="col-md-6 mb-3">
                            <label for="username" class="form-label fw-medium text-muted small">Username <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Buat username" value="<?= set_value('username') ?>" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <h6 class="fw-bold text-dark mb-3">Data Diri</h6>
                        <div class="col-md-12 mb-3">
                            <label for="nama_lengkap" class="form-label fw-medium text-muted small">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" placeholder="Masukkan nama lengkap" value="<?= set_value('nama_lengkap') ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label fw-medium text-muted small">Email (Opsional)</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="contoh@email.com" value="<?= set_value('email') ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="notelp" class="form-label fw-medium text-muted small">Nomor HP (Opsional)</label>
                            <input type="number" class="form-control" id="notelp" name="notelp" placeholder="08xxxxxx" value="<?= set_value('notelp') ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tempat_lahir" class="form-label fw-medium text-muted small">Tempat Lahir <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" placeholder="Kota kelahiran" value="<?= set_value('tempat_lahir') ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tanggal_lahir" class="form-label fw-medium text-muted small">Tanggal Lahir <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" value="<?= set_value('tanggal_lahir') ?>" required>
                        </div>
                        <div class="col-md-12 mb-4">
                            <label for="alamat" class="form-label fw-medium text-muted small">Alamat Lengkap <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="2" placeholder="Masukkan alamat lengkap" required><?= set_value('alamat') ?></textarea>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-2">
                        <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-primary px-4 fw-bold" onclick="confirmTambah()">Simpan Data Mitra</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editNasabahModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom-0 pb-0">
                <h1 class="modal-title fs-5 fw-bold ms-2 mt-2">Edit Data Mitra</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4">
                <form id="formEditNasabah" action="<?= base_url('dashboard/editnasabah') ?>" method="post">
                    <input type="hidden" id="edit_id_user" name="id_user">
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

                    <div class="row bg-light p-3 rounded-3 mb-3">
                        <h6 class="fw-bold text-dark mb-3">Informasi Akun</h6>
                        <div class="col-md-6 mb-3">
                            <label for="edit_username" class="form-label fw-medium text-muted small">Username <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_username" name="username" placeholder="Username" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_password" class="form-label fw-medium text-muted small">Password Baru (Opsional)</label>
                            <input type="password" class="form-control" id="edit_password" name="password" placeholder="Kosongkan jika tidak ingin ganti password">
                        </div>
                    </div>
                    
                    <div class="row">
                        <h6 class="fw-bold text-dark mb-3">Data Diri</h6>
                        <div class="col-md-12 mb-3">
                            <label for="edit_nama_lengkap" class="form-label fw-medium text-muted small">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_nama_lengkap" name="nama_lengkap" placeholder="Nama lengkap" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_email" class="form-label fw-medium text-muted small">Email (Opsional)</label>
                            <input type="email" class="form-control" id="edit_email" name="email" placeholder="contoh@email.com">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_notelp" class="form-label fw-medium text-muted small">Nomor HP (Opsional)</label>
                            <input type="number" class="form-control" id="edit_notelp" name="notelp" placeholder="08xxxxxx">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_tempat_lahir" class="form-label fw-medium text-muted small">Tempat Lahir <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_tempat_lahir" name="tempat_lahir" placeholder="Kota kelahiran" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_tanggal_lahir" class="form-label fw-medium text-muted small">Tanggal Lahir <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="edit_tanggal_lahir" name="tanggal_lahir" required>
                        </div>
                        <div class="col-md-12 mb-4">
                            <label for="edit_alamat" class="form-label fw-medium text-muted small">Alamat Lengkap <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="edit_alamat" name="alamat" rows="2" placeholder="Alamat lengkap" required></textarea>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-2">
                        <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-primary px-4 fw-bold" onclick="confirmEdit()">Simpan Perubahan</button>
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
                <h1 class="modal-title fs-5 fw-bold ms-2 mt-2">Import Excel Data Mitra</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 pb-4">
                <form id="formImportNasabah" action="<?= base_url('dashboard/importnasabah') ?>" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
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
    <div class="modal-dialog modal-dialog-centered modal-md"> <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom-0 pb-0">
                <h1 class="modal-title fs-5 fw-bold ms-2 mt-2">Profil & Keuangan Mitra</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <div class="mb-3">
                    <img id="modal-profile-img" src="" alt="Profile Image" class="rounded-circle shadow-sm border" style="width: 100px; height: 100px; object-fit: cover;">
                </div>
                <h4 id="modal-nama" class="fw-bold mb-1"></h4>
                <p id="modal-email" class="text-primary fw-medium small mb-3"></p>
                
                <div class="bg-success text-white rounded-3 p-3 mb-3 text-center shadow-sm">
                    <small class="opacity-75 d-block fw-medium small text-uppercase tracking-wider">Total Saldo Tabungan</small>
                    <h3 id="modal-saldo" class="fw-shadow mb-0 fw-bold">Rp 0</h3>
                </div>
                
                <div class="row text-start bg-light rounded-3 p-3 g-2 mb-3">
                    <div class="col-6">
                        <small class="text-muted d-block small fw-medium">Tempat Lahir</small>
                        <span id="modal-tempat-lahir" class="fw-bold text-dark small"></span>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block small fw-medium">Tanggal Lahir</small>
                        <span id="modal-tanggal-lahir" class="fw-bold text-dark small"></span>
                    </div>
                    <div class="col-12 border-top pt-2 mt-2">
                        <small class="text-muted d-block small fw-medium">Nomor Telepon</small>
                        <span id="modal-telepon" class="fw-bold text-dark small"></span>
                    </div>
                    <div class="col-12 border-top pt-2 mt-2">
                        <small class="text-muted d-block small fw-medium">Alamat Lengkap</small>
                        <span id="modal-alamat" class="fw-bold text-dark small"></span>
                    </div>
                </div>

                <div class="text-start">
                    <h6 class="fw-bold text-dark mb-2 small"><i class="fas fa-history me-2 text-muted"></i>5 Transaksi Terakhir</h6>
                    <div class="table-responsive rounded-2 border">
                        <table class="table table-sm table-hover align-middle mb-0 text-center small" style="font-size: 12px;">
                            <thead class="table-light text-muted">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                    <th>Nominal</th>
                                </tr>
                            </thead>
                            <tbody id="modal-riwayat-body">
                                </tbody>
                        </table>
                    </div>
                </div>

            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light w-100 fw-bold" data-bs-dismiss="modal">Tutup Detail</button>
            </div>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // JS Untuk memuat data ke Modal Detail
    // JS Untuk memuat data ke Modal Detail
    // JS Untuk memuat data profil (Data Diri) dan AJAX (Keuangan) ke Modal Detail
    $('#exampleModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var modal = $(this);
        
        var idUser = button.data('id'); // Ambil ID User nasabah
        var namaNasabah = button.data('nama');
        var profileImg = button.data('profile');
        
        // 1. Set data profil dasar (Sama seperti kodemu yang lama)
        var defaultAvatar = 'https://ui-avatars.com/api/?name=' + encodeURIComponent(namaNasabah) + '&background=random&color=fff&size=100';
        var profileImageSrc = (profileImg && profileImg !== '') ? '<?= base_url('uploads/profile/'); ?>' + profileImg : defaultAvatar;

        var imgElement = modal.find('#modal-profile-img');
        imgElement.attr('src', profileImageSrc);
        imgElement.attr('onerror', "this.onerror=null; this.src='" + defaultAvatar + "';");

        modal.find('#modal-nama').text(namaNasabah);
        modal.find('#modal-tempat-lahir').text(button.data('tempat-lahir'));
        modal.find('#modal-tanggal-lahir').text(button.data('tanggal-lahir'));
        modal.find('#modal-alamat').text(button.data('alamat'));
        modal.find('#modal-email').text(button.data('email'));
        modal.find('#modal-telepon').text(button.data('telepon'));

        // 2. Tampilkan efek Loading sebelum data AJAX masuk
        modal.find('#modal-saldo').text('Memuat Saldo...');
        modal.find('#modal-riwayat-body').html('<tr><td colspan="3" class="text-center text-muted py-3"><i class="fas fa-spinner fa-spin me-2"></i>Mengambil data transaksi...</td></tr>');

        // 3. JALANKAN AJAX UNTUK AMBIL SALDO & TRANSAKSI REAL-TIME
        $.ajax({
            url: '<?= base_url("dashboard/getDetailNasabahAjax") ?>',
            type: 'GET',
            data: { id: idUser },
            dataType: 'json',
            success: function(response) {
                // Tampilkan total saldo hasil hitung dinamis
                modal.find('#modal-saldo').text(response.saldo_format);
                
                // Susun baris tabel transaksi
                var htmlRows = '';
                if(response.riwayat.length > 0) {
                    response.riwayat.forEach(function(item) {
                        // Tentukan badge setor / tarik
                        var badge = item.debit > 0 
                            ? '<span class="badge bg-success bg-opacity-10 text-success px-2 py-1 rounded">Setor</span>' 
                            : '<span class="badge bg-danger bg-opacity-10 text-danger px-2 py-1 rounded">Tarik</span>';
                        
                        // Tentukan nominal (+ / -) dan warna teks
                        var nominal = item.debit > 0 ? '+ ' + item.debit_final_format : '- ' + item.kredit_format;
                        var warnaTeks = item.debit > 0 ? 'text-success' : 'text-danger';

                        htmlRows += '<tr>' +
                            '<td class="text-muted">' + item.tgl_format + '</td>' +
                            '<td>' + badge + '</td>' +
                            '<td class="fw-bold ' + warnaTeks + '">' + nominal + '</td>' +
                            '</tr>';
                    });
                } else {
                    htmlRows = '<tr><td colspan="3" class="text-center text-muted py-3">Belum ada riwayat transaksi</td></tr>';
                }
                // Masukkan data ke dalam tabel modal
                modal.find('#modal-riwayat-body').html(htmlRows);
            },
            error: function() {
                modal.find('#modal-saldo').text('Gagal Memuat');
                modal.find('#modal-riwayat-body').html('<tr><td colspan="3" class="text-center text-danger py-3"><i class="fas fa-exclamation-triangle me-2"></i>Gagal memuat riwayat keuangan.</td></tr>');
            }
        });
    });

    // JS Untuk memuat data otomatis ke dalam Modal Edit Nasabah
    $('#editNasabahModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var modal = $(this);
        
        // Memasukkan nilai data- ke dalam kolom input masing-masing
        modal.find('#edit_id_user').val(button.data('id'));
        modal.find('#edit_username').val(button.data('username'));
        modal.find('#edit_nama_lengkap').val(button.data('nama'));
        modal.find('#edit_email').val(button.data('email'));
        modal.find('#edit_notelp').val(button.data('telepon'));
        modal.find('#edit_tempat_lahir').val(button.data('tempat-lahir'));
        modal.find('#edit_tanggal_lahir').val(button.data('tanggal-lahir'));
        modal.find('#edit_alamat').val(button.data('alamat'));
        
        // Selalu kosongkan kolom password saat modal dibuka
        modal.find('#edit_password').val('');
    });

    // --- SWEETALERT KONFIRMASI EDIT NASABAH ---
    function confirmEdit() {
        var form = document.getElementById('formEditNasabah');
        
        if (!form.checkValidity()) {
            form.reportValidity(); 
            return;
        }

        Swal.fire({
            title: 'Simpan Perubahan?',
            text: "Data mitra akan diperbarui di dalam sistem.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#0d6efd',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-save me-1"></i> Ya, Perbarui!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $('#formEditNasabah').submit();
            }
        });
    }

    // --- SWEETALERT KONFIRMASI TAMBAH NASABAH ---
    function confirmTambah() {
        var form = document.getElementById('formTambahNasabah');
        
        // Pengecekan otomatis! Jika ada field berlabel 'required' yang kosong, 
        // browser akan memunculkan peringatan otomatis tanpa perlu SweetAlert
        if (!form.checkValidity()) {
            form.reportValidity(); 
            return;
        }

        Swal.fire({
            title: 'Simpan Data Mitra?',
            text: "Pastikan data mitra sudah benar.",
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

    // --- SWEETALERT KONFIRMASI RESET PASSWORD NASABAH ---
    function confirmReset(id, username) {
        Swal.fire({
            title: 'Reset Password Mitra?',
            text: "Password @" + username + " akan dikembalikan menjadi default",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545', // Warna merah (danger)
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-key me-1"></i> Ya, Reset!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect ke controller dengan membawa ID nasabah lewat URL GET (Sama seperti fitur hapus artikelmu)
                window.location.href = "<?= site_url('dashboard/resetPasswordNasabah?id=') ?>" + id;
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