<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
        <h2 class="fs-2 m-0 font-bold text-gray-900">Sistem Stoking</h2>
    </div>

    <!-- Tabs -->
    <ul class="nav nav-tabs mb-4" id="stokTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active font-semibold" id="realtime-tab" data-bs-toggle="tab" data-bs-target="#realtime" type="button" role="tab" aria-controls="realtime" aria-selected="true">
                <i class="fas fa-boxes me-2"></i>Sisa Stok Realtime
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link font-semibold" id="ledger-tab" data-bs-toggle="tab" data-bs-target="#ledger" type="button" role="tab" aria-controls="ledger" aria-selected="false">
                <i class="fas fa-history me-2"></i>Inventory Ledger
            </button>
        </li>
    </ul>

    <!-- Tab Contents -->
    <div class="tab-content" id="stokTabContent">
        
        <!-- Tab 1: Realtime Stok -->
        <div class="tab-pane fade show active" id="realtime" role="tabpanel" aria-labelledby="realtime-tab">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="tableRealtime">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Kategori</th>
                                    <th>Jenis Sampah</th>
                                    <th class="text-end">Stok Tersisa (Kg)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; foreach ($stok_realtime as $sr): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><span class="badge bg-secondary"><?= $sr->kategori_sampah ?></span></td>
                                    <td class="fw-bold"><?= $sr->jenis_sampah ?></td>
                                    <td class="text-end fw-bold text-<?= $sr->stok_tersisa > 0 ? 'success' : 'danger' ?>">
                                        <?= number_format($sr->stok_tersisa, 2, ',', '.') ?> Kg
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab 2: Inventory Ledger -->
        <div class="tab-pane fade" id="ledger" role="tabpanel" aria-labelledby="ledger-tab">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="tableLedger">
                            <thead class="table-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Jenis Sampah</th>
                                    <th>Tipe</th>
                                    <th class="text-end">Jumlah</th>
                                    <th class="text-end">Sisa Stok Akhir</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($stok_ledger as $sl): ?>
                                <tr>
                                    <td><?= date('d/m/Y H:i', strtotime($sl->created_at)) ?></td>
                                    <td class="fw-bold"><?= $sl->jenis_sampah ?></td>
                                    <td>
                                        <?php if($sl->tipe_pergerakan == 'masuk'): ?>
                                            <span class="badge bg-success"><i class="fas fa-arrow-down me-1"></i> Masuk</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger"><i class="fas fa-arrow-up me-1"></i> Keluar</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end"><?= number_format($sl->jumlah, 2, ',', '.') ?> Kg</td>
                                    <td class="text-end fw-bold"><?= number_format($sl->stok_sesudah, 2, ',', '.') ?> Kg</td>
                                    <td class="text-muted small"><?= $sl->keterangan ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>

<script>
    $(document).ready(function() {
        if ($.fn.DataTable.isDataTable('#tableRealtime')) {
            $('#tableRealtime').DataTable().destroy();
        }
        $('#tableRealtime').DataTable({
            "language": { "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json" }
        });

        if ($.fn.DataTable.isDataTable('#tableLedger')) {
            $('#tableLedger').DataTable().destroy();
        }
        $('#tableLedger').DataTable({
            "order": [[ 0, "desc" ]],
            "language": { "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json" }
        });
    });
</script>
