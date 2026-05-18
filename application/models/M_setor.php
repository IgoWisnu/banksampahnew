<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class M_setor extends CI_Model
{

    public function cariUser($query)
    {
        $this->db->select("*");
        $this->db->from("user");

        // Pastikan filter banjar_id selalu mengikat
        $this->db->where('banjar_id', $this->session->userdata('banjar_id'));

        if ($query != '') {
            // Mulai tanda kurung: AND (
            $this->db->group_start();
            $this->db->like('id_user', $query);
            $this->db->or_like('username', $query);
            $this->db->or_like('email', $query);
            $this->db->group_end();
            // Tutup tanda kurung: )
        }

        $this->db->order_by('username', 'DESC');
        return $this->db->get();
    }

    public function cariHarga($id)
    {
        $this->db->select('harga_sampah');
        $this->db->where('id', $id);
        $harga = $this->db->get('jenis_sampah');
        if ($harga->num_rows() > 0) {
            // Retrieve the result
            $row = $harga->row();
            $harga_value = $row->harga_sampah;

            // Now $harga_value contains the 'harga' field value for the specified 'id'
            return $harga_value;
        } else {
            return 1;
        }
    }

    public function insertSampah()
    {
        //set local timezone
        date_default_timezone_set('Asia/Makassar');

        $data = array(
            'id_user_staff' => $this->session->userdata('id'),
            'id_user_nasabah' => $this->input->post('id_user'),
            'total_transaksi' => 0,
            'banjar_id' => $this->session->userdata('banjar_id'),
            'tgl_transaksi' => date('y-m-d H:i:s')
        );
        $this->db->insert('transaksi_sampah', $data);
        return $this->db->insert_id();
    }

    public function insertDtSampah($id)
    {
        foreach ($this->input->post('id_jenis_sampah') as $key => $value) {
            $data = array(
                'id_transaksi_sampah' => $id,
                'id_jenis_sampah' => $value,
                'berat_sampah' => $this->input->post('berat_sampah')[$key],
                'total_harga' => $this->input->post('harga_sampah')[$key]
            );
            $this->db->insert('transaksi_sampahdetail', $data);
        }
    }

    public function updateTotal($id)
    {
        $this->db->select_sum('total_harga', 'total');
        $this->db->where('id_transaksi_sampah', $id);
        $data = $this->db->get('transaksi_sampahdetail')->row()->total;

        $this->db->set('total_transaksi', $data);
        $this->db->where('id_transaksi_sampah', $id);
        $query = $this->db->update('transaksi_sampah');
        return $data;
    }

    public function cariIdTabungan()
    {
        $id = $this->input->post('id_user');
        $query = $this->db->select('id_tabungan')
            ->from('tabungan')
            ->where('id_user_nasabah', $id)
            ->get();

        if ($query->num_rows() > 0) {
            $result = $query->row();
            $id_tabungan = $result->id_tabungan;
            return $id_tabungan;
        } else {
            return null;
        }
    }

    public function insertTabungan($id, $id_tabungan, $total)
    {
        //set local timezone
        date_default_timezone_set('Asia/Makassar');

        $data = array(
            'id_transaksi_sampah' => $id,
            'id_tabungan' => $id_tabungan,
            'id_user_staff' => $this->session->userdata('id'),
            'kredit' => 0,
            'debit' => $total,
            'banjar_id' => $this->session->userdata('banjar_id'),
            'tgl_tabungan_transaksi' => date('y-m-d H:i:s')
        );
        $this->db->insert('tabungan_transaksi', $data);
    }

    public function updateDebitSaldo($id_tabungan, $total)
    {
        $this->db->set('saldo', 'saldo + ' . $total, FALSE);
        $this->db->where('id_tabungan', $id_tabungan);
        $query = $this->db->update('tabungan');
    }

    public function loadSelect()
    {
        $this->db->select('id, jenis_sampah');
        $query = $this->db->get('jenis_sampah');
        return $query;
    }

}

/* End of file m_setor.php */

?>