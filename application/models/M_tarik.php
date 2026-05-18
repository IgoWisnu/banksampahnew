<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class M_tarik extends CI_Model
{

    public function cariUserSaldo($keyword)
    {
        $this->db->select('*');
        $this->db->from('user');
        $this->db->where('banjar_id', $this->session->userdata('banjar_id'));
        $this->db->join('tabungan', 'user.id_user = tabungan.id_user_nasabah');
        $this->db->like('user.username', $keyword);
        $this->db->or_like('user.id_user', $keyword);
        $query = $this->db->get();

        return $query;
    }

    public function insertTrTarik()
    {
        //set local timezone
        date_default_timezone_set('Asia/Makassar');

        $data = array(
            'id_tabungan' => $this->input->post('id_tabungan'),
            'id_user_staff' => $this->session->userdata('id'),
            'kredit' => $this->input->post('tariksaldo'),
            'debit' => 0,
            'banjar_id' => $this->session->userdata('banjar_id'),
            'tgl_tabungan_transaksi' => date('y-m-d H:i:s')
        );
        $query = $this->db->insert('tabungan_transaksi', $data);
        return $query;
    }

    public function updateSaldo()
    {
        $id = $this->input->post('id_tabungan');
        $kredit = $this->input->post('tariksaldo');

        $this->db->set('saldo', 'saldo - ' . $kredit, FALSE);
        $this->db->where('id_tabungan', $id);
        $query = $this->db->update('tabungan');
    }

}

/* End of file m_tarik.php */

?>