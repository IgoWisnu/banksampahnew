<div id="berita-table-container" class="container-fluid pt-4 px-4">
    
    <div class="row mb-4 align-items-center">
        <div class="col-lg-6">
            <h3 class="fs-4 mb-0 fw-bold text-dark">Data Berita & Edukasi</h3>
            <p class="text-muted mb-0">Kelola informasi, artikel, dan berita untuk nasabah.</p>
        </div>
        <div class="col-lg-6 mt-3 mt-lg-0">
            <div class="d-flex justify-content-start justify-content-lg-end">
                <button type="button" class="btn btn-success px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#tambahBeritaModal">
                    <i class="fas fa-plus me-1"></i> Tambah Berita
                </button>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-muted">
                        <tr>
                            <th scope="col" class="ps-4 py-3" width="60">No</th>
                            <th scope="col" class="py-3">Judul Artikel</th>
                            <th scope="col" class="py-3">Thumbnail</th>
                            <th scope="col" class="py-3">Cuplikan Deskripsi</th>
                            <th scope="col" class="pe-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        <?php foreach ($berita->result_array() as $key) { ?>
                        <tr>
                            <td class="ps-4 fw-medium text-dark"><?php echo $key['id'] ?></td>
                            <td class="fw-bold text-primary"><?php echo $key['judul'] ?></td>
                            <td>
                                <img src="<?php echo base_url('uploads/' . $key['gambar']); ?>" alt="Gambar Berita" class="shadow-sm" style="width: 80px; height: 60px; object-fit: cover; border-radius: 6px;">
                            </td>
                            <td>
                                <span class="d-inline-block text-truncate text-muted" style="max-width: 250px;">
                                    <?php echo strip_tags($key['deskripsi']) ?>
                                </span>
                            </td>
                            <td class="pe-4 text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary px-3 rounded-pill" onclick="confirmBroadcastEmail(<?= $key['id']; ?>)">
                                        <i class="fas fa-envelope me-1"></i> Kirim Email
                                    </button>
                                    <button class="btn btn-sm btn-outline-warning px-3 rounded-pill" onclick="openEditModal('<?php echo $key['id']; ?>', '<?php echo addslashes($key['judul']); ?>', '<?php echo $key['gambar']; ?>', '<?php echo htmlspecialchars(addslashes($key['deskripsi'])); ?>')">
                                        <i class="fas fa-edit me-1"></i> Edit
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger px-3 rounded-pill" onclick="confirmDelete(<?= $key['id']; ?>)">
                                        <i class="fas fa-trash me-1"></i> Delete
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
        <?=$pagination ?>
    </div>
</div>

