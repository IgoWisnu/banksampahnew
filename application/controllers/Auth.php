<?php
ob_start();
    
    defined('BASEPATH') OR exit('No direct script access allowed');
    
    class Auth extends CI_Controller {
        
        
        public function __construct()
        {
            parent::__construct();
            $this->load->model('m_auth');
            
        }        

        public function index()
        {
            $this->load->view('banksampah/login');
            
        }

        public function resetpassword()
        {
            $this->load->view('banksampah/resetpass');
            
        }

        public function login(){
            $this->load->view('banksampah/login');
        }

        public function regisGuest(){
            $this->load->view('banksampah/regisGuest');
        }

        // Fungsi 1: Menangkap input email dari form lupa password
        public function check_email()
        {
            $email = $this->input->post('email');
            $this->load->model('M_auth');

            // M_auth->requestPasswordReset akan membuat token dan mengirim email
            $result = $this->M_auth->requestPasswordReset($email);

            // Cek balasan dari model
            if (strpos($result, 'terkirim') !== false) {
                $this->session->set_flashdata('success', $result);
                redirect('auth/resetpassword'); // Pastikan ini mengarah ke view form input email
            } else {
                $this->session->set_flashdata('failed', $result);
                redirect('auth/resetpassword');
            }
        }

        // Fungsi 2: Mengeksekusi reset saat link di email diklik nasabah
        public function doResetToDefault()
        {
            // 1. Ambil token unik dari URL email
            $token = $this->input->get('token');
            
            // 2. Cek apakah token tersebut ada di database dan belum expired
            $user = $this->db->get_where('user', ['reset_token' => $token])->row();

            if ($user && strtotime($user->reset_token_expiry) > time()) {
                
                // 3. Jika token valid, enkripsi angka 12345678
                $new_password = password_hash('12345678', PASSWORD_DEFAULT);
                
                // 4. Update database user
                $this->db->where('id_user', $user->id_user);
                $this->db->update('user', [
                    'password' => $new_password,
                    'reset_token' => NULL, // Hapus token agar tidak bisa dipakai 2x
                    'reset_token_expiry' => NULL
                ]);

                // 5. Beri notifikasi sukses dan kembalikan ke halaman login
                $this->session->set_flashdata('success', 'Berhasil! Password Anda telah direset menjadi <b>12345678</b>. Silakan login.');
                redirect('auth');
                
            } else {
                // Jika token salah atau sudah expired (lebih dari 1 jam)
                $this->session->set_flashdata('failed', 'Link reset password tidak valid atau sudah kedaluwarsa.');
                redirect('auth/resetpassword');
            }
        }

        public function reset_password() {
            $email = $this->input->post('email');
            $old_password = $this->input->post('old_password');
            $new_password = $this->input->post('new_password');
        
            // Fetch the user by email
            $user = $this->db->get_where('user', ['email' => $email])->row();
        
            if ($user && password_verify($old_password, $user->password)) {
                // Update with the new password
                $this->db->update('user', [
                    'password' => password_hash($new_password, PASSWORD_DEFAULT)
                ], ['email' => $email]);
        
                $this->load->view('banksampah/login');
            } else {
                $this->load->view('banksampah/resetpass');
            }
        }
        
        
        
        public function mail(){
            $rules = $this->m_auth->validation();
            $this->form_validation->set_rules($rules);
            
            if($this->form_validation->run() == FALSE){
                $this->load->model('M_banjar');
                $data['banjars'] = $this->M_banjar->get_all();
                $this->load->view('banksampah/v_register', $data);
            } else{
                $mailCode = $this->m_auth->add();
                $email = $mailCode['email'];
                $kode_verif = $mailCode['kode_verif'];
                $data['verif'] = $this->m_auth->mail($kode_verif, $email);

                $this->load->view('banksampah/Verifikasi', $data);
            }

        }

        public function logout(){
            session_destroy();
            redirect('auth');
        }

        public function guestAccess(){
            $sess = array(
                'username' => 'guest',
                'role' => 'guest'
            );
            $this->session->set_userdata($sess);
            redirect('home');
        }

        public function cekLogin(){
            $rules = $this->m_auth->validation();
            $this->form_validation->set_rules($rules);

            $username = $this->input->post('username');
            $password = $this->input->post('password');

            $data = $this->m_auth->checkUser($username);

            if ($data->num_rows() == 1) {
                echo 'ada';
                foreach($data->result_array() as $key) {
                    if(password_verify($password, $key['password'])){
                        $data = $data->result_array();
                        $sess = array(
                            'id'         => $data[0]['id_user'],
                            'username'   => $data[0]['username'],
                            'role'       => $data[0]['role'],
                            'admin_name' => $data[0]['admin_name'],
                            'banjar_id'  => $data[0]['banjar_id'] ?? null, // stored so all controllers can use it like req.user.banjar_id
                        );
                        $this->session->set_userdata($sess);
                        $this->session->set_flashdata('alert','login berhasil!');
                        if($sess['role'] == 'superadmin'){
                            redirect('superadmin');
                        } elseif($sess['role'] == 'admin'){
                            redirect('dashboard');
                        } else{
                            redirect('home');
                        }
                    }
                    else{
                        redirect('auth');
                    }
                }
            }else{
                $this->session->set_flashdata('failed', 'Uername/Password salah');
                redirect('auth');
                
            }
        }

        public function goRegister(){
            $this->load->model('M_banjar');
            $data['banjars'] = $this->M_banjar->get_all();
            $this->load->view('banksampah/v_register', $data);
        }

        public function verify() {
            $token = $this->input->get('token');
            $verifyCheck = $this->m_auth->verify($token);

            if ($verifyCheck == 'verif'){
                $userId = $this->m_auth->getUser($token);
                $bukaTabungan = $this->m_auth->registerTabungan($userId);
                if($bukaTabungan){
                    $this->session->set_flashdata('success','Verifikasi berhasil'); 
                    redirect('auth/login');
                } else{
                    $this->session->set_flashdata('failed','Terjadi masalah pada sistem'); 
                    redirect('auth/login');
                }
            } elseif($verifyCheck == 'already_verif'){
                $this->session->set_flashdata('failed','Akun sudah diverifikasi');
                redirect('auth/login');
            } else{
                $this->session->set_flashdata('failed','Akun sudah diverifikasi');
                redirect('auth/login');
            }
        }
        

        public function tambahNasabah(){
            $username = $this->input->post('username');
            $password = $this->input->post('password');
            $nama_lengkap = $this->input->post('nama_lengkap');
            $alamat = $this->input->post('alamat');

        }
        
    
    }
    
    /* End of file Controllername.php */
    
?>