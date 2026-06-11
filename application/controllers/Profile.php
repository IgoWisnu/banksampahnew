<?php
ob_start();
    
    defined('BASEPATH') OR exit('No direct script access allowed');
    
    class Profile extends CI_Controller {

        
        public function __construct()
        {
            parent::__construct();
            $this->load->model('m_profile');
            
            if($this->session->userdata('role') == ''){
                redirect('auth');
            } elseif($this->session->userdata('role') == 'guest'){
                redirect('auth/regisGuest');
            }
        }
        
    
        public function index()
        {
            $data['profile'] = $this->m_profile->loadProfile(); 
            $this->load->view('banksampah/profile', $data);
            
        }

        public function tesProfile(){
            $this->load->view('banksampah/profile');
        }

        public function editProfile(){
            $id = $this->input->get('id');

            $data['user'] = $this->m_profile->editProfile($id);
            $this->load->view('banksampah/editprofile', $data);
        }

        public function ubahPassword()
        {
            // 1. Ambil input dari form
            $old_password = $this->input->post('old_password');
            $new_password = $this->input->post('new_password');
            
            // 2. Ambil ID nasabah yang sedang login
            $id_user = $this->session->userdata('id');
            
            // 3. Ambil data password lama (hash) dari database
            $user = $this->db->get_where('user', ['id_user' => $id_user])->row();
            
            if ($user) {
                // 4. Verifikasi apakah password lama yang diketik cocok dengan database
                if (password_verify($old_password, $user->password)) {
                    
                    // 5. Enkripsi password baru lalu simpan ke database
                    $hash_password = password_hash($new_password, PASSWORD_DEFAULT);
                    
                    $this->db->where('id_user', $id_user);
                    $this->db->update('user', ['password' => $hash_password]);
                    
                    // 6. Tampilkan pesan sukses
                    $this->session->set_flashdata('success', 'Password Anda berhasil diubah!');
                    
                } else {
                    // Password lama salah
                    $this->session->set_flashdata('failed', 'Password lama yang Anda masukkan salah!');
                }
            } else {
                $this->session->set_flashdata('failed', 'Data user tidak ditemukan.');
            }
            
            // Redirect kembali ke halaman profile
            redirect('profile');
        }

        public function updateProfile(){
            $upload_path = './uploads/profile/';

            // Cek apakah folder sudah ada? Jika belum, buat foldernya otomatis!
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0777, TRUE);
            }

            // Konfigurasi upload
            $config['upload_path']          = $upload_path; 
            $config['allowed_types']        = 'jpg|jpeg|png'; // Yang tadi kita perbaiki
            $config['max_size']             = 10000;
            $config['max_width']            = 10000;
            $config['max_height']           = 10000;
            $config['encrypt_name']  = TRUE;

            $this->upload->initialize($config);

            if ( !$this->upload->do_upload('userfile'))
            {
                $error = $this->upload->display_errors('', '');
                $this->session->set_flashdata('failed', 'Gagal upload foto: ' . $error);
                redirect('profile'); 
            }
            else
            {
                $upload_data = $this->upload->data();
                $gambar = $upload_data['file_name'];
                echo $gambar;
                $data = array(
                    'nama_lengkap' => $this->input->post('nama_lengkap'),
                    'tempat_lahir' => $this->input->post('tempat_lahir'),
                    'tanggal_lahir' => $this->input->post('tanggal_lahir'),
                    'alamat' => $this->input->post('alamat'),
                    'profile' => $gambar
                );

                $result = $this->m_profile->update($data);
                if($result){
                    $this->m_profile->deleteProfile();
                    $this->session->set_flashdata('success', 'Profil berhasil di update');
                    redirect('profile');
                } else {
                    $this->session->set_flashdata('failed', 'Profil gagal di update');
                    redirect('profile');
                }
            }

        }
    
    }
    
    /* End of file profile.php */
    
?>