<div class="container-fluid pt-4 px-4">
    <div class="row mb-4 align-items-center">
        <div class="col-lg-12">
            <h3 class="fs-4 mb-0 fw-bold text-dark">Riwayat Perubahan Harga Sampah</h3>
            <p class="text-muted mb-0">Audit log setiap kali harga jenis sampah diubah oleh admin.</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-4">
            <?php if($history->num_rows() == 0): ?>
                <div class="text-center py-5">
                    <i class="fas fa-history fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Belum ada perubahan harga yang tercatat.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive table-responsive-cards">
                    <table class="table table-hover align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-muted small fw-medium">Tanggal</th>
                                <th class="text-muted small fw-medium">Jenis Sampah</th>
                                <th class="text-muted small fw-medium text-end">Harga Lama</th>
                                <th class="text-muted small fw-medium text-center">→</th>
                                <th class="text-muted small fw-medium text-end">Harga Baru</th>
                                <th class="text-muted small fw-medium text-end">Selisih</th>
                                <th class="text-muted small fw-medium">Admin</th>
                                <th class="text-muted small fw-medium">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($history->result_array() as $row): ?>
                                <?php
                                $selisih = intval($row['harga_baru']) - intval($row['harga_lama'] ?? 0);
                                $selisih_class = $selisih > 0 ? 'text-success' : ($selisih < 0 ? 'text-danger' : 'text-muted');
                                $selisih_icon = $selisih > 0 ? '↑' : ($selisih < 0 ? '↓' : '=');
                                ?>
                                <tr>
                                    <td class="small" data-label="Tanggal">
                                        <?= date('d M Y H:i', strtotime($row['tgl_perubahan'])) ?>
                                    </td>
                                    <td class="fw-medium" data-label="Jenis Sampah">
                                        <?= htmlspecialchars($row['jenis_sampah']) ?>
                                    </td>
                                    <td class="text-end font-monospace text-muted" data-label="Harga Lama">
                                        Rp <?= number_format($row['harga_lama'] ?? 0, 0, ',', '.') ?>
                                    </td>
                                    <td class="text-center text-muted" data-label="Info">→</td>
                                    <td class="text-end font-monospace fw-bold" data-label="Harga Baru">
                                        Rp <?= number_format($row['harga_baru'], 0, ',', '.') ?>
                                    </td>
                                    <td class="text-end font-monospace <?= $selisih_class ?>" data-label="Selisih">
                                        <?= $selisih_icon ?> Rp <?= number_format(abs($selisih), 0, ',', '.') ?>
                                    </td>
                                    <td class="small" data-label="Admin">
                                        <span class="badge bg-light text-dark">
                                            <?= htmlspecialchars($row['admin_username'] ?? '-') ?>
                                        </span>
                                    </td>
                                    <td class="small text-muted" data-label="Keterangan">
                                        <?= htmlspecialchars($row['keterangan'] ?? '-') ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="alert alert-info border-0 shadow-sm rounded-3">
        <div class="d-flex">
            <i class="fas fa-info-circle me-3 mt-1"></i>
            <div>
                <h6 class="alert-heading fw-bold">Tentang Saldo Dinamis</h6>
                <p class="mb-0 small">
                    Setiap kali harga sampah diubah, saldo nasabah yang masih menyimpan jenis sampah tersebut 
                    akan otomatis menyesuaikan. Ini mengikuti prinsip <strong>tabungan berbasis komoditas</strong> 
                    seperti tabungan emas — nilai tabungan mengikuti harga pasar terkini.
                </p>
            </div>
        </div>
    </div>
</div>