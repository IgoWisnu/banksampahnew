<div class="container-fluid pt-4 px-4">
    <div class="row mb-4 align-items-center">
        <div class="col-lg-12">
            <h3 class="fs-4 mb-0 fw-bold text-dark">Beli Sampah</h3>
            <p class="text-muted mb-0">Cari mitra dan input detail jenis sampah yang disetorkan.</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-4">
            <label class="form-label fw-medium text-muted small">Pencarian Mitra</label>
            <div class="input-group shadow-sm rounded">
                <span class="input-group-text bg-light border-0"><i class="fas fa-search text-muted"></i></span>
                <input type="text" name="search_text" id="search_text" placeholder="Ketik Username atau Nama Mitra..."
                    class="form-control border-0 bg-light py-2" />
            </div>
            <div class="result mt-3" id="result"></div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-4">
            <form action="<?= base_url() ?>setorSampah/kalkulasi" method="post" id="add_form">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

                <h6 class="fw-bold mb-3 text-dark">Data Mitra Terpilih</h6>
                <input type="hidden" name="status_pembayaran" id="status_pembayaran" value="Lunas">
                <div class="row g-3 mb-4 bg-light p-3 rounded align-items-center mx-0">
                    <div class="col-md-6">
                        <label for="userid" class="form-label fw-medium text-muted small">ID User</label>
                        <input type="text" name="id_user" id="userid" class="form-control bg-white border-0 shadow-sm"
                            readonly placeholder="-">
                    </div>
                    <div class="col-md-6">
                        <label for="username" class="form-label fw-medium text-muted small">Username Mitra/Pengepul</label>
                        <input type="text" name="username" id="username"
                            class="form-control bg-white border-0 shadow-sm" readonly placeholder="-">
                    </div>
                </div>

                <h6 class="fw-bold mb-3 text-dark">Daftar Beli / Setoran Sampah</h6>

                <!-- Template Option Options Hidden Container -->
                <select id="master_jenis_sampah_options" class="d-none">
                    <option value="" disabled selected>-- Pilih Jenis Sampah --</option>
                    <?php foreach ($option->result_array() as $key) { ?>
                        <?php $h = isset($key['harga_sampah']) ? $key['harga_sampah'] : 0; ?>
                        <option value="<?= $key['id'] ?>" data-harga="<?= $h ?>">
                            <?= htmlspecialchars($key['jenis_sampah']) ?> (Rp <?= number_format($h, 0, ',', '.') ?>/kg)
                        </option>
                    <?php } ?>
                </select>

                <div id="show_item"></div>

                <div class="row my-4 align-items-center g-3">
                    <div class="col-12 col-md-4">
                        <button type="button" class="btn btn-outline-success w-100 py-2 rounded-pill shadow-sm fw-bold" name="add_btn" id="add_btn">
                            <i class="fas fa-plus me-1"></i> Tambah Baris Sampah
                        </button>
                    </div>
                    <div class="col-12 col-md-8">
                        <div class="d-flex flex-column flex-sm-row justify-content-sm-end gap-2">
                            <button type="button" class="btn btn-warning py-2 px-4 rounded-pill shadow-sm fw-bold w-100 w-sm-auto" onclick="confirmSetor('Pending')">
                                <i class="fas fa-clock me-1"></i> Simpan (Pending)
                            </button>
                            <button type="button" class="btn btn-success py-2 px-4 rounded-pill shadow-sm fw-bold w-100 w-sm-auto" onclick="confirmSetor('Lunas')">
                                <i class="fas fa-check-circle me-1"></i> Proses & Bayar (Lunas)
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const MARGIN_VALUE = <?= isset($margin_value) ? $margin_value : 0 ?>;

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
                data: { 
                    cari: $(this).val(),
                    '<?= $this->security->get_csrf_token_name(); ?>': '<?= $this->security->get_csrf_hash(); ?>'
                },
                success: function (data) {
                    if (data.error) console.error('Error:', data.error);
                    else $('#result').html(data);
                }
            });
        });

        // Event saat jenis sampah diubah -> update harga satuan & kalkulasi ulang
        $(document).on('change', '.id_jenis_sampah', function () {
            var row = $(this).closest('.row');
            var selectedOpt = $(this).find('option:selected');
            var hargaSatuan = parseFloat(selectedOpt.data('harga')) || 0;

            row.find('.harga_satuan_tampil').val('Rp ' + formatRupiah(hargaSatuan));

            // Triggers kalkulasi jika berat sudah diisi
            row.find('.berat_sampah').trigger('keyup');
        });

        // Kalkulasi Harga Otomatis Saat Berat Diketik
        $(document).on('keyup change', '.berat_sampah', function () {
            var row = $(this).closest('.row');
            var berat = parseFloat($(this).val()) || 0;
            var hargaSatuan = parseFloat(row.find('.id_jenis_sampah option:selected').data('harga')) || 0;

            var totalHargaDasar = Math.floor(berat * hargaSatuan);
            var margin = Math.round(totalHargaDasar * (MARGIN_VALUE / 100));
            var finalHarga = Math.round(totalHargaDasar - margin);

            row.find('.harga_sampah_tampil').val('Rp ' + formatRupiah(totalHargaDasar));
            row.find('.harga_sampah_raw').val(totalHargaDasar);
            row.find('.margin_tampil').val('Rp ' + formatRupiah(margin));
            row.find('.harga_final_tampil').val('Rp ' + formatRupiah(finalHarga));
        });

        // Event Klik Mitra
        $('#result').on('click', '.result-item', function () {
            var userId = $(this).data('user-id');
            var username = $(this).data('username');

            $('#userid').val(userId);
            $('#username').val(username);

            document.getElementById('userid').style.background = '#d1e7dd';
            document.getElementById('username').style.background = '#d1e7dd';

            $('#result').html('<div class="alert alert-success border-0 shadow-sm rounded-3 py-2 px-3 mb-3 d-flex align-items-center justify-content-between"><small class="fw-bold"><i class="fas fa-check-circle me-1"></i> Mitra Terpilih: @' + username + ' (ID #' + userId + ')</small><button type="button" class="btn-close btn-close-sm" onclick="$(\'#result\').html(\'\')"></button></div>');
        });

        // Tambah Baris Dinamis dengan cloning options HTML
        $("#add_btn").click(function (e) {
            e.preventDefault();
            var optionsHtml = $('#master_jenis_sampah_options').html();

            var newRow = $(`
                <div class="row g-2 mb-3 align-items-end p-3 border rounded shadow-sm bg-white item-row">
                    <div class="col-12 col-md-3">
                        <label class="form-label fw-medium text-muted small">Jenis Sampah</label>
                        <select name="id_jenis_sampah[]" class="form-select id_jenis_sampah border-0 bg-light shadow-sm py-2" required>
                            ${optionsHtml}
                        </select>
                    </div>
                    <div class="col-6 col-md-2">
                        <label class="form-label fw-medium text-muted small">Harga / Kg</label>
                        <input type="text" class="form-control harga_satuan_tampil border-0 text-dark fw-bold bg-light shadow-sm py-2" readonly placeholder="Rp 0">
                    </div>
                    <div class="col-6 col-md-2">
                        <label class="form-label fw-medium text-muted small">Berat (Kg)</label>
                        <input type="number" name="berat_sampah[]" step="0.01" class="form-control berat_sampah border-0 bg-light shadow-sm py-2" placeholder="Cth: 1.5" required>
                    </div>
                    <div class="col-6 col-md-2">
                        <label class="form-label fw-medium text-muted small">Harga Dasar</label>
                        <input type="text" class="form-control harga_sampah_tampil border-0 text-secondary fw-bold shadow-sm py-2" readonly placeholder="Otomatis">
                        <input type="hidden" name="harga_sampah[]" class="harga_sampah_raw">
                    </div>
                    <div class="col-6 col-md-2">
                        <label class="form-label fw-medium text-muted small">Harga Final (`+ MARGIN_VALUE +`%)</label>
                        <input type="text" class="form-control harga_final_tampil border-0 text-success fw-bold shadow-sm py-2" readonly placeholder="Otomatis">
                    </div>
                    <div class="col-12 col-md-1 text-center mt-2 mt-md-0">
                        <button class="btn btn-outline-danger w-100 remove_btn shadow-sm py-2" title="Hapus Baris"><i class="fas fa-trash me-1"></i><span class="d-md-none">Hapus Baris</span></button>
                    </div>
                </div>`);

            $("#show_item").prepend(newRow);
        });

        $(document).on('click', '.remove_btn', function (e) {
            e.preventDefault();
            $(this).closest('.item-row').remove();
        });

        // Tambah 1 baris awal secara otomatis
        $("#add_btn").click();
    });

    // --- SWEETALERT KONFIRMASI SETOR ---
    window.confirmSetor = function (status) {
        if ($('#userid').val() == '') {
            Swal.fire('Mitra Belum Dipilih!', 'Silakan cari dan pilih mitra terlebih dahulu.', 'warning');
            return;
        }

        var itemRows = $('.item-row');
        if (itemRows.length == 0) {
            Swal.fire('Daftar Sampah Kosong!', 'Silakan klik tombol "Tambah Baris Sampah" dan pilih jenis sampah.', 'warning');
            return;
        }

        var hasEmptyJenis = false;
        var hasInvalidBerat = false;

        itemRows.each(function() {
            var jenis = $(this).find('.id_jenis_sampah').val();
            var berat = parseFloat($(this).find('.berat_sampah').val()) || 0;

            if (!jenis || jenis === '') {
                hasEmptyJenis = true;
            }
            if (berat <= 0) {
                hasInvalidBerat = true;
            }
        });

        if (hasEmptyJenis) {
            Swal.fire('Jenis Sampah Kosong!', 'Silakan pilih jenis sampah pada setiap baris setoran.', 'warning');
            return;
        }

        if (hasInvalidBerat) {
            Swal.fire('Berat Sampah Kosong / Nol!', 'Pastikan berat sampah (Kg) diisi lebih dari 0 pada setiap baris.', 'warning');
            return;
        }

        $('#status_pembayaran').val(status);

        var textMsg = (status === 'Lunas') 
            ? "Transaksi Beli Sampah akan langsung diproses dan ditandai LUNAS." 
            : "Transaksi Beli Sampah akan disimpan dengan status PENDING.";

        Swal.fire({
            title: 'Proses Beli Sampah (' + status.toUpperCase() + ')?',
            text: textMsg,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: (status === 'Lunas') ? '#198754' : '#ffc107',
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