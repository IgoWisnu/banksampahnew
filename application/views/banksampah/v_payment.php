<div class="container-fluid pt-4 px-4">
    <div class="row mb-4 align-items-center">
        <div class="col-lg-12">
            <h3 class="fs-4 mb-0 fw-bold text-dark">Payment & Status Invoice</h3>
            <p class="text-muted mb-0">Kelola status pembayaran per invoice untuk transaksi Beli Sampah dan Jual Sampah.</p>
        </div>
    </div>

    <!-- Summary Stat Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3 bg-white p-3">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-primary bg-opacity-10 text-primary rounded-circle p-3 me-3">
                        <i class="fas fa-file-invoice fa-2x"></i>
                    </div>
                    <div>
                        <span class="text-muted small fw-medium">Total Invoice</span>
                        <h4 class="fw-bold mb-0 text-dark"><?= number_format($summary->total_invoice ?? 0) ?></h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3 bg-white p-3">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-success bg-opacity-10 text-success rounded-circle p-3 me-3">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                    <div>
                        <span class="text-muted small fw-medium">Total Terbayar (Lunas)</span>
                        <h4 class="fw-bold mb-0 text-success">Rp <?= number_format($summary->total_lunas ?? 0, 0, ',', '.') ?></h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3 bg-white p-3">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 bg-warning bg-opacity-10 text-warning rounded-circle p-3 me-3">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                    <div>
                        <span class="text-muted small fw-medium">Total Outstanding (Pending)</span>
                        <h4 class="fw-bold mb-0 text-warning">Rp <?= number_format($summary->total_pending ?? 0, 0, ',', '.') ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-3">
            <form action="<?= base_url('payment') ?>" method="get" class="row g-2 align-items-center">
                <div class="col-md-4">
                    <label class="form-label text-muted small fw-medium mb-1">Tipe Transaksi</label>
                    <select name="tipe" class="form-select border-0 bg-light shadow-sm" onchange="this.form.submit()">
                        <option value="all" <?= $tipe == 'all' ? 'selected' : '' ?>>Semua Tipe (Beli & Jual)</option>
                        <option value="beli" <?= $tipe == 'beli' ? 'selected' : '' ?>>Beli Sampah (Pengadaan)</option>
                        <option value="jual" <?= $tipe == 'jual' ? 'selected' : '' ?>>Jual Sampah (Penjualan)</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label text-muted small fw-medium mb-1">Status Pembayaran</label>
                    <select name="status" class="form-select border-0 bg-light shadow-sm" onchange="this.form.submit()">
                        <option value="all" <?= $status == 'all' ? 'selected' : '' ?>>Semua Status</option>
                        <option value="Pending" <?= $status == 'Pending' ? 'selected' : '' ?>>Pending (Belum Dibayar)</option>
                        <option value="Lunas" <?= $status == 'Lunas' ? 'selected' : '' ?>>Lunas (Terbayar)</option>
                    </select>
                </div>
                <div class="col-md-4 pt-4">
                    <a href="<?= base_url('payment') ?>" class="btn btn-light shadow-sm w-100"><i class="fas fa-undo me-1"></i> Reset Filter</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Table Invoices -->
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-4">
            <div class="table-responsive">
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
                                    <td>
                                        <span class="fw-bold text-dark"><?= !empty($row->no_invoice) ? $row->no_invoice : 'INV-' . $row->id_transaksi_sampah ?></span>
                                    </td>
                                    <td>
                                        <?php if ($row->tipe_transaksi == 'jual'): ?>
                                            <span class="badge bg-primary px-3 py-2 rounded-pill"><i class="fas fa-truck-loading me-1"></i> JUAL</span>
                                        <?php else: ?>
                                            <span class="badge bg-success px-3 py-2 rounded-pill"><i class="fas fa-shopping-cart me-1"></i> BELI</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="small fw-medium text-dark"><?= date('d M Y', strtotime($row->tgl_transaksi)) ?></div>
                                        <div class="text-muted extra-small"><?= date('H:i', strtotime($row->tgl_transaksi)) ?> WITA</div>
                                    </td>
                                    <td>
                                        <?php if ($row->tipe_transaksi == 'jual'): ?>
                                            <span class="fw-semibold text-dark"><?= htmlspecialchars($row->nama_pihak_luar ?? 'Buyer/Pabrik') ?></span>
                                        <?php else: ?>
                                            <span class="fw-semibold text-dark"><?= htmlspecialchars($row->nasabah_username ?? 'Nasabah') ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="fw-medium text-secondary">Rp <?= number_format($row->total_transaksi ?? 0, 0, ',', '.') ?></td>
                                    <td class="small text-warning">
                                        <?php if (!empty($row->biaya_tambahan) && $row->biaya_tambahan > 0): ?>
                                            + Rp <?= number_format($row->biaya_tambahan, 0, ',', '.') ?>
                                            <?php if(!empty($row->keterangan_biaya)): ?>
                                                <i class="fas fa-info-circle" title="<?= htmlspecialchars($row->keterangan_biaya) ?>"></i>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                    <td class="fw-bold text-dark fs-6">
                                        Rp <?= number_format(!empty($row->grand_total) ? $row->grand_total : $row->total_transaksi, 0, ',', '.') ?>
                                    </td>
                                    <td>
                                        <?php if ($row->status_pembayaran == 'Lunas'): ?>
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success px-3 py-2 rounded-pill">
                                                <i class="fas fa-check-circle me-1"></i> LUNAS
                                            </span>
                                            <?php if(!empty($row->tgl_pelunasan)): ?>
                                                <div class="text-muted extra-small mt-1"><?= date('d/m/Y H:i', strtotime($row->tgl_pelunasan)) ?></div>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="badge bg-warning bg-opacity-10 text-warning border border-warning px-3 py-2 rounded-pill">
                                                <i class="fas fa-clock me-1"></i> PENDING
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <?php if ($row->status_pembayaran == 'Pending'): ?>
                                                <button type="button" class="btn btn-success shadow-sm btn-update-status" 
                                                        data-id="<?= $row->id_transaksi_sampah ?>" 
                                                        data-invoice="<?= !empty($row->no_invoice) ? $row->no_invoice : 'INV-' . $row->id_transaksi_sampah ?>"
                                                        title="Tandai Lunas">
                                                    <i class="fas fa-check me-1"></i> Lunasi
                                                </button>
                                            <?php else: ?>
                                                <button type="button" class="btn btn-outline-warning shadow-sm btn-update-pending" 
                                                        data-id="<?= $row->id_transaksi_sampah ?>" 
                                                        data-invoice="<?= !empty($row->no_invoice) ? $row->no_invoice : 'INV-' . $row->id_transaksi_sampah ?>"
                                                        title="Ubah ke Pending">
                                                    <i class="fas fa-undo me-1"></i> Set Pending
                                                </button>
                                            <?php endif; ?>
                                            <a href="<?= base_url('payment/detail/' . $row->id_transaksi_sampah) ?>" target="_blank" class="btn btn-outline-secondary shadow-sm" title="Lihat/Cetak Invoice">
                                                <i class="fas fa-print"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="text-center py-4 text-muted">
                                    <i class="fas fa-inbox fa-3x mb-2 d-block opacity-25"></i>
                                    Belum ada data invoice yang sesuai filter.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Update Status Payment -->
<form action="<?= base_url('payment/update_status') ?>" method="post" id="form_update_status">
    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
    <input type="hidden" name="id_transaksi" id="modal_id_transaksi">
    <input type="hidden" name="status_pembayaran" id="modal_status_pembayaran">
</form>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        $('.btn-update-status').click(function() {
            var id = $(this).data('id');
            var inv = $(this).data('invoice');

            Swal.fire({
                title: 'Konfirmasi Pelunasan',
                text: "Apakah Anda yakin ingin menandai " + inv + " sebagai LUNAS?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                confirmButtonText: 'Ya, Tandai Lunas!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#modal_id_transaksi').val(id);
                    $('#modal_status_pembayaran').val('Lunas');
                    $('#form_update_status').submit();
                }
            });
        });

        $('.btn-update-pending').click(function() {
            var id = $(this).data('id');
            var inv = $(this).data('invoice');

            Swal.fire({
                title: 'Ubah Status ke Pending?',
                text: "Status " + inv + " akan diubah kembali menjadi PENDING.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ffc107',
                confirmButtonText: 'Ya, Ubah ke Pending',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#modal_id_transaksi').val(id);
                    $('#modal_status_pembayaran').val('Pending');
                    $('#form_update_status').submit();
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
</script>
