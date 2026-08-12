<div class="container-fluid pt-4 px-4">
    <div class="row mb-4 align-items-center">
        <div class="col-lg-12">
            <h3 class="fs-4 mb-0 fw-bold text-dark">Jual Sampah (Pengeluaran Stok)</h3>
            <p class="text-muted mb-0">Input data penjualan sampah ke nasabah / pembeli / pabrik dengan harga manual dan biaya tambahan.</p>
        </div>
    </div>

    <!-- Hidden Master Options for JS Cloning -->
    <select id="master_jual_jenis_options" class="d-none">
        <option value="" disabled selected>-- Pilih Jenis Sampah --</option>
        <?php foreach ($option->result_array() as $key) { ?>
            <option value="<?= $key['id'] ?>" data-stok="<?= $key['stok_tersisa'] ?>" data-harga="<?= $key['harga_sampah'] ?>">
                <?= htmlspecialchars($key['jenis_sampah']) ?> (<?= htmlspecialchars($key['sub_kategori_sampah']) ?>) - [Stok: <?= $key['stok_tersisa'] ?> kg]
            </option>
        <?php } ?>
    </select>

    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-4">
            <form action="<?= base_url('jualSampah/prosesJual') ?>" method="post" id="form_jual">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

                <h6 class="fw-bold mb-3 text-dark"><i class="fas fa-building text-primary me-2"></i>Data Pembeli / Nasabah & Invoice</h6>
                <input type="hidden" name="status_pembayaran" id="status_pembayaran" value="Lunas">
                <div class="row g-3 mb-4 bg-light p-3 rounded align-items-center mx-0">
                    <div class="col-md-6">
                        <label for="select_nasabah_buyer" class="form-label fw-medium text-muted small">Pilih Dari Data Nasabah (Opsional)</label>
                        <select id="select_nasabah_buyer" class="form-select bg-white border-0 shadow-sm">
                            <option value="">-- Pilih Nasabah / Pengepul --</option>
                            <?php if (!empty($nasabah_list)): ?>
                                <?php foreach ($nasabah_list as $n): ?>
                                    <option value="<?= htmlspecialchars($n['username'] . ($n['nama_lengkap'] ? ' - ' . $n['nama_lengkap'] : '')) ?>">
                                        @<?= htmlspecialchars($n['username']) ?> <?= $n['nama_lengkap'] ? '(' . htmlspecialchars($n['nama_lengkap']) . ')' : '' ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="nama_buyer" class="form-label fw-medium text-muted small">Nama Pembeli / Buyer / Perusahaan <span class="text-danger">*</span></label>
                        <input type="text" name="nama_buyer" id="nama_buyer" class="form-control bg-white border-0 shadow-sm" required placeholder="Ketik nama pembeli / pilih nasabah">
                    </div>
                </div>

                <h6 class="fw-bold mb-3 text-dark"><i class="fas fa-boxes text-success me-2"></i>Daftar Item Sampah yang Dijual</h6>

                <div id="show_item_jual"></div>

                <div class="row my-3">
                    <div class="col-12">
                        <button class="btn btn-outline-success px-4 rounded-pill shadow-sm" type="button" id="add_btn_jual">
                            <i class="fas fa-plus me-1"></i> Tambah Item Sampah
                        </button>
                    </div>
                </div>

                <hr class="my-4">

                <h6 class="fw-bold mb-3 text-dark"><i class="fas fa-truck text-warning me-2"></i>Additional Fee & Summary</h6>
                <div class="row g-3 bg-light p-3 rounded mx-0 mb-4">
                    <div class="col-md-4">
                        <label class="form-label fw-medium text-muted small">Biaya Tambahan / Additional Fee (Rp)</label>
                        <input type="number" name="biaya_tambahan" id="biaya_tambahan" class="form-control bg-white border-0 shadow-sm py-2" value="0" placeholder="Biaya angkut dll.">
                    </div>
                    <div class="col-md-5">
                        <label class="form-label fw-medium text-muted small">Keterangan Biaya Tambahan</label>
                        <input type="text" name="keterangan_biaya" class="form-control bg-white border-0 shadow-sm py-2" placeholder="Contoh: Biaya Angkut & Loading Armada">
                    </div>
                    <div class="col-md-3 text-end">
                        <label class="form-label fw-medium text-muted small d-block">Estimasi Grand Total</label>
                        <span class="fs-4 fw-bold text-success" id="display_grand_total">Rp 0</span>
                    </div>
                </div>

                <div class="text-end">
                    <button type="button" class="btn btn-warning px-4 py-2 rounded-pill shadow-sm fw-bold me-2" onclick="confirmJual('Pending')">
                        <i class="fas fa-clock me-1"></i> Simpan (Pending)
                    </button>
                    <button type="button" class="btn btn-success px-5 py-2 rounded-pill shadow-sm fw-bold" onclick="confirmJual('Lunas')">
                        <i class="fas fa-check-circle me-1"></i> Proses & Jual (Lunas)
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
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
        return 'Rp ' + rupiah;
    }

    function hitungGrandTotal() {
        var totalItem = 0;
        $('.subtotal_item_raw').each(function() {
            var val = parseFloat($(this).val()) || 0;
            totalItem += val;
        });

        var biayaTambahan = parseFloat($('#biaya_tambahan').val()) || 0;
        var grandTotal = totalItem + biayaTambahan;

        $('#display_grand_total').text(formatRupiah(grandTotal));
    }

    $(document).ready(function() {
        // Dropdown nasabah auto-fill ke nama pembeli
        $('#select_nasabah_buyer').on('change', function() {
            var val = $(this).val();
            if (val !== '') {
                $('#nama_buyer').val(val);
            }
        });

        // Event saat jenis sampah diubah -> set default harga manual
        $(document).on('change', '.id_jenis_sampah', function() {
            var row = $(this).closest('.row');
            var selectedOpt = $(this).find('option:selected');
            var hargaDefault = parseFloat(selectedOpt.data('harga')) || 0;

            row.find('.harga_manual').val(hargaDefault);
            row.find('.berat_jual').trigger('keyup');
        });

        // Event perubahan berat atau harga manual
        $(document).on('keyup change', '.berat_jual, .harga_manual', function() {
            var row = $(this).closest('.row');
            var berat = parseFloat(row.find('.berat_jual').val()) || 0;
            var harga = parseFloat(row.find('.harga_manual').val()) || 0;
            var stokMax = parseFloat(row.find('.id_jenis_sampah option:selected').data('stok')) || 0;

            if (berat > stokMax && stokMax > 0) {
                row.find('.stok_warning').removeClass('d-none').text('⚠️ Berat melebihi stok tersisa (' + stokMax + ' kg)');
            } else {
                row.find('.stok_warning').addClass('d-none');
            }

            var total = Math.floor(berat * harga);
            row.find('.subtotal_item_tampil').val(formatRupiah(total));
            row.find('.subtotal_item_raw').val(total);

            hitungGrandTotal();
        });

        $('#biaya_tambahan').on('keyup change', function() {
            hitungGrandTotal();
        });

        // Tambah Baris Dinamis Jual dengan Cloning Master Options
        $("#add_btn_jual").click(function(e) {
            e.preventDefault();
            var optionsHtml = $('#master_jual_jenis_options').html();

            var newRow = $(`
                <div class="row g-2 mb-3 align-items-start p-3 border rounded shadow-sm bg-white item-jual-row">
                    <div class="col-md-4">
                        <label class="form-label fw-medium text-muted small">Jenis Sampah & Stok Tersisa</label>
                        <select name="id_jenis_sampah[]" class="form-select id_jenis_sampah border-0 bg-light shadow-sm py-2" required>
                            ${optionsHtml}
                        </select>
                        <span class="text-danger small mt-1 d-none stok_warning"></span>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-medium text-muted small">Berat Dijual (Kg)</label>
                        <input type="number" name="berat_sampah[]" step="0.01" class="form-control berat_jual border-0 bg-light shadow-sm py-2" required placeholder="Cth: 10.5">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-medium text-muted small">Harga Satuan / Kg (Manual Rp)</label>
                        <input type="number" name="harga_manual[]" class="form-control harga_manual border-0 bg-light shadow-sm py-2" required placeholder="Masukkan harga manual">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-medium text-muted small">Subtotal Item</label>
                        <input type="text" class="form-control subtotal_item_tampil border-0 text-success fw-bold shadow-sm py-2" readonly placeholder="Rp 0">
                        <input type="hidden" class="subtotal_item_raw" value="0">
                    </div>
                    <div class="col-md-1 text-center pt-4">
                        <button type="button" class="btn btn-danger w-100 remove_btn_jual shadow-sm py-2" title="Hapus"><i class="fas fa-trash"></i></button>
                    </div>
                </div>`);

            $("#show_item_jual").prepend(newRow);
        });

        // Hapus Baris
        $(document).on('click', '.remove_btn_jual', function(e) {
            e.preventDefault();
            $(this).closest('.item-jual-row').remove();
            hitungGrandTotal();
        });

        // Tambah 1 baris awal secara otomatis
        $("#add_btn_jual").click();
    });

    window.confirmJual = function(status) {
        if ($('#nama_buyer').val().trim() == '') {
            Swal.fire('Oops!', 'Silakan isi atau pilih Nama Pembeli / Buyer terlebih dahulu.', 'warning');
            return;
        }

        if ($('.item-jual-row').length == 0) {
            Swal.fire('Oops!', 'Silakan tambah minimal 1 item sampah yang dijual.', 'warning');
            return;
        }

        var overStok = false;
        $('.stok_warning').each(function() {
            if (!$(this).hasClass('d-none')) {
                overStok = true;
            }
        });

        if (overStok) {
            Swal.fire('Peringatan Stok!', 'Terdapat item dengan jumlah yang melebihi stok tersedia. Mohon periksa kembali berat sampah.', 'error');
            return;
        }

        if (status) {
            $('#status_pembayaran').val(status);
        } else {
            status = $('#status_pembayaran').val();
        }

        var textMsg = (status === 'Lunas')
            ? "Transaksi Penjualan Sampah akan langsung diproses dan ditandai LUNAS."
            : "Transaksi Penjualan Sampah akan disimpan dengan status PENDING (Piutang).";

        Swal.fire({
            title: 'Proses Penjualan (' + status.toUpperCase() + ')?',
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
                $('#form_jual').submit();
            }
        });
    };

    $(document).ready(function() {
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
