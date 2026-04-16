<?php
    
    defined('BASEPATH') OR exit('No direct script access allowed');
    
    class Jenissampah extends CI_Controller {
    
        
        public function __construct()
        {
            parent::__construct();
            $this->load->model('m_jenis_sampah');

            if($this->session->userdata('role') == ''){
                redirect('auth');
            }
        }
        
        public function index()
        {
            $this->load->library('pagination');

            $config['base_url'] = base_url('jenissampah/index');
            $config['total_rows'] = $this->m_jenis_sampah->get_count();
            $config['per_page'] = 10;
            
            // Tailwind CSS configuration for pagination
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
            
            $data['sampah'] = $this->m_jenis_sampah->get_paginated($config["per_page"], $page);
            $data['pagination'] = $this->pagination->create_links();

            $this->load->view('banksampah/tabelsampah', $data);
        }
    
    }
    
    /* End of file jenissampah.php */
    
?>