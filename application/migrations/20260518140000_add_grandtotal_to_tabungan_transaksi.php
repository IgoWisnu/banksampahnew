<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Migration: Add grandtotal column to tabungan_transaksi table
 *
 * Kolom grandtotal digunakan untuk menyimpan nilai akhir dari transaksi
 * setelah kalkulasi (Debit/Kredit + Margin).
 */
class Migration_Add_grandtotal_to_tabungan_transaksi extends CI_Migration
{

    public function up()
    {
        // 1. Definisikan kolom grandtotal baru
        $fields = array(
            'grandtotal' => array(
                'type' => 'INT',
                'constraint' => 11,
                'null' => TRUE,       // Diatur TRUE agar transaksi lama tidak error
                'default' => 0,          // Nilai default 0 untuk transaksi baru
                'after' => 'margin'    // Memposisikan setelah kolom margin yang kita buat tadi
            ),
        );

        // 2. Eksekusi penambahan kolom ke tabel tabungan_transaksi
        $this->dbforge->add_column('tabungan_transaksi', $fields);
    }

    public function down()
    {
        // Menghapus kembali kolom grandtotal jika dilakukan rollback
        $this->dbforge->drop_column('tabungan_transaksi', 'grandtotal');
    }
}