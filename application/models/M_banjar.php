<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_banjar extends CI_Model
{

    public function get_all()
    {
        return $this->db->get('banjar')->result();
    }

    public function get_by_id($id)
    {
        return $this->db->get_where('banjar', ['id' => $id])->row();
    }

    public function insert($data)
    {
        return $this->db->insert('banjar', $data);
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('banjar', $data);
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('banjar');
    }

}
