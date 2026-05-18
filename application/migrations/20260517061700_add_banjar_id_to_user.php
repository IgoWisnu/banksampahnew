<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Migration: Add banjar_id foreign key to user table
 *
 * The user table already exists, so we only ALTER it to add banjar_id column.
 * This links every user account to a banjar (company/organization).
 * banjar_id is stored in session at login so all child records (jenis_sampah,
 * artikel, transaksi) can inherit it automatically without a form input.
 */
class Migration_Add_banjar_id_to_user extends CI_Migration
{

    public function up()
    {
        // 1. Buat kolom banjar_id terlebih dahulu
        $fields = array(
            'banjar_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => TRUE,
                'after' => 'id' // Mengatur posisi kolom baru setelah kolom id
            ),
        );
        $this->dbforge->add_column('user', $fields);

        // 2. Tambahkan foreign key constraint setelah kolomnya sukses dibuat
        // Catatan: Pastikan nama tabel referensinya benar (di sini saya samakan ke 'banjari' sesuai skrip sebelumnya)
        $this->db->query('ALTER TABLE `user`
            ADD CONSTRAINT `fk_user_banjar`
            FOREIGN KEY (`banjar_id`)
            REFERENCES `banjari` (`id`)
            ON DELETE SET NULL
            ON UPDATE CASCADE');

        // 3. Opsional: Mengisi data awal (seeding) secara acak untuk user yang sudah ada
        $this->db->query('UPDATE `user` SET `banjar_id` = (SELECT `id` FROM `banjari` ORDER BY RAND() LIMIT 1)');
    }

    public function down()
    {
        // Urutan down harus memutus foreign key dulu, baru drop kolomnya
        $this->db->query('ALTER TABLE `user` DROP FOREIGN KEY `fk_user_banjar`');
        $this->dbforge->drop_column('user', 'banjar_id');
    }
}