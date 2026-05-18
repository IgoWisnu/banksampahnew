<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Migration: Add margin_value column to banjar table
 *
 * Kolom margin_value menggunakan tipe DECIMAL untuk menyimpan nilai
 * persentase keuntungan (misal: 10.50 untuk 10.5%).
 */
class Migration_Add_margin_value_to_banjar extends CI_Migration
{

    public function up()
    {
        // 1. Definisikan kolom margin_value baru
        $fields = array(
            'margin_value' => array(
                'type' => 'DECIMAL',
                'constraint' => '5,2',     // 5 digit total, 2 digit di belakang koma
                'null' => TRUE,       // TRUE agar data banjar lama tidak error
                'default' => 0.00,       // Nilai default awal 0.00
                'after' => 'status'    // Memposisikan setelah kolom status (sesuaikan struktur tabelmu)
            ),
        );

        // 2. Eksekusi penambahan kolom ke tabel banjar
        // Catatan: Ubah 'banjar' menjadi 'banjari' jika nama tabelmu menggunakan akhiran 'i'
        $this->dbforge->add_column('banjar', $fields);
    }

    public function down()
    {
        // Menghapus kembali kolom margin_value jika dilakukan rollback
        $this->dbforge->drop_column('banjar', 'margin_value');
    }
}