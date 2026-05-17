<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_banjari extends CI_Model {

    public function get_all()
    {
        return $this->db->get('banjari')->result();
    }

    public function get_by_id($id)
    {
        return $this->db->get_where('banjari', ['id' => $id])->row();
    }

    public function insert($data)
    {
        return $this->db->insert('banjari', $data);
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('banjari', $data);
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('banjari');
    }

}