<div class="modal fade" id="tambahBeritaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom-0 pb-0">
                <h1 class="modal-title fs-5 fw-bold ms-2 mt-2">Tambah Berita Baru</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 pb-4">
                <form id="formTambahBerita" action="<?= base_url('dashboard/tambahberita') ?>" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="judulBerita" class="form-label fw-medium text-muted small">Judul Artikel</label>
                        <input type="text" class="form-control bg-light border-0 shadow-sm py-2" id="judulBerita" name="judulBerita" placeholder="Masukkan Judul Berita" required>
                    </div>
                    <div class="mb-3">
                        <label for="gambarBerita" class="form-label fw-medium text-muted small">Upload Thumbnail (Gambar)</label>
                        <input type="file" class="form-control bg-light border-0 shadow-sm py-2" id="gambarBerita" name="gambarBerita" accept="image/*" required>
                    </div>
                    <div class="mb-4">
                        <label for="deskripsiBerita" class="form-label fw-medium text-muted small">Isi Konten Berita</label>
                        <input type="hidden" id="deskripsiBerita" name="deskripsiBerita">
                        <div class="bg-white rounded border shadow-sm">
                            <div id="deskripsiQuilli" style="min-height: 200px;"></div>
                        </div>
                    </div>
                    <div class="mb-4 form-check ms-1">
                        <input type="checkbox" class="form-check-input" id="kirimEmail" name="kirim_email" value="1" checked>
                        <label class="form-check-label fw-medium text-dark small" for="kirimEmail">
                            Langsung masukkan ke antrean email nasabah saat berita diterbitkan
                        </label>
                    </div>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary px-4 fw-bold"><i class="fas fa-paper-plane me-1"></i> Terbitkan Berita</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editBeritaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom-0 pb-0">
                <h1 class="modal-title fs-5 fw-bold ms-2 mt-2">Edit Berita</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 pb-4">
                <form id="editForm" action="<?= base_url('dashboard/updateBerita') ?>" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="editBeritaId">
                    <input type="hidden" name="gambarBerita_existing" id="editGambarBeritaExisting">

                    <div class="mb-3">
                        <label for="editJudulBerita" class="form-label fw-medium text-muted small">Judul Artikel</label>
                        <input type="text" class="form-control bg-light border-0 shadow-sm py-2" id="editJudulBerita" name="judulBerita" placeholder="Masukkan Judul Berita" required>
                    </div>
                    
                    <div class="row mb-3 align-items-center bg-light rounded-3 p-3 mx-0">
                        <div class="col-md-9">
                            <label for="editGambarBerita" class="form-label fw-medium text-muted small">Ganti Thumbnail (Opsional)</label>
                            <input type="file" class="form-control bg-white border-0 shadow-sm py-2" id="editGambarBerita" name="gambarBerita" accept="image/*">
                        </div>
                        <div class="col-md-3 text-center mt-3 mt-md-0">
                            <small class="text-muted d-block mb-1">Gambar Saat Ini:</small>
                            <img id="editGambarPreview" class="rounded shadow-sm" style="width: 80px; height: 60px; object-fit: cover;" alt="Current Image">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="editDeskripsiBerita" class="form-label fw-medium text-muted small">Isi Konten Berita</label>
                        <input type="hidden" id="editDeskripsiBerita" name="deskripsiBerita">
                        <div class="bg-white rounded border shadow-sm">
                            <div id="editEditor" style="min-height: 200px;"></div>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning px-4 fw-bold text-dark"><i class="fas fa-save me-1"></i> Update Berita</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // --- Inisialisasi Quill Editor ---
    var quill = new Quill('#deskripsiQuilli', {theme: 'snow'});
    var editQuill = new Quill('#editEditor', {theme: 'snow'});

    // --- Sinkronisasi Quill sebelum form ditambah ---
    var modalForm = document.querySelector('#formTambahBerita');
    modalForm.onsubmit = function () {
        document.getElementById('deskripsiBerita').value = quill.root.innerHTML;
        return true; 
    };

    // --- Sinkronisasi Quill sebelum form diedit ---
    var editModalForm = document.querySelector('#editForm');
    editModalForm.addEventListener('submit', function () {
        document.getElementById('editDeskripsiBerita').value = editQuill.root.innerHTML;
        return true; 
    });

    // --- Fungsi Membuka Modal Edit dengan Data Terisi ---
    function openEditModal(id, judul, gambar, deskripsi) {
        document.getElementById('editBeritaId').value = id;
        document.getElementById('editJudulBerita').value = judul;
        document.getElementById('editGambarBeritaExisting').value = gambar;
        
        // Memperbaiki path URL gambar yang sebelumnya salah mengarah ke folder img/
        document.getElementById('editGambarPreview').src = '<?= base_url("uploads/") ?>' + gambar;
        
        editQuill.root.innerHTML = deskripsi;
        $('#editBeritaModal').modal('show');
    }
    
    function confirmBroadcastEmail(id) {
        Swal.fire({
            title: 'Kirim Email Massal?',
            text: "Berita ini akan dimasukkan ke antrean email seluruh nasabah aktif.",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#0d6efd',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-paper-plane me-1"></i> Ya, Antrekan!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Jalankan fungsi antrean email di controller
                window.location.href = "<?= site_url('dashboard/antrikanEmailBerita?id=') ?>" + id;
            }
        });
    }

    // --- Fungsi Konfirmasi Delete Menggunakan SweetAlert2 ---
    function confirmDelete(id) {
        Swal.fire({
            title: 'Hapus Artikel?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-trash me-1"></i> Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Arahkan ke link delete jika user klik "Ya"
                window.location.href = "<?= site_url('dashboard/deleteb?id=') ?>" + id;
            }
        });
    }

    // --- Deteksi Flashdata untuk SweetAlert2 ---
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