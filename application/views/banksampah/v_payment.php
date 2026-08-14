<div class="container-fluid pt-4 px-4">
    <div class="row mb-4 align-items-center">
        <div class="col-lg-12">
            <h3 class="fs-4 mb-0 fw-bold text-dark">Payment & Status Invoice</h3>
            <p class="text-muted mb-0">Kelola status pembayaran per invoice, pantau arus kas masuk/keluar, serta rekapitulasi Utang & Piutang usaha.</p>
        </div>
    </div>

    <!-- Financial Breakdown Ledger Card Collapsible -->
    <div class="card border-0 shadow-sm rounded-3 mb-4 bg-white">
        <div class="card-header bg-light border-0 py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <h6 class="fw-bold text-dark mb-0"><i class="fas fa-chart-pie text-success me-2"></i>Rincian Keuangan & Buku Besar Invoice</h6>
                <?php if (!empty($tgl_mulai) || !empty($tgl_selesai)): ?>
                    <span class="badge bg-primary px-3 py-1 rounded-pill small">
                        <i class="fas fa-calendar-alt me-1"></i> Periode: <?= !empty($tgl_mulai) ? date('d M Y', strtotime($tgl_mulai)) : 'Awal' ?> s/d <?= !empty($tgl_selesai) ? date('d M Y', strtotime($tgl_selesai)) : 'Hari ini' ?>
                    </span>
                <?php endif; ?>
            </div>
            <button class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 fw-bold" type="button" id="btnToggleLedger">
                <i class="fas fa-chevron-up me-1" id="iconToggleLedger"></i> <span id="textToggleLedger">Sembunyikan</span>
            </button>
        </div>
        <div class="collapse show" id="ledgerCollapse">
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-6 col-lg-3">
                        <div class="p-3 bg-light rounded shadow-sm">
                            <span class="text-muted small fw-medium d-block">Kas Masuk (Jual Lunas)</span>
                            <h5 class="fw-bold text-success mb-1">
                                + Rp <?= number_format($fin_summary->kas_masuk ?? 0, 0, ',', '.') ?>
                            </h5>
                            <small class="text-muted">Total Omset: Rp <?= number_format($fin_summary->total_omset_jual ?? 0, 0, ',', '.') ?></small>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="p-3 bg-light rounded shadow-sm">
                            <span class="text-muted small fw-medium d-block">Kas Keluar (Beli Lunas)</span>
                            <h5 class="fw-bold text-danger mb-1">
                                - Rp <?= number_format($fin_summary->kas_keluar ?? 0, 0, ',', '.') ?>
                            </h5>
                            <small class="text-muted">Total Pembelian: Rp <?= number_format($fin_summary->total_pembelian ?? 0, 0, ',', '.') ?></small>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="p-3 bg-light rounded shadow-sm border-start border-4 border-warning">
                            <span class="text-muted small fw-medium d-block">Utang Usaha (Beli Pending)</span>
                            <h5 class="fw-bold text-warning mb-1">
                                Rp <?= number_format($fin_summary->utang_beli ?? 0, 0, ',', '.') ?>
                            </h5>
                            <small class="text-muted">Kewajiban Bayar Pengepul</small>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="p-3 bg-light rounded shadow-sm border-start border-4 border-info">
                            <span class="text-muted small fw-medium d-block">Piutang Usaha (Jual Pending)</span>
                            <h5 class="fw-bold text-info mb-1">
                                Rp <?= number_format($fin_summary->piutang_jual ?? 0, 0, ',', '.') ?>
                            </h5>
                            <small class="text-muted">Tagihan ke Buyer</small>
                        </div>
                    </div>
                </div>

                <hr class="my-3">

                <div class="row align-items-center">
                    <div class="col-md-6">
                        <span class="text-muted me-3">Biaya Tambahan / Angkut: <strong class="text-dark">Rp <?= number_format($fin_summary->total_biaya_tambahan ?? 0, 0, ',', '.') ?></strong></span>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <span class="fs-6 fw-bold me-2">Saldo Arus Kas Net:</span>
                        <span class="fs-5 fw-bold <?= ($fin_summary->saldo_kas ?? 0) >= 0 ? 'text-success' : 'text-danger' ?>">
                            Rp <?= number_format($fin_summary->saldo_kas ?? 0, 0, ',', '.') ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php 
        $url_date = '';
        if (!empty($tgl_mulai)) $url_date .= '&tgl_mulai=' . urlencode($tgl_mulai);
        if (!empty($tgl_selesai)) $url_date .= '&tgl_selesai=' . urlencode($tgl_selesai);
    ?>

    <!-- Summary Stat Cards & Quick Filters -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <a href="<?= base_url('payment?tipe=all&status=all' . $url_date) ?>" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-3 bg-white p-3 <?= ($tipe == 'all' && $status == 'all') ? 'border-start border-4 border-primary' : '' ?>">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-primary bg-opacity-10 text-primary rounded-circle p-3 me-3">
                            <i class="fas fa-file-invoice fa-2x"></i>
                        </div>
                        <div>
                            <span class="text-muted small fw-medium">Total Invoice</span>
                            <h5 class="fw-bold mb-0 text-dark"><?= number_format($summary->total_invoice ?? 0) ?></h5>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="<?= base_url('payment?tipe=all&status=Lunas' . $url_date) ?>" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-3 bg-white p-3 <?= ($status == 'Lunas') ? 'border-start border-4 border-success' : '' ?>">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-success bg-opacity-10 text-success rounded-circle p-3 me-3">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                        <div>
                            <span class="text-muted small fw-medium">Total Lunas</span>
                            <h5 class="fw-bold mb-0 text-success">Rp <?= number_format($summary->total_lunas ?? 0, 0, ',', '.') ?></h5>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="<?= base_url('payment?tipe=beli&status=Pending' . $url_date) ?>" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-3 bg-white p-3 <?= ($tipe == 'beli' && $status == 'Pending') ? 'border-start border-4 border-warning' : '' ?>">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-warning bg-opacity-10 text-warning rounded-circle p-3 me-3">
                            <i class="fas fa-exclamation-triangle fa-2x"></i>
                        </div>
                        <div>
                            <span class="text-muted small fw-medium">Utang Beli Pending</span>
                            <h5 class="fw-bold mb-0 text-warning">Rp <?= number_format($fin_summary->utang_beli ?? 0, 0, ',', '.') ?></h5>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="<?= base_url('payment?tipe=jual&status=Pending' . $url_date) ?>" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-3 bg-white p-3 <?= ($tipe == 'jual' && $status == 'Pending') ? 'border-start border-4 border-info' : '' ?>">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-info bg-opacity-10 text-info rounded-circle p-3 me-3">
                            <i class="fas fa-hand-holding-usd fa-2x"></i>
                        </div>
                        <div>
                            <span class="text-muted small fw-medium">Piutang Jual Pending</span>
                            <h5 class="fw-bold mb-0 text-info">Rp <?= number_format($fin_summary->piutang_jual ?? 0, 0, ',', '.') ?></h5>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Filter Bar Lengkap dengan Date Range Filter -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-header bg-light border-0 py-3 px-4">
            <h6 class="fw-bold text-dark mb-0"><i class="fas fa-filter text-primary me-2"></i>Filter Pencarian Invoice</h6>
        </div>
        <div class="card-body p-3">
            <form action="<?= base_url('payment') ?>" method="get" class="row g-3 align-items-end">
                <div class="col-md-3 col-sm-6">
                    <label class="form-label text-muted small fw-medium mb-1"><i class="fas fa-calendar-day me-1"></i>Tanggal Mulai</label>
                    <input type="date" name="tgl_mulai" class="form-control border-0 bg-light shadow-sm" value="<?= htmlspecialchars($tgl_mulai ?? '') ?>">
                </div>
                <div class="col-md-3 col-sm-6">
                    <label class="form-label text-muted small fw-medium mb-1"><i class="fas fa-calendar-check me-1"></i>Tanggal Selesai</label>
                    <input type="date" name="tgl_selesai" class="form-control border-0 bg-light shadow-sm" value="<?= htmlspecialchars($tgl_selesai ?? '') ?>">
                </div>
                <div class="col-md-2 col-sm-6">
                    <label class="form-label text-muted small fw-medium mb-1">Tipe Transaksi</label>
                    <select name="tipe" class="form-select border-0 bg-light shadow-sm">
                        <option value="all" <?= $tipe == 'all' ? 'selected' : '' ?>>Semua Tipe</option>
                        <option value="beli" <?= $tipe == 'beli' ? 'selected' : '' ?>>Beli Sampah</option>
                        <option value="jual" <?= $tipe == 'jual' ? 'selected' : '' ?>>Jual Sampah</option>
                    </select>
                </div>
                <div class="col-md-2 col-sm-6">
                    <label class="form-label text-muted small fw-medium mb-1">Status Pembayaran</label>
                    <select name="status" class="form-select border-0 bg-light shadow-sm">
                        <option value="all" <?= $status == 'all' ? 'selected' : '' ?>>Semua Status</option>
                        <option value="Pending" <?= $status == 'Pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="Lunas" <?= $status == 'Lunas' ? 'selected' : '' ?>>Lunas</option>
                    </select>
                </div>
                <div class="col-md-2 col-sm-12">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary shadow-sm w-100 fw-bold">
                            <i class="fas fa-search me-1"></i> Filter
                        </button>
                        <a href="<?= base_url('payment') ?>" class="btn btn-light shadow-sm" title="Reset Filter">
                            <i class="fas fa-undo"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Table Invoices -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-4">
            <div class="table-responsive table-responsive-cards">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No Invoice</th>
                            <th>Tipe</th>
                            <th>Tanggal & Waktu</th>
                            <th>Pihak Terkait</th>
                            <th>Subtotal</th>
                            <th>Biaya Tambahan</th>
                            <th>Grand Total</th>
                            <th>Status Bayar</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($invoices->num_rows() > 0): ?>
                            <?php foreach ($invoices->result() as $row): ?>
                                <tr>
                                    <td class="fw-bold text-primary" data-label="No Invoice"><?= $row->no_invoice ?></td>
                                    <td data-label="Tipe">
                                        <?php if ($row->tipe_transaksi == 'jual'): ?>
                                            <span class="badge bg-primary">Jual (Sales)</span>
                                        <?php else: ?>
                                            <span class="badge bg-success">Beli (Intake)</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="small text-muted" data-label="Tanggal & Waktu"><?= date('d M Y H:i', strtotime($row->tgl_transaksi)) ?></td>
                                    <td data-label="Pihak Terkait">
                                        <?php if ($row->tipe_transaksi == 'jual'): ?>
                                            <i class="fas fa-building me-1 text-muted"></i> <?= htmlspecialchars($row->nama_pihak_luar ? $row->nama_pihak_luar : 'Buyer Eksternal') ?>
                                        <?php else: ?>
                                            <i class="fas fa-user me-1 text-muted"></i> <?= htmlspecialchars($row->nasabah_username ? $row->nasabah_username : ($row->nama_pihak_luar ? $row->nama_pihak_luar : 'Mitra')) ?>
                                        <?php endif; ?>
                                    </td>
                                    <td data-label="Subtotal">Rp <?= number_format($row->total_transaksi, 0, ',', '.') ?></td>
                                    <td data-label="Biaya Tambahan">Rp <?= number_format($row->biaya_tambahan, 0, ',', '.') ?></td>
                                    <td class="fw-bold text-dark" data-label="Grand Total">Rp <?= number_format($row->grand_total, 0, ',', '.') ?></td>
                                    <td data-label="Status Bayar">
                                        <?php if ($row->status_pembayaran == 'Lunas'): ?>
                                            <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> Lunas</span>
                                            <?php if ($row->tgl_pelunasan): ?>
                                                <small class="d-block text-muted" style="font-size: 0.7rem;"><?= date('d/m/Y H:i', strtotime($row->tgl_pelunasan)) ?></small>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i> Pending</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center" data-label="Aksi">
                                        <div class="btn-group">
                                            <a href="<?= base_url('payment/detail/' . $row->id_transaksi_sampah) ?>" class="btn btn-sm btn-outline-info rounded-pill me-1" title="Lihat Detail Invoice" target="_blank">
                                                <i class="fas fa-eye"></i> Detail
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-success rounded-pill me-1" onclick="shareInvoice('<?= $row->id_transaksi_sampah ?>', '<?= $row->no_invoice ?>')" title="Bagikan PDF Invoice">
                                                <i class="fas fa-share-alt"></i> Share
                                            </button>
                                            <?php if ($row->status_pembayaran == 'Pending'): ?>
                                                <button type="button" class="btn btn-sm btn-success rounded-pill" onclick="confirmPelunasan('<?= $row->id_transaksi_sampah ?>', '<?= $row->no_invoice ?>', 'Lunas')">
                                                    <i class="fas fa-check me-1"></i> Bayar
                                                </button>
                                            <?php else: ?>
                                                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill" onclick="confirmPelunasan('<?= $row->id_transaksi_sampah ?>', '<?= $row->no_invoice ?>', 'Pending')">
                                                    Ubah Pending
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="text-center py-4 text-muted">Belum ada data invoice yang sesuai dengan filter.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Form Tersembunyi untuk Update Status -->
<form id="form_update_status" action="<?= base_url('payment/update_status') ?>" method="post" class="d-none">
    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
    <input type="hidden" name="id_transaksi" id="update_id_transaksi">
    <input type="hidden" name="status_pembayaran" id="update_status_pembayaran">
