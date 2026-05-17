<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_banjari extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'nama' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'alamat' => array(
                'type' => 'TEXT',
                'null' => TRUE,
            ),
            'email' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'no_telp' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => TRUE,
            ),
            'status' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'default' => 'active',
            ),
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('banjari');
    }

    public function down()
    {
        $this->dbforge->drop_table('banjari');
    }
}
