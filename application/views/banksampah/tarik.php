<div class="container-fluid pt-4 px-4">
    <div class="row mb-4 align-items-center">
        <div class="col-lg-12">
            <h3 class="fs-4 mb-0 fw-bold text-dark">Tarik Saldo</h3>
            <p class="text-muted mb-0">Cari nasabah dan cairkan saldo tabungan sampah mereka.</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-4">
            <label class="form-label fw-medium text-muted small">Pencarian Nasabah</label>
            <div class="input-group shadow-sm rounded">
                <span class="input-group-text bg-light border-0"><i class="fas fa-search text-muted"></i></span>
                <input type="text" name="search_text" id="search_text" placeholder="Ketik Username atau ID Nasabah..." class="form-control border-0 bg-light py-2" />
            </div>
            <div class="result mt-3" id="result"></div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-4">
            <h5 class="fw-bold mb-4">Detail Penarikan</h5>
            <form action="<?= base_url() ?>tarik/tarikTabungan" method="post" id="add_form">
                
                <div class="row g-3 mb-4 bg-light p-3 rounded align-items-center">
                    <div class="col-md-4">
                        <label for="id_tabungan" class="form-label fw-medium text-muted small">ID Tabungan</label>
                        <input type="text" name="id_tabungan" id="id_tabungan" class="form-control bg-white border-0 shadow-sm" readonly placeholder="-">
                    </div>
                    <div class="col-md-4">
                        <label for="username" class="form-label fw-medium text-muted small">Username</label>
                        <input type="text" name="username" id="username" class="form-control bg-white border-0 shadow-sm" readonly placeholder="-">
                    </div>
                    <div class="col-md-4">
                        <label for="saldo_tampil" class="form-label fw-bold text-dark">Saldo Saat Ini</label>
                        <input type="text" id="saldo_tampil" class="form-control border-0 fw-bold text-success fs-5 shadow-sm" readonly placeholder="0">
                        <input type="hidden" name="saldo" id="saldo">
                    </div>
                </div>

                <div class="row g-3 align-items-start">
                    <div class="col-md-8">
                        <label for="tariksaldo_tampil" class="form-label fw-medium text-muted small">Nominal Penarikan (Rp)</label>
                        <input type="text" inputmode="numeric" id="tariksaldo_tampil" class="form-control bg-light border-0 shadow-sm py-3 fs-5" placeholder="Masukkan jumlah uang...">
                        <input type="hidden" name="tariksaldo" id="tariksaldo">
                        <div class="keterangan mt-2 fw-medium" id="keterangan"></div>
                    </div>
                    <div class="col-md-4 mt-md-4 pt-md-2">
                        <button type="button" class="btn btn-success w-100 shadow-sm py-3 fw-bold rounded-3" onclick="confirmTarik()">
                            <i class="fas fa-hand-holding-usd me-1"></i> Proses Penarikan
                        </button>
                    </div>
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
        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return rupiah;
    }

    $('document').ready(function(){
        $('#search_text').on('keyup', function(){
            $.ajax({
                url     : '<?= base_url('tarik/cariUser')?>',
                type    : 'POST',
                data    : { cari : $(this).val() },
                success : function(data){
                    if (data.error) console.error('Error:', data.error);
                    else $('#result').html(data);
                }
            });
        });

        // Event saat nominal diketik (Format Titik Otomatis)
        $('#tariksaldo_tampil').on('keyup', function(){
            var valTampil = $(this).val();
            $(this).val(formatRupiah(valTampil)); // Tampilkan dengan titik

            var valAsli = valTampil.replace(/\./g, '');
            $('#tariksaldo').val(valAsli); // Simpan angka asli

            $('#keterangan').html('');
            var saldoAsli = $('#saldo').val();
            
            $.ajax({
                url     : '<?= base_url('tarik/cekSaldo')?>',
                type    : 'POST',
                data    : { cari : valAsli, saldo : saldoAsli },
                success : function(data){
                    if (data.error) console.error('Error:', data.error);
                    else $('#keterangan').html(data);
                }
            });
        });

        // Event saat nasabah diklik
        $('#result').on('click', '.result-item', function(){
            var idTabungan = $(this).data('tabungan-id');
            var username = $(this).data('username');
            var saldo = $(this).data('saldo');

            $('#id_tabungan').val(idTabungan);
            $('#username').val(username);
            
            // Tampilkan saldo dengan format Rupiah
            $('#saldo_tampil').val('Rp ' + formatRupiah(saldo));
            $('#saldo').val(saldo);

            document.getElementById('id_tabungan').style.background = '#d1e7dd';
            document.getElementById('username').style.background = '#d1e7dd';

            $('#result').html('');
        });
    });

    // --- SWEETALERT KONFIRMASI TARIK ---
    window.confirmTarik = function() {
        if ($('#id_tabungan').val() == '' || $('#tariksaldo').val() == '') {
            Swal.fire('Oops!', 'Pilih nasabah dan masukkan nominal penarikan.', 'warning');
            return;
        }

        Swal.fire({
            title: 'Proses Penarikan?',
            text: "Pastikan nominal uang yang ditarik sudah sesuai dengan fisik.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#198754', 
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-check me-1"></i> Ya, Tarik!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('add_form').submit(); 
            }
        });
    };
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