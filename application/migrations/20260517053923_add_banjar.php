<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_banjar extends CI_Migration
{

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
        $this->dbforge->create_table('banjar');

        $data = array(
            array(
                'nama' => 'Banjar Dinas Kesambi',
                'alamat' => 'Jl. Raya Kesambi No. 45, Kerobokan, Badung',
                'email' => 'kesambi.banjar@gmail.com',
                'no_telp' => '081234567890',
                'status' => 'active'
            ),
            array(
                'nama' => 'Banjar Kaja Panjer',
                'alamat' => 'Jl. Waturenggong, Panjer, Denpasar Selatan',
                'email' => 'kaja.panjer@outlook.com',
                'no_telp' => '081987654321',
                'status' => 'active'
            ),
            array(
                'nama' => 'Banjar Ubud Kelod',
                'alamat' => 'Jl. Raya Ubud, Kecamatan Ubud, Gianyar',
                'email' => NULL,
                'no_telp' => '087765432109',
                'status' => 'inactive'
            )
        );

        $this->db->insert_batch('banjar', $data);
    }

    public function down()
    {
        $this->dbforge->drop_table('banjar');
    }
}
