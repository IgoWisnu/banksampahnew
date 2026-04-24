<div id="sampah-table-container" class="container-fluid pt-4 px-4">
    <div class="row mb-4 align-items-center">
        <div class="col-lg-6">
            <h3 class="fs-4 mb-0 fw-bold text-dark">Data Master Sampah</h3>
            <p class="text-muted mb-0">Kelola kategori, nama, dan patokan harga sampah per Kg.</p>
        </div>
        <div class="col-lg-6 text-lg-end mt-3 mt-lg-0">
            <button type="button" class="btn btn-success px-4 shadow-sm me-2" data-bs-toggle="modal" data-bs-target="#tambahSampahModal" onclick="document.getElementById('formTambahSampah').reset()">
                <i class="fas fa-plus me-1"></i> Tambah Sampah
            </button>
            <!-- <div class="btn-group shadow-sm">
                <a href="<?=base_url()?>uploads/excel/template_banksampah.xlsx" class="btn btn-primary px-3">
                    <i class="fas fa-download me-1"></i> Template
                </a>
                <button type="button" class="btn btn-warning px-3" data-bs-toggle="modal" data-bs-target="#importSampahModal">
                    <i class="fas fa-file-import me-1"></i> Import Excel
                </button>
            </div> -->
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-4">
            <form action="<?=base_url()?>dashboard/loadSampah" method="post">
                <div class="input-group shadow-sm rounded">
                    <span class="input-group-text bg-light border-0"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" class="form-control border-0 bg-light py-2" placeholder="Cari jenis atau kategori sampah..." id="keyword" name="keyword" value="<?= isset($_SESSION['keyword_sampah']) ? $_SESSION['keyword_sampah'] : '' ?>">
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
                            <th class="ps-4 py-3" width="50">No</th>
                            <th class="py-3">Jenis Sampah</th>
                            <th class="py-3">Kategori</th>
                            <th class="py-3">Sub Kategori</th>
                            <th class="py-3">Harga/Kg</th>
                            <th class="pe-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        <?php if($sampah->num_rows() > 0): ?>
                            <?php foreach ($sampah->result_array() as $key): ?>
                            <tr>
                                <td class="ps-4 fw-medium text-dark"><?=$key['id'] ?></td>
                                <td class="fw-bold text-primary"><?=$key['jenis_sampah'] ?></td>
                                <td><?=$key['kategori_sampah'] ?></td>
                                <td><?=$key['sub_kategori_sampah'] ?></td>
                                <td class="text-success fw-bold">Rp <?= number_format($key['harga_sampah'], 0, ',', '.') ?></td>
                                <td class="pe-4 text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <button class="btn btn-sm btn-outline-warning px-3 rounded-pill" 
                                                onclick="openEditModal('<?= $key['id'] ?>', '<?= addslashes($key['jenis_sampah']) ?>', '<?= addslashes($key['kategori_sampah']) ?>', '<?= addslashes($key['sub_kategori_sampah']) ?>', '<?= $key['harga_sampah'] ?>')">
                                            <i class="fas fa-edit me-1"></i> Edit
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger px-3 rounded-pill" onclick="confirmDelete(<?= $key['id'] ?>)">
                                            <i class="fas fa-trash me-1"></i> Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="6" class="text-center py-4 text-muted fst-italic">Data sampah tidak ditemukan.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end mb-4" id='pagination'>
        <?=$pagination ?>
    </div>
</div>

