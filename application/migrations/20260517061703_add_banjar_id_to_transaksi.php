<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Migration: Add banjar_id foreign key to transaction tables
 *
 * tabungan_transaksi  — the main financial transaction table
 * transaksi_sampah    — the waste collection transaction table
 *
 * Both are linked to banjar so each company's transactions are isolated.
 */
class Migration_Add_banjar_id_to_transaksi extends CI_Migration {

    public function up()
    {
        $field = array(
            'banjar_id' => array(
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => TRUE,
                'null'       => TRUE,
            ),
        );

        // --- tabungan_transaksi ---
        $this->dbforge->add_column('tabungan_transaksi', $field);
        $this->db->query('ALTER TABLE `tabungan_transaksi`
            ADD CONSTRAINT `fk_tabungan_transaksi_banjar`
            FOREIGN KEY (`banjar_id`)
            REFERENCES `banjar` (`id`)
            ON DELETE SET NULL
            ON UPDATE CASCADE');

        // --- transaksi_sampah ---
        $this->dbforge->add_column('transaksi_sampah', $field);
        $this->db->query('ALTER TABLE `transaksi_sampah`
            ADD CONSTRAINT `fk_transaksi_sampah_banjar`
            FOREIGN KEY (`banjar_id`)
            REFERENCES `banjar` (`id`)
            ON DELETE SET NULL
            ON UPDATE CASCADE');
    }

    public function down()
    {
        $this->db->query('ALTER TABLE `tabungan_transaksi` DROP FOREIGN KEY `fk_tabungan_transaksi_banjar`');
        $this->dbforge->drop_column('tabungan_transaksi', 'banjar_id');

        $this->db->query('ALTER TABLE `transaksi_sampah` DROP FOREIGN KEY `fk_transaksi_sampah_banjar`');
        $this->dbforge->drop_column('transaksi_sampah', 'banjar_id');
    }
}
