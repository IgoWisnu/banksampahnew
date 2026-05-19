<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?= $title; ?></title>
    <style>
        /* CSS Internal khusus untuk cetak PDF agar tidak pecah */
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #00926E;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
            color: #00926E;
            font-size: 24px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .header p {
            margin: 5px 0 0 0;
            font-size: 14px;
            color: #555;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 8px 10px;
            border: 1px solid #ddd;
        }
        th {
            background-color: #00926E;
            color: #ffffff;
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 11px;
        }
        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .text-success { color: #00926E; }
        .text-danger { color: #d9534f; }
        .badge {
            font-size: 10px;
            color: #444;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>Laporan Transaksi Bank Sampah</h2>
        <p>Periode: <strong><?= date('d F Y', strtotime($date_from)) ?></strong> s/d <strong><?= date('d F Y', strtotime($date_to)) ?></strong></p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Tanggal</th>
                <th width="15%">Nama Nasabah</th>
                <th width="15%">Setor (Debit)</th>
                <th width="15%">Tarik (Kredit)</th>
                <th width="35%">Keterangan Sampah</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            $total_setor = 0;
            $total_tarik = 0;
            
            foreach($laporan->result_array() as $key): 
                $total_setor += $key['debit'];
                $total_tarik += $key['kredit'];
            ?>
            <tr>
                <td class="text-center"><?= $no++ ?></td>
                <td class="text-center"><?= date('d/m/Y H:i', strtotime($key['tgl_tabungan_transaksi'])) ?></td>
                <td><span class="font-bold"><?= htmlspecialchars($key['nasabah_username']) ?></span></td>
                
                <td class="text-right text-success font-bold">
                    <?= $key['debit'] > 0 ? '+ Rp ' . number_format($key['debit'], 0, ',', '.') : '-' ?>
                </td>
                
                <td class="text-right text-danger font-bold">
                    <?= $key['kredit'] > 0 ? '- Rp ' . number_format($key['kredit'], 0, ',', '.') : '-' ?>
                </td>
                
                <td>
                    <?php 
                        $detail_teks = [];
                        foreach($detail->result_array() as $result){ 
                            if($result['id_transaksi_sampah'] == $key['id_transaksi_sampah']){
                                $detail_teks[] = '<span class="badge">' . htmlspecialchars($result['jenis_sampah']) . ' (' . $result['berat_sampah'] . ' kg)</span>';
                            }
                        }
                        
                        // Jika ada detail sampah tampilkan, jika kosong berarti ini transaksi Penarikan Uang
                        echo !empty($detail_teks) ? implode(', ', $detail_teks) : '<i style="color:#999; font-size:11px;">Penarikan Saldo Tabungan</i>';
                    ?>
                </td>
            </tr>
            <?php endforeach; ?>
            
            <?php if($laporan->num_rows() == 0): ?>
            <tr>
                <td colspan="6" class="text-center" style="padding: 20px; color: #777;">
                    <i>Tidak ada transaksi pada periode tanggal ini.</i>
                </td>
            </tr>
            <?php endif; ?>
        </tbody>
        
        <tfoot>
            <tr>
                <th colspan="3" class="text-right">TOTAL KESELURUHAN</th>
                <th class="text-right">Rp <?= number_format($total_setor, 0, ',', '.') ?></th>
                <th class="text-right">Rp <?= number_format($total_tarik, 0, ',', '.') ?></th>
                <th></th>
            </tr>
        </tfoot>
    </table>
</body>
</html>