<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Banjari extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_banjar');
        $this->load->library(['form_validation', 'session']);
        $this->load->helper(['url', 'form']);

        // Only admin can manage banjar data
        if ($this->session->userdata('role') != 'admin') {
            redirect('auth');
        }
    }

    /**
     * List all banjar with pagination
     */
    public function index()
    {
        $data['title']  = 'Data Banjar';
        $data['banjar'] = $this->M_banjar->get_all();
        $this->load->view('banksampah/banjar/index', $data);
    }

    /**
     * Show form to create a new banjar
     */
    public function create()
    {
        $data['title'] = 'Tambah Banjar';
        $this->load->view('banksampah/banjar/create', $data);
    }

    /**
     * Handle POST to save a new banjar
     */
    public function store()
    {
        $this->form_validation->set_rules('nama',    'Nama',    'required|trim');
        $this->form_validation->set_rules('alamat',  'Alamat',  'trim');
        $this->form_validation->set_rules('email',   'Email',   'trim|valid_email');
        $this->form_validation->set_rules('no_telp', 'No. Telp','trim');
        $this->form_validation->set_rules('status',  'Status',  'required');

        if ($this->form_validation->run() == FALSE) {
            $data['title'] = 'Tambah Banjar';
            $this->load->view('banksampah/banjar/create', $data);
        } else {
            $payload = [
                'nama'    => $this->input->post('nama'),
                'alamat'  => $this->input->post('alamat'),
                'email'   => $this->input->post('email'),
                'no_telp' => $this->input->post('no_telp'),
                'status'  => $this->input->post('status'),
            ];

            $result = $this->M_banjar->insert($payload);

            if ($result) {
                $this->session->set_flashdata('success', 'Data banjar berhasil ditambahkan.');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan data banjar.');
            }
            redirect('banjari');
        }
    }

    /**
     * Show form to edit an existing banjar
     */
    public function edit($id)
    {
        $data['title']  = 'Edit Banjar';
        $data['banjar'] = $this->M_banjar->get_by_id($id);

        if (!$data['banjar']) {
            $this->session->set_flashdata('error', 'Data banjar tidak ditemukan.');
            redirect('banjari');
        }

        $this->load->view('banksampah/banjar/edit', $data);
    }

    /**
     * Handle POST to update an existing banjar
     */
    public function update($id)
    {
        $this->form_validation->set_rules('nama',    'Nama',    'required|trim');
        $this->form_validation->set_rules('alamat',  'Alamat',  'trim');
        $this->form_validation->set_rules('email',   'Email',   'trim|valid_email');
        $this->form_validation->set_rules('no_telp', 'No. Telp','trim');
        $this->form_validation->set_rules('status',  'Status',  'required');

        if ($this->form_validation->run() == FALSE) {
            $data['title']  = 'Edit Banjar';
            $data['banjar'] = $this->M_banjar->get_by_id($id);
            $this->load->view('banksampah/banjar/edit', $data);
        } else {
            $payload = [
                'nama'    => $this->input->post('nama'),
                'alamat'  => $this->input->post('alamat'),
                'email'   => $this->input->post('email'),
                'no_telp' => $this->input->post('no_telp'),
                'status'  => $this->input->post('status'),
            ];

            $result = $this->M_banjar->update($id, $payload);

            if ($result) {
                $this->session->set_flashdata('success', 'Data banjar berhasil diperbarui.');
            } else {
                $this->session->set_flashdata('error', 'Gagal memperbarui data banjar.');
            }
            redirect('banjari');
        }
    }

    /**
     * Delete a banjar record
     */
    public function delete($id)
    {
        $result = $this->M_banjar->delete($id);

        if ($result) {
            $this->session->set_flashdata('success', 'Data banjar berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus data banjar.');
        }
        redirect('banjari');
    }

    /**
     * Show detail of a banjar record
     */
    public function show($id)
    {
        $data['title']  = 'Detail Banjar';
        $data['banjar'] = $this->M_banjar->get_by_id($id);

        if (!$data['banjar']) {
            $this->session->set_flashdata('error', 'Data banjar tidak ditemukan.');
            redirect('banjari');
        }

        $this->load->view('banksampah/banjar/show', $data);
    }

}

/* End of file Banjari.php */
