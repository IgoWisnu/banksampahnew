<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_jual extends CI_Model {

    public function loadJenisSampahWithStok()
    {
        $this->db->select('id, jenis_sampah, kategori_sampah, sub_kategori_sampah, harga_sampah, stok_tersisa');
        $banjar_id = $this->session->userdata('banjar_id');
        $role = $this->session->userdata('role');

        if (!empty($banjar_id) && $role != 'superadmin') {
            $this->db->group_start();
            $this->db->where('banjar_id', $banjar_id);
            $this->db->or_where('banjar_id', NULL);
            $this->db->group_end();
        }
        $this->db->order_by('jenis_sampah', 'ASC');
        return $this->db->get('jenis_sampah');
    }

    public function getStokTersisa($id)
    {
        $this->db->select('stok_tersisa');
        $this->db->where('id', $id);
        $res = $this->db->get('jenis_sampah');
        if ($res->num_rows() > 0) {
            return floatval($res->row()->stok_tersisa);
        }
        return 0;
    }

    public function insertJualSampah($nama_buyer, $biaya_tambahan, $keterangan_biaya, $status_pembayaran)
    {
        date_default_timezone_set('Asia/Makassar');

        $tgl_pelunasan = ($status_pembayaran == 'Lunas') ? date('Y-m-d H:i:s') : NULL;
        $no_invoice = 'INV-JUAL-' . date('YmdHis') . '-' . rand(100, 999);

        $data = array(
            'no_invoice'        => $no_invoice,
            'tipe_transaksi'    => 'jual',
            'id_user_staff'     => $this->session->userdata('id'),
            'nama_pihak_luar'   => $nama_buyer,
            'total_transaksi'   => 0,
            'status_pembayaran' => $status_pembayaran,
            'biaya_tambahan'    => intval($biaya_tambahan),
            'keterangan_biaya'  => $keterangan_biaya,
            'grand_total'       => 0,
            'banjar_id'         => $this->session->userdata('banjar_id'),
            'tgl_transaksi'     => date('Y-m-d H:i:s'),
            'tgl_pelunasan'     => $tgl_pelunasan
        );
        $this->db->insert('transaksi_sampah', $data);
        return $this->db->insert_id();
    }

    public function insertDtJualSampah($id_transaksi, $list_id_jenis, $list_berat, $list_harga_manual)
    {
        $subtotal_items = 0;

        foreach ($list_id_jenis as $key => $id_jenis) {
            $berat = floatval($list_berat[$key]);
            $harga_manual = intval($list_harga_manual[$key]);
            $total_item = floor($berat * $harga_manual);
            $subtotal_items += $total_item;

            // 1. Simpan detail transaksi
            $data_detail = array(
                'id_transaksi_sampah' => $id_transaksi,
                'id_jenis_sampah'     => $id_jenis,
                'harga_satuan'        => $harga_manual,  // BARU: Input harga manual per unit
                'berat_sampah'        => $berat,
                'berat_sisa'          => 0,
                'harga_saat_setor'    => $harga_manual,
                'total_harga'         => $total_item
            );
            $this->db->insert('transaksi_sampahdetail', $data_detail);

            // 2. Kurangi stok_tersisa di jenis_sampah
            $stok_sebelum = $this->getStokTersisa($id_jenis);
            $stok_sesudah = max(0, $stok_sebelum - $berat);

            $this->db->set('stok_tersisa', $stok_sesudah);
            $this->db->where('id', $id_jenis);
            $this->db->update('jenis_sampah');

            // 3. Catat audit log pergerakan stok (keluar)
            $log_data = array(
                'banjar_id'       => $this->session->userdata('banjar_id'),
                'id_jenis_sampah' => $id_jenis,
                'tipe_pergerakan' => 'keluar',
                'jumlah'          => $berat,
                'stok_sebelum'    => $stok_sebelum,
                'stok_sesudah'    => $stok_sesudah,
                'ref_invoice_id'  => $id_transaksi,
                'keterangan'      => 'Penjualan Sampah (Jual)'
            );
            $this->db->insert('stok_log', $log_data);
        }

        return $subtotal_items;
    }

    public function updateHeaderTotals($id_transaksi, $subtotal_items)
    {
        $this->db->select('biaya_tambahan');
        $this->db->where('id_transaksi_sampah', $id_transaksi);
        $biaya = $this->db->get('transaksi_sampah')->row()->biaya_tambahan;

        $grand_total = $subtotal_items + intval($biaya);

        $this->db->set('total_transaksi', $subtotal_items);
        $this->db->set('grand_total', $grand_total);
        $this->db->where('id_transaksi_sampah', $id_transaksi);
        $this->db->update('transaksi_sampah');
    }
}