</form>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmPelunasan(id, noInvoice, statusTarget) {
        var actionText = (statusTarget === 'Lunas') ? 'menandai invoice LUNAS (terbayar)' : 'mengubah status invoice menjadi PENDING';
        var btnColor   = (statusTarget === 'Lunas') ? '#198754' : '#ffc107';

        Swal.fire({
            title: 'Ubah Status Invoice?',
            html: 'Apakah Anda yakin ingin ' + actionText + ' untuk invoice <strong>' + noInvoice + '</strong>?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: btnColor,
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Ubah Status!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $('#update_id_transaksi').val(id);
                $('#update_status_pembayaran').val(statusTarget);
                $('#form_update_status').submit();
            }
        });
    }

    $(document).ready(function() {
        $('#btnToggleLedger').on('click', function(e) {
            e.preventDefault();
            $('#ledgerCollapse').slideToggle(300, function() {
                if ($(this).is(':visible')) {
                    $('#iconToggleLedger').removeClass('fa-chevron-down').addClass('fa-chevron-up');
                    $('#textToggleLedger').text('Sembunyikan');
                } else {
                    $('#iconToggleLedger').removeClass('fa-chevron-up').addClass('fa-chevron-down');
                    $('#textToggleLedger').text('Tampilkan Panel');
                }
            });
        });

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

    async function shareInvoice(id, invoiceNo) {
        if (!navigator.share || !navigator.canShare) {
            Swal.fire({
                icon: 'info',
                title: 'Perangkat Tidak Mendukung',
                text: 'Browser/Perangkat Anda tidak mendukung fitur Web Share API. Silakan unduh PDF-nya terlebih dahulu.',
            });
            return;
        }

        // Tampilkan loading swal
        Swal.fire({
            title: 'Menyiapkan File...',
            text: 'Mohon tunggu sebentar',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        try {
            const pdfUrl = '<?= base_url("payment/pdf_invoice/") ?>' + id;
            const response = await fetch(pdfUrl);
            
            if (!response.ok) throw new Error('Gagal mengunduh PDF');
            
            const blob = await response.blob();
            // Buat File object dari Blob
            const file = new File([blob], 'Invoice_' + invoiceNo + '.pdf', { type: 'application/pdf' });
            
            // Tutup loading
            Swal.close();

            // Cek apakah browser bisa membagikan file array ini
            if (navigator.canShare({ files: [file] })) {
                await navigator.share({
                    title: 'Invoice ' + invoiceNo,
                    text: 'Berikut adalah lampiran Invoice ' + invoiceNo,
                    files: [file]
                });
            } else {
                throw new Error('Browser tidak mendukung bagikan tipe file ini');
            }
        } catch (error) {
            Swal.close();
            console.error('Error sharing invoice:', error);
            Swal.fire({
                icon: 'error',
                title: 'Gagal Membagikan',
                text: 'Terjadi kesalahan atau proses dibatalkan: ' + error.message,
            });
        }
    }
</script>