<div class="modal fade" id="tambahSampahModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom-0 pb-0">
                <h1 class="modal-title fs-5 fw-bold ms-2 mt-2">Tambah Jenis Sampah</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4">
                <form id="formTambahSampah" action="<?= base_url('dashboard/tambahSampah') ?>" method="post">
                    <div class="mb-3">
                        <label class="form-label fw-medium text-muted small">Nama Jenis Sampah</label>
                        <input type="text" class="form-control" id="add_jenis" name="jenis_sampah" required placeholder="Cth: Botol Plastik">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium text-muted small">Kategori Utama</label>
                        <input type="text" class="form-control" id="add_kategori" name="kategori_sampah" required placeholder="Cth: Plastik">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium text-muted small">Sub Kategori</label>
                        <input type="text" class="form-control" id="add_sub" name="sub_kategori_sampah" required placeholder="Cth: PET">
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-medium text-muted small">Harga Per Kg (Rp)</label>
                        <input type="text" class="form-control fw-bold text-success fs-5 bg-light" id="add_harga_tampil" onkeyup="formatRupiahInput(this, 'add_harga_raw')" required placeholder="0">
                        <input type="hidden" name="harga_sampah" id="add_harga_raw">
                    </div>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-2">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-success px-4" onclick="confirmTambah()">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editSampahModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom-0 pb-0">
                <h1 class="modal-title fs-5 fw-bold ms-2 mt-2">Edit Jenis Sampah</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4">
                <form id="formEditSampah" action="<?= base_url('dashboard/updateSampah') ?>" method="post">
                    <input type="hidden" name="id_sampah" id="edit_id">
                    
                    <div class="mb-3">
                        <label class="form-label fw-medium text-muted small">Nama Jenis Sampah</label>
                        <input type="text" class="form-control" name="jenis_sampah" id="edit_jenis" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium text-muted small">Kategori Utama</label>
                        <input type="text" class="form-control" name="kategori_sampah" id="edit_kategori" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium text-muted small">Sub Kategori</label>
                        <input type="text" class="form-control" name="sub_kategori_sampah" id="edit_sub" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-medium text-muted small">Harga Per Kg (Rp)</label>
                        <input type="text" class="form-control fw-bold text-success fs-5 bg-light" id="edit_harga_tampil" onkeyup="formatRupiahInput(this, 'edit_harga_raw')" required>
                        <input type="hidden" name="harga_sampah" id="edit_harga_raw">
                    </div>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-2">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-warning px-4 text-dark fw-bold" onclick="confirmEdit()">Update Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="importSampahModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom-0 pb-0">
                <h1 class="modal-title fs-5 fw-bold ms-2 mt-2">Import Excel Sampah</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4 pb-4">
                <form id="formImportSampah" action="<?= base_url('dashboard/importSampah') ?>" method="post" enctype="multipart/form-data">
                    <div class="mb-4 mt-2">
                        <label class="form-label fw-medium text-muted small">Pilih File Excel (.xlsx)</label>
                        <input type="file" class="form-control" id="excel_sampah" name="excel_sampah" accept=".xlsx" required>
                    </div>
                    <div class="d-grid">
                        <button type="button" class="btn btn-warning fw-bold text-dark" onclick="confirmImport()">Mulai Import Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // --- FUNGSI FORMAT RUPIAH ---
    function formatRupiahInput(inputElement, rawInputId) {
        // Hapus semua selain angka
        let value = inputElement.value.replace(/[^,\d]/g, '').toString();
        
        // Simpan angka murni ke input hidden untuk dikirim ke database
        document.getElementById(rawInputId).value = value;
        
        // Buat format titik
        let split = value.split(',');
        let sisa = split[0].length % 3;
        let rupiah = split[0].substr(0, sisa);
        let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            let separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }
        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        
        // Tampilkan di input visual
        inputElement.value = rupiah;
    }

    // --- FUNGSI ISI MODAL EDIT ---
    function openEditModal(id, jenis, kategori, sub, harga) {
        $('#edit_id').val(id);
        $('#edit_jenis').val(jenis);
        $('#edit_kategori').val(kategori);
        $('#edit_sub').val(sub);
        
        // Set raw data
        $('#edit_harga_raw').val(harga);
        
        // Set format rupiah untuk tampilan
        let formattedHarga = harga.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        $('#edit_harga_tampil').val(formattedHarga);

        $('#editSampahModal').modal('show');
    }

    // --- SWEETALERT KONFIRMASI TAMBAH ---
    function confirmTambah() {
        if($('#add_jenis').val() == '' || $('#add_harga_raw').val() == ''){
            Swal.fire('Oops!', 'Nama dan Harga Sampah wajib diisi!', 'warning');
            return;
        }
        Swal.fire({
            title: 'Simpan Data?',
            text: "Pastikan data jenis sampah sudah benar.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-save me-1"></i> Simpan!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) $('#formTambahSampah').submit();
        });
    }

    // --- SWEETALERT KONFIRMASI EDIT ---
    function confirmEdit() {
        if($('#edit_jenis').val() == '' || $('#edit_harga_raw').val() == ''){
            Swal.fire('Oops!', 'Nama dan Harga Sampah tidak boleh kosong!', 'warning');
            return;
        }
        Swal.fire({
            title: 'Update Data?',
            text: "Perubahan akan disimpan ke database.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#ffc107',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-save me-1"></i> Update!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) $('#formEditSampah').submit();
        });
    }

    // --- SWEETALERT KONFIRMASI HAPUS ---
    function confirmDelete(id) {
        Swal.fire({
            title: 'Hapus Jenis Sampah?',
            text: "Data yang dihapus tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-trash me-1"></i> Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Sesuai dengan controller deleteSampah yang menggunakan ?id_sampah=
                window.location.href = "<?= site_url('dashboard/deleteSampah?id_sampah=') ?>" + id;
            }
        });
    }

    // --- SWEETALERT KONFIRMASI IMPORT ---
    function confirmImport() {
        if($('#excel_sampah').val() == ''){
            Swal.fire('Oops!', 'Silakan pilih file Excel terlebih dahulu!', 'warning');
            return;
        }
        Swal.fire({
            title: 'Import Data Excel?',
            text: "Pastikan format sudah sesuai template.",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#ffc107',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Import!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) $('#formImportSampah').submit();
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