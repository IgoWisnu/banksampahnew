<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Migration: Add debit_final column to tabungan_transaksi table
 *
 * Kolom debit_final digunakan untuk menyimpan nilai debit setelah 
 * dikalkulasikan dengan komponen biaya lainnya atau margin.
 */
class Migration_Add_debit_final_to_tabungan_transaksi extends CI_Migration
{

    public function up()
    {
        // Definisikan kolom debit_final sesuai dengan query SQL ALTER TABLE
        $fields = array(
            'debit_final' => array(
                'type' => 'INT',
                'constraint' => 11,
                'null' => TRUE,       // NULL
                'default' => 0,          // DEFAULT 0
                'after' => 'margin'    // AFTER `margin`
            ),
        );

        // Eksekusi penambahan kolom ke tabel tabungan_transaksi
        $this->dbforge->add_column('tabungan_transaksi', $fields);
    }

    public function down()
    {
        // Menghapus kembali kolom debit_final jika dilakukan rollback (migrate down)
        $this->dbforge->drop_column('tabungan_transaksi', 'debit_final');
    }
}