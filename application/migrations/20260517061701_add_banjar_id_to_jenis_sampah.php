<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Migration: Add banjar_id foreign key to jenis_sampah table
 */
class Migration_Add_banjar_id_to_jenis_sampah extends CI_Migration
{

    public function up()
    {
        $field = array(
            'banjar_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => TRUE,
                'after' => 'id',
            ),
        );
        $this->dbforge->add_column('jenis_sampah', $field);

        $this->db->query('ALTER TABLE `jenis_sampah`
            ADD CONSTRAINT `fk_jenis_sampah_banjar`
            FOREIGN KEY (`banjar_id`)
            REFERENCES `banjar` (`id`)
            ON DELETE SET NULL
            ON UPDATE CASCADE');
    }

    public function down()
    {
        $this->db->query('ALTER TABLE `jenis_sampah` DROP FOREIGN KEY `fk_jenis_sampah_banjar`');
        $this->dbforge->drop_column('jenis_sampah', 'banjar_id');
    }
}
