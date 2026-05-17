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
class Migration_Add_banjar_id_to_user extends CI_Migration {

    public function up()
    {
        // Add banjar_id column to user table
        $field = array(
            'banjar_id' => array(
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => TRUE,
                'null'       => TRUE,   // nullable so existing rows are not broken
                'after'      => 'id_user',
            ),
        );
        $this->dbforge->add_column('user', $field);

        // Add foreign key constraint
        $this->db->query('ALTER TABLE `user`
            ADD CONSTRAINT `fk_user_banjar`
            FOREIGN KEY (`banjar_id`)
            REFERENCES `banjar` (`id`)
            ON DELETE SET NULL
            ON UPDATE CASCADE');
    }

    public function down()
    {
        $this->db->query('ALTER TABLE `user` DROP FOREIGN KEY `fk_user_banjar`');
        $this->dbforge->drop_column('user', 'banjar_id');
    }
}
