<?php
    
    defined('BASEPATH') OR exit('No direct script access allowed');
    
    class Home extends CI_Controller {

        
        public function __construct()
        {
            parent::__construct();
            $this->load->model('m_home');
            
            if($this->session->userdata('role') == ''){
                redirect('auth');
            }
        }
        
    
        public function index()
        {
            redirect('home/loadArtikel');
        }

        public function berita(){
            $this->load->view('banksampah/berita');
        }

        public function verif(){
            $this->load->view('banksampah/Verifikasi');
            
        }

        public function Riwayat(){
            $this->load->view('banksampah/riwayat');
            
        }

        public function invoice(){
            $this->load->view('banksampah/invoice');
            
        }

        public function tarik(){
            $this->load->view('banksampah/tarik');
            
        }

        public function setor(){
            $this->load->view('banksampah/setor');
            
        }

        public function loadArtikel(){
        //user data
        $username = $this->session->userdata('username');
        $data['username'] = $username;

        //load artikel
        $this->load->model('m_artikel');
        $data['artikel'] = $this->m_artikel->getData();

        //cek apakah user guest
        $id = $this->session->userdata('id');
        
        if($id == ''){
            $data['saldo'] = 'Rp 0'; 
        } else {
            $this->load->model('m_dashboard');
            // Ambil saldo dinamis
            $saldo_dinamis = $this->m_dashboard->getSaldoDinamis($id);
            
            if (is_numeric($saldo_dinamis)) {
                $data['saldo'] = 'Rp ' . number_format($saldo_dinamis, 0, ',', '.');
            } else {
                $data['saldo'] = $saldo_dinamis; 
            }
            // ======================
        }

        $this->load->view('banksampah/homepage', $data);
    }

        public function loadSetor(){
            if($this->session->userdata('role') == 'guest'){
                redirect('auth/regisGuest');
            }
            $data['profile'] = $this->m_home->loadProfile();

            $this->load->view('banksampah/v_setor', $data);               
        }

        public function loadTarik(){
            if($this->session->userdata('role') == 'guest'){
                redirect('auth/regisGuest');
            }
            $data['profile'] = $this->m_home->loadProfile();

            $this->load->view('banksampah/v_tarik', $data);               
        }
    
    }
    
    /* End of file home.php */
    
?>