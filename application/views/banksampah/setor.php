<div class="container-fluid pt-4 px-4">
    <div class="row mb-4 align-items-center">
        <div class="col-lg-12">
            <h3 class="fs-4 mb-0 fw-bold text-dark">Setor Sampah</h3>
            <p class="text-muted mb-0">Cari nasabah dan input detail jenis sampah yang disetorkan.</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-4">
            <label class="form-label fw-medium text-muted small">Pencarian Nasabah</label>
            <div class="input-group shadow-sm rounded">
                <span class="input-group-text bg-light border-0"><i class="fas fa-search text-muted"></i></span>
                <input type="text" name="search_text" id="search_text" placeholder="Ketik Username atau Nama Nasabah..."
                    class="form-control border-0 bg-light py-2" />
            </div>
            <div class="result mt-3" id="result"></div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-4">
            <form action="<?= base_url() ?>setorSampah/kalkulasi" method="post" id="add_form">

                <h6 class="fw-bold mb-3 text-dark">Data Nasabah Terpilih</h6>
                <div class="row g-3 mb-4 bg-light p-3 rounded align-items-center mx-0">
                    <div class="col-md-6">
                        <label for="userid" class="form-label fw-medium text-muted small">ID User</label>
                        <input type="text" name="id_user" id="userid" class="form-control bg-white border-0 shadow-sm"
                            readonly placeholder="-">
                    </div>
                    <div class="col-md-6">
                        <label for="username" class="form-label fw-medium text-muted small">Username</label>
                        <input type="text" name="username" id="username"
                            class="form-control bg-white border-0 shadow-sm" readonly placeholder="-">
                    </div>
                </div>

                <h6 class="fw-bold mb-3 text-dark">Daftar Setoran Sampah</h6>

                <div id="show_item"></div>

                <div class="row my-3">
                    <div class="col-12">
                        <button class="btn btn-outline-success px-4 rounded-pill shadow-sm" name="add_btn" id="add_btn">
                            <i class="fas fa-plus me-1"></i> Tambah Baris Sampah
                        </button>
                    </div>
                </div>

                <hr class="my-4">
                <div class="text-end">
                    <button type="button" class="btn btn-primary px-5 py-2 rounded-pill shadow-sm fw-bold"
                        onclick="confirmSetor()">
                        <i class="fas fa-balance-scale me-1"></i> Proses Setoran
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // --- FUNGSI FORMAT RUPIAH ---
    function formatRupiah(angka) {
        var number_string = angka.toString().replace(/[^,\d]/g, ''),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            var separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }
        return rupiah;
    }

    $('document').ready(function () {
        // Pencarian Nasabah
        $('#search_text').on('keyup', function () {
            $.ajax({
                url: '<?= base_url('setorSampah/setor') ?>',
                type: 'POST',
                data: { cari: $(this).val() },
                success: function (data) {
                    if (data.error) console.error('Error:', data.error);
                    else $('#result').html(data);
                }
            });
        });

        // Kosongkan form berat & harga kalau jenis sampah diubah
        $(document).on('change', '.id_jenis_sampah', function () {
            var row = $(this).closest('.row');
            row.find('.berat_sampah').val('');
            row.find('.harga_sampah_tampil').val('');
            row.find('.harga_sampah_raw').val('');
        });

        // Kalkulasi Harga Otomatis Saat Berat Diketik
        $(document).on('keyup', '.berat_sampah', function () {
            var beratSampah = $(this).val();
            var row = $(this).closest('.row');
            var idSampah = row.find('.id_jenis_sampah').val();

            var hargaTampil = row.find('.harga_sampah_tampil');
            var hargaRaw = row.find('.harga_sampah_raw');

            $.ajax({
                url: '<?= base_url('setorSampah/hitungHarga') ?>',
                type: 'POST',
                data: { berat: beratSampah, id: idSampah },
                success: function (response) {
                    // Update tampilan dgn titik (Rupiah) & simpan raw
                    hargaTampil.val(formatRupiah(response));
                    hargaRaw.val(response);
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });

        // Event Klik Nasabah
        $('#result').on('click', '.result-item', function () {
            var userId = $(this).data('user-id');
            var username = $(this).data('username');

            $('#userid').val(userId);
            $('#username').val(username);

            document.getElementById('userid').style.background = '#d1e7dd';
            document.getElementById('username').style.background = '#d1e7dd';

            $('#result').html('');
        });

        // Tambah Baris Dinamis
        $("#add_btn").click(function (e) {
            e.preventDefault();
            $("#show_item").prepend(`
                <div class="row g-2 mb-3 align-items-end p-3 border rounded shadow-sm bg-white" id="show_item">
                    <div class="col-md-5">
                        <label class="form-label fw-medium text-muted small">Jenis Sampah</label>
                        <select name="id_jenis_sampah[]" class="form-select id_jenis_sampah border-0 bg-light shadow-sm py-2">
                            <option value="" disabled selected>-- Pilih Jenis --</option>
                            <?php foreach ($option->result_array() as $key) { ?>
                                <option value="<?= $key['id'] ?>"><?= $key['jenis_sampah'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-medium text-muted small">Berat (Kg)</label>
                        <input type="number" name="berat_sampah[]" step="0.01" class="form-control berat_sampah border-0 bg-light shadow-sm py-2" placeholder="Cth: 1.5">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-medium text-muted small">Total Harga</label>
                        <input type="text" class="form-control harga_sampah_tampil border-0 text-success fw-bold shadow-sm py-2" readonly placeholder="Otomatis (Rp)">
                        <input type="hidden" name="harga_sampah[]" class="harga_sampah_raw">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-medium text-muted small">Total Harga</label>
                     
                    </div>
                    <div class="col-md-1 text-center">
                        <button class="btn btn-danger w-100 remove_btn shadow-sm py-2" title="Hapus Baris"><i class="fas fa-trash"></i></button>
                    </div>
                </div>`);
        });

        $(document).on('click', '.remove_btn', function (e) {
            e.preventDefault();
            let row_item = $(this).closest('.row');
            $(row_item).remove();
        })
    });

    // --- SWEETALERT KONFIRMASI SETOR ---
    window.confirmSetor = function () {
        if ($('#userid').val() == '') {
            Swal.fire('Oops!', 'Silakan cari dan pilih nasabah terlebih dahulu.', 'warning');
            return;
        }

        Swal.fire({
            title: 'Proses Setoran?',
            text: "Pastikan data jenis dan berat sampah sudah ditimbang dengan benar!",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#0d6efd',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-check-circle me-1"></i> Ya, Proses!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $('#add_form').submit();
            }
        });
    };
    // --- Deteksi Flashdata untuk Notifikasi Sukses/Gagal (SweetAlert2) ---
    $(document).ready(function () {
        <?php if ($this->session->flashdata('success')): ?>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '<?= $this->session->flashdata('success'); ?>',
                timer: 3000,
                showConfirmButton: false
            });
        <?php elseif ($this->session->flashdata('failed')): ?>
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '<?= $this->session->flashdata('failed'); ?>'
            });
        <?php endif; ?>
    });
</script>