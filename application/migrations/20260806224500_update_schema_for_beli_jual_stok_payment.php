<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Migration: Update Schema for Beli, Jual, Stok System, & Payment Status
 */
class Migration_Update_schema_for_beli_jual_stok_payment extends CI_Migration
{
    public function up()
    {
        // 1. Tambahkan kolom stok_tersisa di jenis_sampah jika belum ada
        if (!$this->db->field_exists('stok_tersisa', 'jenis_sampah')) {
            $field_stok = array(
                'stok_tersisa' => array(
                    'type' => 'FLOAT',
                    'null' => FALSE,
                    'default' => 0,
                    'after' => 'harga_sampah',
                )
            );
            $this->dbforge->add_column('jenis_sampah', $field_stok);
        }

        // 2. Buat tabel stok_log jika belum ada
        if (!$this->db->table_exists('stok_log')) {
            $this->dbforge->add_field(array(
                'id_stok_log' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => FALSE,
                    'auto_increment' => TRUE
                ),
                'banjar_id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => TRUE,
                    'null' => TRUE
                ),
                'id_jenis_sampah' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'null' => FALSE
                ),
                'tipe_pergerakan' => array(
                    'type' => "ENUM('masuk','keluar')",
                    'null' => FALSE
                ),
                'jumlah' => array(
                    'type' => 'FLOAT',
                    'null' => FALSE
                ),
                'stok_sebelum' => array(
                    'type' => 'FLOAT',
                    'null' => FALSE
                ),
                'stok_sesudah' => array(
                    'type' => 'FLOAT',
                    'null' => FALSE
                ),
                'ref_invoice_id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'null' => TRUE
                ),
                'keterangan' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                    'null' => TRUE
                ),
                'created_at' => array(
                    'type' => 'DATETIME',
                    'null' => TRUE
                )
            ));
            $this->dbforge->add_key('id_stok_log', TRUE);
            $this->dbforge->create_table('stok_log', TRUE);
            
            // Set default CURRENT_TIMESTAMP via query
            $this->db->query("ALTER TABLE `stok_log` MODIFY `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP");
        }

        // 3. Tambahkan kolom di transaksi_sampah (Header Invoice)
        $fields_transaksi = array();

        if (!$this->db->field_exists('no_invoice', 'transaksi_sampah')) {
            $fields_transaksi['no_invoice'] = array(
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => TRUE,
                'after' => 'id_transaksi_sampah'
            );
        }

        if (!$this->db->field_exists('tipe_transaksi', 'transaksi_sampah')) {
            $fields_transaksi['tipe_transaksi'] = array(
                'type' => "ENUM('beli','jual')",
                'default' => 'beli',
                'null' => FALSE,
                'after' => 'no_invoice'
            );
        }

        if (!$this->db->field_exists('nama_pihak_luar', 'transaksi_sampah')) {
            $fields_transaksi['nama_pihak_luar'] = array(
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => TRUE,
                'after' => 'id_user_nasabah'
            );
        }

        if (!$this->db->field_exists('status_pembayaran', 'transaksi_sampah')) {
            $fields_transaksi['status_pembayaran'] = array(
                'type' => "ENUM('Pending','Lunas')",
                'default' => 'Lunas',
                'null' => FALSE,
                'after' => 'total_transaksi'
            );
        }

        if (!$this->db->field_exists('biaya_tambahan', 'transaksi_sampah')) {
            $fields_transaksi['biaya_tambahan'] = array(
                'type' => 'INT',
                'default' => 0,
                'null' => TRUE,
                'after' => 'status_pembayaran'
            );
        }

        if (!$this->db->field_exists('keterangan_biaya', 'transaksi_sampah')) {
            $fields_transaksi['keterangan_biaya'] = array(
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE,
                'after' => 'biaya_tambahan'
            );
        }

        if (!$this->db->field_exists('grand_total', 'transaksi_sampah')) {
            $fields_transaksi['grand_total'] = array(
                'type' => 'INT',
                'default' => 0,
                'null' => TRUE,
                'after' => 'keterangan_biaya'
            );
        }

        if (!$this->db->field_exists('tgl_pelunasan', 'transaksi_sampah')) {
            $fields_transaksi['tgl_pelunasan'] = array(
                'type' => 'DATETIME',
                'null' => TRUE,
                'after' => 'tgl_transaksi'
            );
        }

        if (!empty($fields_transaksi)) {
            $this->dbforge->add_column('transaksi_sampah', $fields_transaksi);
        }

        // 4. Tambahkan kolom di transaksi_sampahdetail (Detail Invoice & Fixed Price)
        if (!$this->db->field_exists('harga_satuan', 'transaksi_sampahdetail')) {
            $field_detail = array(
                'harga_satuan' => array(
                    'type' => 'INT',
                    'default' => 0,
                    'null' => FALSE,
                    'after' => 'id_jenis_sampah'
                )
            );
            $this->dbforge->add_column('transaksi_sampahdetail', $field_detail);
        }
    }

    public function down()
    {
        // Rollback schema changes
        if ($this->db->field_exists('harga_satuan', 'transaksi_sampahdetail')) {
            $this->dbforge->drop_column('transaksi_sampahdetail', 'harga_satuan');
        }

        $transaksi_cols = array('tgl_pelunasan', 'grand_total', 'keterangan_biaya', 'biaya_tambahan', 'status_pembayaran', 'nama_pihak_luar', 'tipe_transaksi', 'no_invoice');
        foreach ($transaksi_cols as $col) {
            if ($this->db->field_exists($col, 'transaksi_sampah')) {
                $this->dbforge->drop_column('transaksi_sampah', $col);
            }
        }

        if ($this->db->table_exists('stok_log')) {
            $this->dbforge->drop_table('stok_log', TRUE);
        }

        if ($this->db->field_exists('stok_tersisa', 'jenis_sampah')) {
            $this->dbforge->drop_column('jenis_sampah', 'stok_tersisa');
        }
    }
}
