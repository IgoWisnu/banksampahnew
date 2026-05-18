<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Migration: Add banjar_id foreign key to artikel table
 */
class Migration_Add_banjar_id_to_artikel extends CI_Migration
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
        $this->dbforge->add_column('artikel', $field);

        $this->db->query('ALTER TABLE `artikel`
            ADD CONSTRAINT `fk_artikel_banjar`
            FOREIGN KEY (`banjar_id`)
            REFERENCES `banjar` (`id`)
            ON DELETE SET NULL
            ON UPDATE CASCADE');
    }

    public function down()
    {
        $this->db->query('ALTER TABLE `artikel` DROP FOREIGN KEY `fk_artikel_banjar`');
        $this->dbforge->drop_column('artikel', 'banjar_id');
    }
}
