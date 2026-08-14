<div id="transaksi-table-container" class="container-fluid pt-4 px-4">
        
    <div class="row mb-4 align-items-center">
        <div class="col-lg-12">
            <h3 class="fs-4 mb-0 fw-bold text-dark">Data Transaksi</h3>
            <p class="text-muted mb-0">Pantau riwayat transaksi setor dan tarik saldo nasabah.</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-0">
            <div class="table-responsive table-responsive-cards">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-muted">
                        <tr>
                            <th scope="col" class="ps-4 py-3">Tanggal Transaksi</th>
                            <th scope="col" class="py-3">Nasabah</th>
                            <th scope="col" class="py-3">Petugas (Admin)</th>
                            <th scope="col" class="py-3">Setor (Debit)</th>
                            <th scope="col" class="py-3">Margin</th>
                            <th scope="col" class="py-3">Debit Final</th>
                            <th scope="col" class="py-3">Tarik (Kredit)</th>
                            <th scope="col" class="pe-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        <?php if($transaksi->num_rows() > 0): ?>
                            <?php foreach ($transaksi->result_array() as $key) { ?>
                            <tr>
                                <td class="ps-4 fw-medium text-dark" data-label="Tanggal Transaksi">
                                    <?php echo date('d M Y, H:i', strtotime($key['tgl_tabungan_transaksi'])); ?>
                                </td>
                                <td class="fw-bold text-primary" data-label="Nasabah">@<?php echo $key['nasabah_username'] ?></td>
                                <td data-label="Petugas (Admin)"><?php echo $key['staff_username'] ?></td>
                                
                                <td class="text-success fw-bold" data-label="Setor (Debit)">
                                    <?php echo $key['debit'] > 0 ? '+ Rp ' . number_format($key['debit'], 0, ',', '.') : '-'; ?>
                                </td>
                                <td class="text-warning fw-bold" data-label="Margin">
                                    <?php echo isset($key['margin']) && $key['margin'] > 0 ? '- Rp ' . number_format($key['margin'], 0, ',', '.') : '-'; ?>
                                </td>
                                <td class="text-success fw-bold" data-label="Debit Final">
                                    <?php echo isset($key['debit_final']) && $key['debit_final'] > 0 ? '+ Rp ' . number_format($key['debit_final'], 0, ',', '.') : '-'; ?>
                                </td>
                                <td class="text-danger fw-bold" data-label="Tarik (Kredit)">
                                    <?php echo $key['kredit'] > 0 ? '- Rp ' . number_format($key['kredit'], 0, ',', '.') : '-'; ?>
                                </td>
                                
                                <td class="pe-4 text-center" data-label="Aksi">
                                    <a href="<?=base_url()?>riwayat/invoice?id=<?=$key['id_tabungan_transaksi']?>" class="btn btn-sm btn-outline-primary px-3 rounded-pill" target="_blank">
                                        <i class="fas fa-file-invoice me-1"></i> Cetak Invoice
                                    </a>
                                </td>
                            </tr>
                            <?php } ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted fst-italic" data-label="Info">Data transaksi belum tersedia.</td>
                            </tr>
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