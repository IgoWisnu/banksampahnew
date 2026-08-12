<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_stok extends CI_Model {

    public function get_stok_realtime($banjar_id = null)
    {
        $this->db->select('id, jenis_sampah, kategori_sampah, stok_tersisa');
        $this->db->from('jenis_sampah');
        if ($banjar_id != null) {
            $this->db->where('banjar_id', $banjar_id);
        }
        $this->db->order_by('kategori_sampah', 'ASC');
        $this->db->order_by('jenis_sampah', 'ASC');
        return $this->db->get()->result();
    }

    public function get_stok_ledger($banjar_id = null)
    {
        $this->db->select('sl.*, js.jenis_sampah, js.kategori_sampah');
        $this->db->from('stok_log sl');
        $this->db->join('jenis_sampah js', 'js.id = sl.id_jenis_sampah');
        if ($banjar_id != null) {
            $this->db->where('sl.banjar_id', $banjar_id);
        }
        $this->db->order_by('sl.created_at', 'DESC');
        return $this->db->get()->result();
    }
}
