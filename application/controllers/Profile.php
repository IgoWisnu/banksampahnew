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

        public function updateProfile(){
            // Konfigurasi upload
            $config['upload_path']          = "./uploads/profile"; 
            $config['allowed_types']        = 'jpg|png';
            $config['max_size']             = 10000;
            $config['max_width']            = 10000;
            $config['max_height']           = 10000;
        
            $this->upload->initialize($config);
        
            // If there's no file upload or the upload failed
            if ( !$this->upload->do_upload('userfile') && empty($_FILES['userfile']['name']))
            {
                // No image was uploaded, only update other data without updating the image
                $data = array(
                    'nama_lengkap' => $this->input->post('nama_lengkap'),
                    'tempat_lahir' => $this->input->post('tempat_lahir'),
                    'tanggal_lahir' => $this->input->post('tanggal_lahir'),
                    'alamat' => $this->input->post('alamat')
                    // No 'profile' field here, so the image won't be updated
                );
            }
            else if ($this->upload->do_upload('userfile'))
            {
                // If an image is uploaded successfully, include the image in the update
                $upload_data = $this->upload->data();
                $gambar = $upload_data['file_name'];
        
                $data = array(
                    'nama_lengkap' => $this->input->post('nama_lengkap'),
                    'tempat_lahir' => $this->input->post('tempat_lahir'),
                    'tanggal_lahir' => $this->input->post('tanggal_lahir'),
                    'alamat' => $this->input->post('alamat'),
                    'profile' => $gambar // Include image if uploaded
                );
            }
            else
            {
                // Handle the case where image upload fails
                echo "gagal tambah";
                $error = $this->upload->display_errors();
                echo $error;
                return;
            }
        
            // Update the profile with or without image
            $result = $this->m_profile->update($data);
            if($result){
                $this->m_profile->deleteProfile(); // Assuming this is used to delete the old profile image
                $this->session->set_flashdata('success', 'Profil berhasil di update');
                redirect('profile');
            } else {
                $this->session->set_flashdata('failed', 'Profil gagal di update');
                redirect('profile');
            }
        }
        
    
    }
    
    /* End of file profile.php */
    
?>