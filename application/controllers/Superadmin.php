<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Superadmin extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_banjar');
        $this->load->model('M_auth');
        $this->load->model('m_dashboard');
        
        if ($this->session->userdata('role') != 'superadmin') {
            redirect('auth');
        }
    }

    private function get_topbar_data() {
        $top['username'] = $this->session->userdata('username');
        $top['adminCount'] = $this->m_dashboard->getAdminCount();
        $top['nasabahCount'] = $this->m_dashboard->getNasabahCount();
        $top['transaksiCount'] = $this->m_dashboard->getTransaksiCount();
        $top['artikelCount'] = $this->m_dashboard->getArtikelCount();
        return $top;
    }

    public function index()
    {
        $top = $this->get_topbar_data();

        // Statistics specific to superadmin dashboard index
        $top['banjarCount'] = $this->db->count_all('banjar');
        // $top['adminCount'] is already loaded in get_topbar_data, which counts all admins

        $this->load->view('template/header');
        $this->load->view('template/sidebar');
        $this->load->view('template/topbar', $top);
        $this->load->view('superadmin/index', $top);
        $this->load->view('template/footer');
    }

    public function banjar()
    {
        $top = $this->get_topbar_data();

        $data['banjars'] = $this->M_banjar->get_all();

        $this->load->view('template/header');
        $this->load->view('template/sidebar');
        $this->load->view('template/topbar', $top);
        $this->load->view('superadmin/banjar', $data);
        $this->load->view('template/footer');
    }

    public function admin()
    {
        $top = $this->get_topbar_data();

        $this->db->select('user.*, banjar.nama');
        $this->db->from('user');
        $this->db->join('banjar', 'user.banjar_id = banjar.id', 'left');
        $this->db->where('user.role', 'admin');
        $data['admins'] = $this->db->get()->result_array();
        
        $data['banjars'] = $this->M_banjar->get_all();

        $this->load->view('template/header');
        $this->load->view('template/sidebar');
        $this->load->view('template/topbar', $top);
        $this->load->view('superadmin/admin', $data);
        $this->load->view('template/footer');
    }

    public function add_banjar()
    {
        $this->form_validation->set_rules('nama', 'Nama Banjar', 'required');
        $this->form_validation->set_rules('alamat', 'Alamat', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('failed', 'Nama Banjar dan Alamat wajib diisi!');
            redirect('superadmin/banjar');
        } else {
            $data = [
                'nama' => $this->input->post('nama'),
                'alamat' => $this->input->post('alamat'),
                'margin_value' => $this->input->post('margin_value') ?: 0
            ];
            $this->db->insert('banjar', $data);
            $this->session->set_flashdata('success', 'Banjar berhasil ditambahkan');
            redirect('superadmin/banjar');
        }
    }

    public function update_banjar()
    {
        $this->form_validation->set_rules('nama', 'Nama Banjar', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('failed', 'Nama Banjar wajib diisi!');
            redirect('superadmin/banjar');
        } else {
            $id = $this->input->post('id');
            $data = [
                'nama' => $this->input->post('nama'),
                'alamat' => $this->input->post('alamat'),
                'margin_value' => $this->input->post('margin_value') ?: 0
            ];
            $this->db->where('id', $id);
            $this->db->update('banjar', $data);
            $this->session->set_flashdata('success', 'Banjar berhasil diupdate');
            redirect('superadmin/banjar');
        }
    }

    public function delete_banjar($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('banjar');
        $this->session->set_flashdata('success', 'Banjar berhasil dihapus');
        redirect('superadmin/banjar');
    }

    public function add_admin()
    {
        // Setup validation rules based on existing Auth or custom
        $this->form_validation->set_rules('username', 'Username', 'required|is_unique[user.username]');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[user.email]');
        $this->form_validation->set_rules('password', 'Password', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('failed', validation_errors());
            redirect('superadmin/admin');
        }

        $data = [
            'username' => $this->input->post('username'),
            'email' => $this->input->post('email'),
            'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
            'role' => 'admin',
            'nama_lengkap' => $this->input->post('nama_lengkap'),
            'banjar_id' => $this->input->post('banjar_id'),
            'isVerif' => 1
        ];
        $this->db->insert('user', $data);
        $this->session->set_flashdata('success', 'Admin berhasil ditambahkan');
        redirect('superadmin/admin');
    }

    public function delete_admin($id)
    {
        $this->db->where('id_user', $id);
        $this->db->delete('user');
        $this->session->set_flashdata('success', 'Admin berhasil dihapus');
        redirect('superadmin/admin');
    }
}
