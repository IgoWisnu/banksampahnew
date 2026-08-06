<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?= $title; ?></title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 11px; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #00926E; padding-bottom: 10px; margin-bottom: 15px; }
        .header h2 { margin: 0; color: #00926E; font-size: 20px; text-transform: uppercase; letter-spacing: 1px; }
        .header p { margin: 5px 0 0 0; font-size: 12px; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 6px 8px; border: 1px solid #ddd; }
        th { background-color: #00926E; color: #ffffff; text-align: center; font-weight: bold; text-transform: uppercase; font-size: 10px; }
        tbody tr:nth-child(even) { background-color: #f9f9f9; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .text-success { color: #00926E; }
        .text-danger { color: #d9534f; }
        .badge-masuk { color: #198754; font-weight: bold; }
        .badge-keluar { color: #dc3545; font-weight: bold; }
    </style>
</head>

<body>
    <div class="header">
        <h2>Laporan Stok Sampah (Inventory Ledger)</h2>
        <p>MANKADIBALIRECYCLING - Dicetak pada: <strong><?= date('d F Y H:i') ?> WITA</strong></p>
    </div>

    <h3 style="color: #00926E; margin-bottom: 5px;">1. Saldo Stok Sampah Realtime</h3>
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="25%">Jenis Sampah</th>
                <th width="20%">Kategori</th>
                <th width="20%">Sub Kategori</th>
                <th width="15%">Harga Catalog (Rp/Kg)</th>
                <th width="15%">Stok Tersisa (Kg)</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            $total_stok = 0;
            foreach($stok->result_array() as $row): 
                $stok_val = floatval($row['stok_tersisa']);
                $total_stok += $stok_val;
            ?>
            <tr>
                <td class="text-center"><?= $no++ ?></td>
                <td class="font-bold text-success"><?= htmlspecialchars($row['jenis_sampah']) ?></td>
                <td><?= htmlspecialchars($row['kategori_sampah']) ?></td>
                <td><?= htmlspecialchars($row['sub_kategori_sampah']) ?></td>
                <td class="text-right">Rp <?= number_format($row['harga_sampah'], 0, ',', '.') ?></td>
                <td class="text-right font-bold"><?= number_format($stok_val, 2, ',', '.') ?> Kg</td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="5" class="text-right">TOTAL STOK TERSISA</th>
                <th class="text-right"><?= number_format($total_stok, 2, ',', '.') ?> Kg</th>
            </tr>
        </tfoot>
    </table>

    <br><br>
    <h3 style="color: #00926E; margin-bottom: 5px;">2. Audit Trail Pergerakan Stok Terakhir</h3>
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Waktu</th>
                <th width="20%">Jenis Sampah</th>
                <th width="12%">Tipe Mutasi</th>
                <th width="12%">Jumlah (Kg)</th>
                <th width="12%">Stok Sebelum</th>
                <th width="12%">Stok Sesudah</th>
                <th width="12%">No Invoice</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no_log = 1;
            foreach($log->result_array() as $l): 
            ?>
            <tr>
                <td class="text-center"><?= $no_log++ ?></td>
                <td class="text-center"><?= date('d/m/Y H:i', strtotime($l['created_at'])) ?></td>
                <td><?= htmlspecialchars($l['jenis_sampah']) ?></td>
                <td class="text-center">
                    <?php if($l['tipe_pergerakan'] == 'masuk'): ?>
                        <span class="badge-masuk">MASUK (BELI)</span>
                    <?php else: ?>
                        <span class="badge-keluar">KELUAR (JUAL)</span>
                    <?php endif; ?>
                </td>
                <td class="text-right font-bold"><?= number_format($l['jumlah'], 2, ',', '.') ?> Kg</td>
                <td class="text-right"><?= number_format($l['stok_sebelum'], 2, ',', '.') ?> Kg</td>
                <td class="text-right"><?= number_format($l['stok_sesudah'], 2, ',', '.') ?> Kg</td>
                <td class="text-center"><?= htmlspecialchars($l['no_invoice'] ?? '-') ?></td>
            </tr>
            <?php endforeach; ?>
            <?php if($log->num_rows() == 0): ?>
            <tr>
                <td colspan="8" class="text-center" style="padding: 15px; color: #777;">Belum ada log pergerakan stok.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
