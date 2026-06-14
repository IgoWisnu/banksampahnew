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
                $this->session->set_flashdata('success', 'Berhasil! Password Anda telah direset. Silakan login kembali.');
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
            // ==========================================================
            // VALIDASI GOOGLE RECAPTCHA UNTUK REGISTER
            // ==========================================================
            $recaptchaResponse = $this->input->post('g-recaptcha-response');
            $secretKey = RECAPTCHA_SECRET_KEY; // <-- Memanggil dari constants.php

            if(empty($recaptchaResponse)) {
                $this->session->set_flashdata('failed', 'Silakan centang verifikasi "Saya bukan robot" terlebih dahulu!');
                // Kembalikan ke halaman register beserta pesan error
                $this->goRegister(); 
                return;
            }

            $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secretKey.'&response='.$recaptchaResponse);
            $responseData = json_decode($verifyResponse);

            if(!$responseData->success) {
                $this->session->set_flashdata('failed', 'Validasi reCAPTCHA gagal, silakan coba lagi!');
                $this->goRegister();
                return;
            }
            // ==========================================================

            // Jika reCAPTCHA aman, baru lanjutkan validasi form CodeIgniter
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
            // PERBAIKAN: Gunakan aturan validasi khusus login, bukan dari model register
            $this->form_validation->set_rules('username', 'Username', 'required', [
                'required' => 'Username wajib diisi!'
            ]);
            $this->form_validation->set_rules('password', 'Password', 'required', [
                'required' => 'Password wajib diisi!'
            ]);

            // Jika form kosong, kembalikan ke halaman login agar muncul peringatan
            if ($this->form_validation->run() == FALSE) {
                $this->load->view('banksampah/login');
                return;
            }

            // ==========================================================
            // VALIDASI GOOGLE RECAPTCHA (SATPAM PINTU DEPAN)
            // ==========================================================
            $recaptchaResponse = $this->input->post('g-recaptcha-response');
            $secretKey = RECAPTCHA_SECRET_KEY; // <-- Panggil tanpa tanda kutip karena ini konstanta

            // Cek apakah user lupa mencentang captcha
            if(empty($recaptchaResponse)) {
                $this->session->set_flashdata('failed', 'Silakan centang verifikasi "Saya bukan robot" terlebih dahulu!');
                redirect('auth');
                return;
            }

            // Hubungi server Google untuk memvalidasi centangan user
            $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secretKey.'&response='.$recaptchaResponse);
            $responseData = json_decode($verifyResponse);

            // Jika Google bilang captcha-nya tidak valid / kadaluarsa
            if(!$responseData->success) {
                $this->session->set_flashdata('failed', 'Validasi reCAPTCHA gagal, silakan coba lagi!');
                redirect('auth');
                return;
            }
            // ==========================================================

            $username = $this->input->post('username');
            $password = $this->input->post('password');

            // Cek database
            $this->load->model('M_auth');
            $data = $this->M_auth->checkUser($username);

            if ($data->num_rows() == 1) {
                // Pakai row_array() agar lebih simpel tanpa foreach
                $user = $data->row_array(); 
                
                if(password_verify($password, $user['password'])){
                    // PASSWORD BENAR -> Eksekusi Login
                    $sess = array(
                        'id'         => $user['id_user'],
                        'username'   => $user['username'],
                        'role'       => $user['role'],
                        'admin_name' => $user['admin_name'],
                        'banjar_id'  => $user['banjar_id'] ?? null, 
                    );
                    $this->session->set_userdata($sess);
                    $this->session->set_flashdata('success', 'Login berhasil!');
                    
                    if($sess['role'] == 'superadmin'){
                        redirect('superadmin');
                    } elseif($sess['role'] == 'admin'){
                        redirect('dashboard');
                    } else{
                        redirect('home');
                    }
                } else {
                    // PASSWORD SALAH: Kirim pesan error SweetAlert
                    $this->session->set_flashdata('failed', 'Username atau Password salah!');
                    redirect('auth');
                }
            } else {
                // USERNAME TIDAK ADA: Kirim pesan error SweetAlert
                $this->session->set_flashdata('failed', 'Username atau Password salah!');
                redirect('auth');
            }
        }

        public function goRegister(){
            $this->load->model('M_banjar');
            $data['banjars'] = $this->M_banjar->get_all();
            $this->load->view('banksampah/v_register', $data);
        }

        // ==========================================================
        // FUNGSI UBAH PASSWORD KHUSUS ADMIN & SUPERADMIN
        // ==========================================================
        public function ubahPasswordAdmin()
        {
            // Pastikan hanya admin & superadmin yang boleh mengakses
            if ($this->session->userdata('role') == 'user' || empty($this->session->userdata('role'))) {
                show_error('Akses Ditolak!', 403);
            }

            $id_user = $this->session->userdata('id');
            $old_password = $this->input->post('old_password');
            $new_password = $this->input->post('new_password');
            
            // 1. Ambil data admin yang sedang login dari database
            $user = $this->db->get_where('user', ['id_user' => $id_user])->row();

            // 2. Verifikasi apakah password lama yang dimasukkan benar
            if (password_verify($old_password, $user->password)) {
                
                // 3. Hash password baru
                $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);

                // 4. Update ke database
                $this->db->where('id_user', $id_user);
                $this->db->update('user', ['password' => $hashed_new_password]);

                $this->session->set_flashdata('success', 'Password Anda berhasil diperbarui!');
            } else {
                // Jika password lama salah
                $this->session->set_flashdata('failed', 'Password Lama yang Anda masukkan salah!');
            }

            // 5. Kembalikan ke halaman sebelumnya (Superadmin ke superadmin, Admin ke dashboard)
            if ($this->session->userdata('role') == 'superadmin') {
                redirect('superadmin');
            } else {
                redirect('dashboard');
            }
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