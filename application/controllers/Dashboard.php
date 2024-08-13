<?php
    
    defined('BASEPATH') OR exit('No direct script access allowed');
    
    class Dashboard extends CI_Controller {
            public function __construct() {
                parent::__construct();
                // Load model BeritaModel
                $this->load->model('m_dashboard');
                $this->load->library('pagination');
                $this->load->helper(array('form', 'url'));
                $this->load->library('upload');


                if(!$this->session->userdata('role') == 'admin'){
                    redirect('auth');
                }
            }
        
            public function tambahBerita() {
                // Konfigurasi upload
                $config['upload_path'] = "./uploads"; // Path to the upload folder
                $config['allowed_types'] = 'gif|jpg|png';  // Allowed file types
                $config['max_size'] = 204800;  // Maximum file size in KB
            
                $this->upload->initialize($config);
            
                if (!$this->upload->do_upload('gambarBerita')) {
                    // Handle upload error, if any
                    $error = array('error' => $this->upload->display_errors());
                    print_r($error);  // You might want to handle this more gracefully in a production environment
                } else {
                    // Upload successful, get the uploaded file data
                    $upload_data = $this->upload->data();
                    $gambarBerita = $upload_data['file_name'];  // Get the uploaded file name
            
                    // Call insertBerita function
                    // Call insertBerita function with the necessary data
                    $insert = $this->m_dashboard->insertBerita(
                        $gambarBerita
                    );

            
                    // Redirect atau tampilkan pesan sukses
                    if($insert){
                        $this->session->set_flashdata('success', 'Artikel berhasil ditambahkan');
                    } else{
                        $this->session->set_flashdata('failed', 'Artikel gagal ditambahkan');
                    }
                    redirect('dashboard/loadBerita'); // Ganti 'dashboard' dengan nama controller yang sesuai
                }
            }
            
            

            public function updateBerita() {
                // Ambil data dari form
                $id = $this->input->post('id');
        
                // Konfigurasi upload (jika diperlukan)
                $config['upload_path'] = "./uploads";
                $config['allowed_types'] = 'gif|jpg|png';
                $config['max_size'] = 204800;
        
                $this->upload->initialize($config);
        
                // Cek apakah ada file gambar yang diupload
                if ($_FILES['gambarBerita']['name']) {
                    // Lakukan proses upload gambar
                    if (!$this->upload->do_upload('gambarBerita')) {
                        $error = array('error' => $this->upload->display_errors());
                        print_r($error);
                        return;
                    }
                    
        
                    // Upload successful, get the uploaded file data
                    $upload_data = $this->upload->data();
                    $gambarBerita = $upload_data['file_name'];
                } else {
                    // Jika tidak ada file yang diupload, gunakan gambar yang sudah ada
                    $gambarBerita = $this->input->post('gambarBerita_existing');
                }
        
                // Simpan ke Array
                
        
                $update = $this->m_dashboard->updateBerita($id, $gambarBerita);
                
                if($update){
                    $this->session->set_flashdata('success', 'Artikel berhasil diupdate');
                } else{
                    $this->session->set_flashdata('failed', 'Artikel gagal diupdate');
                }
                // Redirect atau tampilkan pesan sukses
                redirect('dashboard/loadBerita');
            }

            public function tampilkanTabelNasabah() {
                $data['user'] = $this->m_dashboard->getData(); // Mengambil data nasabah dari model
            
                // Load view yang menampilkan tabel nasabah
                $this->load->view('banksampah/tnasabah', $data);
            }
            
            

        public function index(){
                $this->load->model('m_transaksi');
                $username = $this->session->userdata('username');
                $data['username'] = $username;

                $this->load->model('m_dashboard');  // Load the model
                $data['adminCount'] = $this->m_dashboard->getAdminCount();
                $data['nasabahCount'] = $this->m_dashboard->getNasabahCount();
                $data['transaksiCount'] = $this->m_dashboard->getTransaksiCount();
                $data['artikelCount'] = $this->m_dashboard->getArtikelCount();

                $this->load->view('template/header');
                $this->load->view('template/sidebar');
                $this->load->view('template/topbar', $data);
                $this->load->view('template/footer');
                
         }
            

        public function deleteb(){
            //get arikel id from url
            $id = $this->input->get('id');

            //execute delete on model
            $delete = $this->m_dashboard->deleteData($id);
            if($delete){
                $this->session->set_flashdata('success', 'Artikel berhasil dihapus');
            } else{
                $this->session->set_flashdata('failed', 'Artikel gagal dihapus');
            }
            redirect('dashboard/loadBerita');
        }
 
        public function editberita($id){
            $data['artikel'] = $this->m_dashboard->getBeritaById($id);
            
            $this->load->view('banksampah/edit_berita', $data);
            
        }

        public function loadNasabah(){
            //search handle
            if($this->input->post('keyword') == ''){
                $data['keyword'] =  null;
                $this->session->unset_userdata('keyword_nasabah');
            }elseif($this->input->post('keyword')){
                $data['keyword'] =  $this->input->post('keyword');
                $this->session->set_userdata('keyword_nasabah', $data['keyword'] );
            }elseif($this->session->userdata('keyword_nasabah')){
                $data['keyword'] = $this->session->userdata('keyword_nasabah');   
            }


            //get data count after the last query
            $this->db->where('role', 'user');
            $this->db->where('isVerif', '1');

            if($data['keyword']){
                $this->db->like('username', $data['keyword']);
            }
            $this->db->from('user');

            // Pagination Configuration
            $config['base_url'] = base_url().'dashboard/loadNasabah';
            $config['use_page_numbers'] = TRUE;
            $config['total_rows'] = $this->db->count_all_results();
            $config['per_page'] = 7;
             
             // Initialize
            $this->pagination->initialize($config);

            // Initialize data Array and pagination
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;
            $start = ($page - 1) * $config['per_page']; 
            $data['user'] = $this->m_dashboard->getUserData($config['per_page'],$start, $data['keyword']);
            $data['pagination'] = $this->pagination->create_links();

            //include the top bar
            $username = $this->session->userdata('username');
            $top['username'] = $username;
            $top['adminCount'] = $this->m_dashboard->getAdminCount();
            $top['nasabahCount'] = $this->m_dashboard->getNasabahCount();
            $top['transaksiCount'] = $this->m_dashboard->getTransaksiCount();
            $top['artikelCount'] = $this->m_dashboard->getArtikelCount();

            $this->load->view('template/header');
            $this->load->view('template/sidebar');
            $this->load->view('template/topbar', $top);
            $this->load->view('banksampah/tabelnasabah', $data);
            $this->load->view('template/footer');
            
        }

        public function loadBerita(){
             //get data count
             $beritaCount = $this->m_dashboard->getArtikelCount();

             // Pagination Configuration
             $config['base_url'] = base_url().'dashboard/loadBerita';
             $config['use_page_numbers'] = TRUE;
             $config['total_rows'] = $beritaCount;
             $config['per_page'] = 5;
              
              // Initialize
             $this->pagination->initialize($config);
 
             // Initialize data Array and pagination
             $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;
             $start = ($page - 1) * $config['per_page']; 
             $data['berita'] = $this->m_dashboard->getBerita($config['per_page'],$start);
             $data['pagination'] = $this->pagination->create_links();
 

            $username = $this->session->userdata('username');
            $top['username'] = $username;
            $top['adminCount'] = $this->m_dashboard->getAdminCount();
            $top['nasabahCount'] = $this->m_dashboard->getNasabahCount();
            $top['transaksiCount'] = $this->m_dashboard->getTransaksiCount();
            $top['artikelCount'] = $this->m_dashboard->getArtikelCount();

            $this->load->view('template/header');
            $this->load->view('template/sidebar');
            $this->load->view('template/topbar', $top);
            $this->load->view('banksampah/tabelberita', $data);
            $this->load->view('template/footer');
            
        }

        public function loadTransaksi($page = 1){
            //in this case use m_transaksi model
            $this->load->model('m_transaksi');

            //get data count
            $transaksiCount = $this->m_dashboard->getTransaksiCount();

            // Pagination Configuration
            $config['base_url'] = base_url().'dashboard/loadTransaksi';
            $config['use_page_numbers'] = TRUE;
            $config['total_rows'] = $transaksiCount;
            $config['per_page'] = 7;

             // Initialize
            $this->pagination->initialize($config);

            // Initialize data Array and pagination
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;
            
           
            $start = ($page - 1) * $config['per_page']; 

            $data['transaksi'] = $this->m_transaksi->loadTransaksiAll($config['per_page'],$start);
            $data['pagination'] = $this->pagination->create_links();

            $username = $this->session->userdata('username');
            $top['username'] = $username;

            $top['adminCount'] = $this->m_dashboard->getAdminCount();
            $top['nasabahCount'] = $this->m_dashboard->getNasabahCount();
            $top['transaksiCount'] = $this->m_dashboard->getTransaksiCount();
            $top['artikelCount'] = $this->m_dashboard->getArtikelCount();

            $this->load->view('template/header');
            $this->load->view('template/sidebar');
            $this->load->view('template/topbar', $top);
            $this->load->view('banksampah/tabeltransaksi', $data);
            $this->load->view('template/footer');
            
        }

        public function loadSampah(){


             //get data count
             $sampahCount = $this->m_dashboard->getSampahCount();
 
             // Pagination Configuration
             $config['base_url'] = base_url().'dashboard/loadSampah';
             $config['use_page_numbers'] = TRUE;
             $config['total_rows'] = $sampahCount;
             $config['per_page'] = 7;
 
              // Initialize
             $this->pagination->initialize($config);
 
             // Initialize data Array and pagination
             $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;
             
            
             $start = ($page - 1) * $config['per_page']; 
 
             $data['sampah'] = $this->m_dashboard->getDataSampah($config['per_page'],$start);
             $data['pagination'] = $this->pagination->create_links();
 
             $username = $this->session->userdata('username');
             $top['username'] = $username;
 
             $top['adminCount'] = $this->m_dashboard->getAdminCount();
             $top['nasabahCount'] = $this->m_dashboard->getNasabahCount();
             $top['transaksiCount'] = $this->m_dashboard->getTransaksiCount();
             $top['artikelCount'] = $this->m_dashboard->getArtikelCount();
 
             $this->load->view('template/header');
             $this->load->view('template/sidebar');
             $this->load->view('template/topbar', $top);
             $this->load->view('banksampah/tabelsampahadmin', $data);
             $this->load->view('template/footer');
        }

        public function tambahNasabah(){
            $this->load->model('M_auth');

            $rules = $this->M_auth->validation_fromadmin();
            $this->form_validation->set_rules($rules);
            
            if ($this->form_validation->run() == FALSE) {
                $username = $this->session->userdata('username');
                $top['username'] = $username;

                $top['adminCount'] = $this->m_dashboard->getAdminCount();
                $top['nasabahCount'] = $this->m_dashboard->getNasabahCount();
                $top['transaksiCount'] = $this->m_dashboard->getTransaksiCount();
                $top['artikelCount'] = $this->m_dashboard->getArtikelCount();

                $this->load->view('template/header');
                $this->load->view('template/sidebar');
                $this->load->view('template/topbar', $top);
                $this->load->view('banksampah/tabeltransaksi');
                $this->load->view('template/footer');
            } else {             
                $id_user = $this->M_auth->Add_fromadmin();

                //saldo
                $saldo = $this->input->post('saldo');
                $this->M_auth->registerTabunganWithSaldo($id_user, $saldo);
                redirect('dashboard/loadNasabah');
            }
        }

        public function importNasabah(){
            $config['upload_path'] = './uploads/';
            $config['allowed_types'] = 'xlsx';
            $config['max_size'] = 10000;

            $this->upload->initialize($config);

            if (!$this->upload->do_upload('excel_nasabah')) {
                $error = array('error' => $this->upload->display_errors());
                echo 'errrpr';
            } else {
                $data = array('upload_data' => $this->upload->data());
                $file_path = './uploads/' . $data['upload_data']['file_name'];

                $this->loadExcel($file_path);
            }   
        }

        public function loadExcel($file_path) {
            $this->load->model('M_auth');

            $reader = new \PhpOffice\PhpSpreadsheet\Reader\xlsx();
            $spreadsheet = $reader->load($file_path);
            $sheetData = $spreadsheet->getActiveSheet()->toArray();
    
            foreach ($sheetData as $key => $row) {
                if ($key == 0) continue; // Skip header row
                $data = array(
                    'username' => $row[1],
                    'password' => $row[2],
                    'notelp' => $row[3],
                    'email' => $row[4],
                    'tempat_lahir' => $row[5],
                    'tanggal_lahir' => $row[6],
                    'alamat' => $row[7],
                    'isverif' => '1',
                    'role' => 'user'
                );
                $saldo = $row[8];
                $userid = $this->M_auth->importnasabah($data);
                $this->M_auth->registerTabunganWithSaldo($userid, $saldo);
            }
            redirect('dashboard/loadNasabah');
            
        }

        public function tambahSampah(){
            $this->load->model('M_dashboard');

            $this->M_dashboard->insertSampah();
            redirect('dashboard/loadSampah');
        }

        public function editSampah(){
            $this->load->model('M_dashboard');

            $id_sampah = $this->input->get('id_sampah');
            $data_sampah = $this->M_dashboard->loadSampah($id_sampah);
            
            $username = $this->session->userdata('username');
            $top['username'] = $username;

            $top['adminCount'] = $this->m_dashboard->getAdminCount();
            $top['nasabahCount'] = $this->m_dashboard->getNasabahCount();
            $top['transaksiCount'] = $this->m_dashboard->getTransaksiCount();
            $top['artikelCount'] = $this->m_dashboard->getArtikelCount();

            $this->load->view('template/header');
            $this->load->view('template/sidebar');
            $this->load->view('template/topbar', $top);
            $this->load->view('banksampah/edit_sampah', ['data_sampah' => $data_sampah]);
            $this->load->view('template/footer');
        }

        public function updateSampah(){
            $this->load->model('M_dashboard');

            $id_sampah = $this->input->post('id_sampah');
            $sampah = $this->M_dashboard->updateSampah($id_sampah);
            redirect('dashboard/loadSampah');
        }

        public function deleteSampah(){
            $this->load->model('M_dashboard');

            $id_sampah = $this->input->get('id_sampah');
            $sampah = $this->M_dashboard->deleteSampah($id_sampah);
            redirect('dashboard/loadSampah');
        }

        public function importSampah(){
            $config['upload_path'] = './uploads/';
            $config['allowed_types'] = 'xlsx';
            $config['max_size'] = 10000;

            $this->upload->initialize($config);

            if (!$this->upload->do_upload('excel_sampah')) {
                $error = array('error' => $this->upload->display_errors());
                echo 'errrpr';
            } else {
                $data = array('upload_data' => $this->upload->data());
                $file_path = './uploads/' . $data['upload_data']['file_name'];

                $this->loadExcelSampah($file_path);
            }   
        }

        public function loadExcelSampah($file_path) {
            $this->load->model('M_dashboard');

            $reader = new \PhpOffice\PhpSpreadsheet\Reader\xlsx();
            $spreadsheet = $reader->load($file_path);
            $sheetData = $spreadsheet->getActiveSheet()->toArray();
    
            foreach ($sheetData as $key => $row) {
                if ($key == 0) continue; // Skip header row
                $data = array(
                    'jenis_sampah' => $row[1],
                    'kategori_sampah' => $row[2],
                    'sub_kategori_sampah' => $row[3],
                    'harga_sampah' => $row[4],
                );
                $this->M_dashboard->importSampah($data);
            }
            redirect('dashboard/loadSampah');
            
        }


    
    }
    
    /* End of file banksampah.php */
    
?>