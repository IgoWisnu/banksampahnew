<?php
    
defined('BASEPATH') OR exit('No direct script access allowed');

class Jenissampah extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_jenis_sampah');
        $this->load->model('m_dashboard');

        if($this->session->userdata('role') == ''){
            redirect('auth');
        }
    }
    
    /**
     * Daftar jenis sampah dengan pagination (untuk halaman publik / nasabah).
     */
    public function index()
    {
        $this->load->library('pagination');

        $config['base_url'] = base_url('jenissampah/index');
        $config['total_rows'] = $this->m_jenis_sampah->get_count();
        $config['per_page'] = 10;
        
        // Konfigurasi Tailwind Pagination
        $config['full_tag_open'] = '<div class="flex items-center justify-center mt-6 space-x-2">';
        $config['full_tag_close'] = '</div>';
        $config['first_tag_open'] = '';
        $config['first_tag_close'] = '';
        $config['last_tag_open'] = '';
        $config['last_tag_close'] = '';
        $config['next_tag_open'] = '';
        $config['next_tag_close'] = '';
        $config['next_link'] = 'Next <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>';
        $config['prev_tag_open'] = '';
        $config['prev_tag_close'] = '';
        $config['prev_link'] = '<svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg> Prev';
        $config['cur_tag_open'] = '<span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-brand-green text-white font-semibold shadow text-sm">';
        $config['cur_tag_close'] = '</span>';
        $config['num_tag_open'] = '';
        $config['num_tag_close'] = '';
        $config['attributes'] = ['class' => 'inline-flex items-center justify-center px-3 py-1.5 rounded-full text-sm font-medium text-gray-600 hover:bg-green-50 hover:text-brand-green transition-colors'];

        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        
        // 1. Ambil data query sampah
        $query_sampah = $this->m_jenis_sampah->get_paginated($config["per_page"], $page);
        $sampah_array = $query_sampah->result_array();

        // 2. Looping untuk menambahkan data history terakhir ke tiap baris sampah
        foreach($sampah_array as &$item) {
            $this->db->where('id_jenis_sampah', $item['id']);
            $this->db->order_by('tgl_perubahan', 'DESC');
            $this->db->limit(1); // Ambil 1 data riwayat paling baru
            $history = $this->db->get('harga_sampah_history')->row_array();
            
            if($history) {
                $item['harga_lama'] = $history['harga_lama'];
                $item['keterangan'] = $history['keterangan'] ? $history['keterangan'] : '-';
            } else {
                // Jika tidak ada history (sampah baru), harga lama = harga sekarang
                $item['harga_lama'] = $item['harga_sampah']; 
                $item['keterangan'] = 'Harga awal';
            }
        }

        // 3. Simpan data yang sudah digabung ke variabel (Sekarang bentuknya Array, bukan Object Query)
        $data['sampah'] = $sampah_array; 
        $data['pagination'] = $this->pagination->create_links();

        $this->load->view('banksampah/tabelsampah', $data);
    }

    /**
     * Update harga sampah (khusus admin).
     * Auto-log perubahan ke harga_sampah_history.
     * Setelah update, semua nasabah yang punya stok jenis ini saldonya 
     * akan ikut berubah otomatis (via view v_saldo_dinamis_nasabah).
     */
    public function update()
    {
        // Auth check khusus admin
        if($this->session->userdata('role') != 'admin'){
            show_error('Akses ditolak', 403);
            return;
        }

        $id = $this->input->post('id');

        if(empty($id) || !is_numeric($id)){
            $this->session->set_flashdata('failed', 'ID jenis sampah tidak valid.');
            redirect('dashboard/loadDataSampah');
            return;
        }

        $data = array(
            'jenis_sampah'        => $this->input->post('jenis_sampah'),
            'kategori_sampah'     => $this->input->post('kategori_sampah'),
            'sub_kategori_sampah' => $this->input->post('sub_kategori_sampah'),
            'harga_sampah'        => intval($this->input->post('harga_sampah')),
        );

        $keterangan = $this->input->post('keterangan'); // alasan ubah harga

        $ok = $this->m_jenis_sampah->updateJenis($id, $data, $keterangan);

        if($ok){
            $this->session->set_flashdata('success', 
                'Jenis sampah berhasil diupdate. Saldo nasabah otomatis menyesuaikan harga baru.');
        } else {
            $this->session->set_flashdata('failed', 'Gagal mengupdate jenis sampah.');
        }

        redirect('dashboard/loadDataSampah');
    }

    /**
     * Tambah jenis sampah baru (khusus admin).
     */
    public function insert()
    {
        if($this->session->userdata('role') != 'admin'){
            show_error('Akses ditolak', 403);
            return;
        }

        $data = array(
            'jenis_sampah'        => $this->input->post('jenis_sampah'),
            'kategori_sampah'     => $this->input->post('kategori_sampah'),
            'sub_kategori_sampah' => $this->input->post('sub_kategori_sampah'),
            'harga_sampah'        => intval($this->input->post('harga_sampah')),
        );

        if($this->m_jenis_sampah->insertJenis($data)){
            $this->session->set_flashdata('success', 'Jenis sampah baru ditambahkan.');
        } else {
            $this->session->set_flashdata('failed', 'Gagal menambah jenis sampah.');
        }

        redirect('dashboard/loadDataSampah');
    }

    /**
     * Halaman riwayat perubahan harga (untuk admin & transparansi nasabah).
     */
    public function riwayatHarga()
    {
        $data['history'] = $this->m_jenis_sampah->getAllHargaHistory(100);

        // Cek role: kalau admin pakai template admin, kalau nasabah pakai view nasabah
        if($this->session->userdata('role') == 'admin'){
            $username = $this->session->userdata('username');
            $top['username']       = $username;
            $top['adminCount']     = $this->m_dashboard->getAdminCount();
            $top['nasabahCount']   = $this->m_dashboard->getNasabahCount();
            $top['transaksiCount'] = $this->m_dashboard->getTransaksiCount();
            $top['artikelCount']   = $this->m_dashboard->getArtikelCount();

            $this->load->view('template/header');
            $this->load->view('template/sidebar');
            $this->load->view('template/topbar', $top);
            $this->load->view('banksampah/riwayat_harga', $data);
            $this->load->view('template/footer');
        } else {
            $this->load->view('banksampah/riwayat_harga_nasabah', $data);
        }
    }

    /**
     * AJAX: ambil 1 jenis sampah (untuk fill form edit modal).
     */
    public function getDetail()
    {
        $id = $this->input->get('id');
        if(empty($id) || !is_numeric($id)){
            echo json_encode(['success' => false]);
            return;
        }

        $row = $this->m_jenis_sampah->getById($id);
        echo json_encode(['success' => true, 'data' => $row]);
    }
}