<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Migration: Add margin column to tabungan_transaksi table
 *
 * Kolom margin ditambahkan untuk menyimpan keuntungan/selisih nilai 
 * dari transaksi tabungan sampah.
 */
class Migration_Add_margin_to_tabungan_transaksi extends CI_Migration
{

    public function up()
    {
        // 1. Definisikan kolom margin baru
        $fields = array(
            'margin' => array(
                'type'       => 'FLOAT',
                'constraint' => '10,2',
                'null'       => TRUE,       // Diatur TRUE agar data lama yang belum punya margin tidak error
                'default'    => 0,          // Nilai default 0 untuk transaksi baru
                'after'      => 'debit'     // Memposisikan kolom setelah kolom 'debit'
            ),
        );

        // 2. Eksekusi penambahan kolom ke tabel tabungan_transaksi
        $this->dbforge->add_column('tabungan_transaksi', $fields);
    }

    public function down()
    {
        // Menghapus kembali kolom margin jika dilakukan rollback (migrate down)
        $this->dbforge->drop_column('tabungan_transaksi', 'margin');
    }
}