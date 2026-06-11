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
                $this->load->model('m_jenis_sampah');

                // PERBAIKAN: Gunakan != (tidak sama dengan)
                if($this->session->userdata('role') != 'admin'){
                    redirect('auth');
                }
            }

            public function tambahSampah()
            {
                if($this->session->userdata('role') != 'admin'){
                    show_error('Akses ditolak', 403);
                    return;
                }
            
                $this->load->model('m_jenis_sampah');
            
                $data = array(
                    'jenis_sampah'        => $this->input->post('jenis_sampah'),
                    'kategori_sampah'     => $this->input->post('kategori_sampah'),
                    'sub_kategori_sampah' => $this->input->post('sub_kategori_sampah'),
                    'harga_sampah'        => intval($this->input->post('harga_sampah')),
                );
            
                if($this->m_jenis_sampah->insertJenis($data)){
                    $this->session->set_flashdata('success', 'Jenis sampah baru berhasil ditambahkan.');
                } else {
                    $this->session->set_flashdata('failed', 'Gagal menambah jenis sampah.');
                }
            
                redirect('dashboard/loadSampah');
            }

            public function updateSampah()
            {
                if($this->session->userdata('role') != 'admin'){
                    show_error('Akses ditolak', 403);
                    return;
                }
            
                $this->load->model('m_jenis_sampah');
            
                $id = $this->input->post('id_sampah');  // <-- field name dari view
                if(empty($id) || !is_numeric($id)){
                    $this->session->set_flashdata('failed', 'ID jenis sampah tidak valid.');
                    redirect('dashboard/loadSampah');
                    return;
                }
            
                $data = array(
                    'jenis_sampah'        => $this->input->post('jenis_sampah'),
                    'kategori_sampah'     => $this->input->post('kategori_sampah'),
                    'sub_kategori_sampah' => $this->input->post('sub_kategori_sampah'),
                    'harga_sampah'        => intval($this->input->post('harga_sampah')),
                );
            
                // Alasan perubahan (untuk audit di harga_sampah_history)
                $keterangan = $this->input->post('keterangan');
            
                $ok = $this->m_jenis_sampah->updateJenis($id, $data, $keterangan);
            
                if($ok){
                    $this->session->set_flashdata('success', 
                        'Data berhasil diupdate. Saldo nasabah otomatis menyesuaikan harga baru.');
                } else {
                    $this->session->set_flashdata('failed', 'Gagal mengupdate data sampah.');
                }
            
                redirect('dashboard/loadSampah');
            }

            public function deleteSampah()
            {
                if($this->session->userdata('role') != 'admin'){
                    show_error('Akses ditolak', 403);
                    return;
                }
            
                $this->load->model('m_jenis_sampah');
            
                // === UPDATE DI SINI ===
                // Ubah dari 'id' menjadi 'id_sampah' agar cocok dengan URL yang dikirim oleh View
                $id = $this->input->post('id_sampah') ?: $this->input->get('id_sampah');
            
                if(empty($id) || !is_numeric($id)){
                    $this->session->set_flashdata('failed', 'ID jenis sampah tidak valid.');
                    redirect('dashboard/loadSampah');
                    return;
                }
            
                // Cek apakah jenis ini sudah dipakai di setoran
                $this->db->where('id_jenis_sampah', $id);
                $pakai = $this->db->count_all_results('transaksi_sampahdetail');
            
                if($pakai > 0){
                    $this->session->set_flashdata('failed', 
                        'Tidak bisa dihapus. Jenis sampah ini sudah pernah disetor oleh nasabah ('. $pakai .' transaksi). 
                        Untuk menonaktifkan, ubah harganya menjadi 0 atau ubah namanya.');
                    redirect('dashboard/loadSampah');
                    return;
                }
            
                if($this->m_jenis_sampah->deleteJenis($id)){
                    $this->session->set_flashdata('success', 'Jenis sampah berhasil dihapus.');
                } else {
                    $this->session->set_flashdata('failed', 'Gagal menghapus jenis sampah.');
                }
            
                redirect('dashboard/loadSampah');
            }
            
            
            /**
             * Alias untuk loadDataSampah (kompatibel dgn link dari Jenissampah controller).
             */
            public function loadDataSampah()
            {
                $this->loadSampah();
            }
        
            public function tambahBerita() {
                // Konfigurasi upload
                $config['upload_path'] = "./uploads"; 
                $config['allowed_types'] = 'gif|jpg|jpeg|png';  
                $config['max_size'] = 204800;  
                $config['encrypt_name']  = TRUE;
            
                $this->upload->initialize($config);
            
                if (!$this->upload->do_upload('gambarBerita')) {
                    $error = $this->upload->display_errors('', '');  
            
                    // Set flashdata failed dan redirect agar memicu SweetAlert
                    $this->session->set_flashdata('failed', 'Gagal upload gambar: ' . $error);
                    redirect('dashboard/loadBerita');
                    return; 
                } else {
                    $upload_data = $this->upload->data();
                    $gambarBerita = $upload_data['file_name'];  
            
                    // 1. Simpan berita ke tabel artikel
                    $insert = $this->m_dashboard->insertBerita($gambarBerita);
            
                    // 2. Jika berita berhasil disimpan
                    if($insert){
                        // === UPDATE DI SINI ===
                        // Cek apakah admin mencentang pilihan kirim email
                        $kirim_email = $this->input->post('kirim_email');
                        
                        if($kirim_email == 1) {
                            $judul = $this->input->post('judulBerita');
                            $deskripsi = $this->input->post('deskripsiBerita');
                            
                            // Ambil semua email nasabah yang aktif
                            $this->db->select('email');
                            $this->db->where('role', 'user');
                            $this->db->where('isVerif', 1);
                            $this->db->where('email !=', '');
                            $nasabah = $this->db->get('user')->result_array();

                            if(!empty($nasabah)){
                                $data_antrian = array();
                                $subjek_email = "Info Bank Sampah: " . $judul;
                                $ringkasan = strip_tags($deskripsi);
                                $ringkasan = substr($ringkasan, 0, 150) . "..."; 
                                
                                $pesan_email = "Halo Nasabah Bank Sampah,\n\nAda info terbaru untuk Anda:\n\n" . 
                                            $judul . "\n\n" . 
                                            $ringkasan . "\n\n" .
                                            "Silakan login ke aplikasi Bank Sampah untuk membaca berita selengkapnya.\n\nSalam Hangat,\nAdmin Bank Sampah";

                                foreach($nasabah as $n) {
                                    if (!empty($n['email'])) {
                                        $data_antrian[] = array(
                                            'email_tujuan' => $n['email'],
                                            'subjek'       => $subjek_email,
                                            'pesan'        => $pesan_email,
                                            'status'       => 'antri'
                                        );
                                    }
                                }

                                if(!empty($data_antrian)){
                                    $this->db->insert_batch('antrian_email', $data_antrian);
                                }
                            }
                            $this->session->set_flashdata('success', 'Berita diterbitkan dan masuk ke antrean email!');
                        } else {
                            $this->session->set_flashdata('success', 'Berita berhasil diterbitkan (Tanpa email).');
                        }
                        // ======================
                    } else {
                        $this->session->set_flashdata('failed', 'Artikel gagal ditambahkan');
                    }
                    redirect('dashboard/loadBerita'); 
                }
            }

            // === TAMBAHKAN FUNGSI BARU INI DI BAWAH TAMBAH BERITA ===
            /**
             * Mengantrekan email berita secara manual dari tombol di tabel
             */
            public function antrikanEmailBerita() {
                if($this->session->userdata('role') != 'admin'){
                    show_error('Akses ditolak', 403);
                    return;
                }

                $id = $this->input->get('id');
                if(empty($id) || !is_numeric($id)){
                    $this->session->set_flashdata('failed', 'ID artikel tidak valid.');
                    redirect('dashboard/loadBerita');
                    return;
                }

                // Ambil data berita berdasarkan ID
                $berita = $this->m_dashboard->getBeritaById($id);
                if(!$berita) {
                    $this->session->set_flashdata('failed', 'Artikel tidak ditemukan.');
                    redirect('dashboard/loadBerita');
                    return;
                }

                $judul = $berita['judul'];
                $deskripsi = $berita['deskripsi'];

                // Ambil semua nasabah aktif yang punya email
                $this->db->select('email');
                $this->db->where('role', 'user');
                $this->db->where('isVerif', 1);
                $this->db->where('email !=', '');
                $nasabah = $this->db->get('user')->result_array();

                if(!empty($nasabah)){
                    $data_antrian = array();
                    $subjek_email = "Info Bank Sampah: " . $judul;
                    $ringkasan = strip_tags($deskripsi);
                    $ringkasan = substr($ringkasan, 0, 150) . "..."; 
                    
                    $pesan_email = "Halo Nasabah Bank Sampah,\n\nAda info terbaru untuk Anda:\n\n" . 
                                $judul . "\n\n" . 
                                $ringkasan . "\n\n" .
                                "Silakan login ke aplikasi Bank Sampah untuk membaca berita selengkapnya.\n\nSalam Hangat,\nAdmin Bank Sampah";

                    foreach($nasabah as $n) {
                        if (!empty($n['email'])) {
                            $data_antrian[] = array(
                                'email_tujuan' => $n['email'],
                                'subjek'       => $subjek_email,
                                'pesan'        => $pesan_email,
                                'status'       => 'antri'
                            );
                        }
                    }

                    if(!empty($data_antrian)){
                        $this->db->insert_batch('antrian_email', $data_antrian);
                        $this->session->set_flashdata('success', 'Berita berhasil dimasukkan ke antrean email!');
                    } else {
                        $this->session->set_flashdata('failed', 'Tidak ada email nasabah yang valid.');
                    }
                } else {
                    $this->session->set_flashdata('failed', 'Tidak ada nasabah aktif yang memiliki email.');
                }

                redirect('dashboard/loadBerita');
            }
            
            

    public function updateBerita()
    {
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
                // PERBAIKAN: Ambil pesan error tanpa tag <p> bawaan CodeIgniter
                $error = $this->upload->display_errors('', '');
                
                // Set flashdata failed dan redirect agar memicu SweetAlert
                $this->session->set_flashdata('failed', 'Gagal update gambar: ' . $error);
                redirect('dashboard/loadBerita');
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

        if ($update) {
            $this->session->set_flashdata('success', 'Artikel berhasil diupdate');
        } else {
            $this->session->set_flashdata('failed', 'Artikel gagal diupdate');
        }
        // Redirect atau tampilkan pesan sukses
        redirect('dashboard/loadBerita');
    }

    public function tampilkanTabelNasabah()
    {
        $data['user'] = $this->m_dashboard->getData(); // Mengambil data nasabah dari model

        // Load view yang menampilkan tabel nasabah
        $this->load->view('banksampah/tnasabah', $data);
    }



    public function index()
    {
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
                $this->load->view('banksampah/dashboard_utama', $data);

        $this->load->view('template/footer');
        

    }


    public function deleteb()
    {
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

        public function loadRiwayatHarga()
        {
            // 1. Pastikan yang akses adalah admin
            if($this->session->userdata('role') != 'admin'){
                show_error('Akses ditolak', 403);
                return;
            }

            // 2. Load model m_jenis_sampah (tempat query history berada)
            $this->load->model('m_jenis_sampah');

            // 3. Ambil data riwayat perubahan harga dari model (limit 50 data terbaru)
            $data['history'] = $this->m_jenis_sampah->getAllHargaHistory(50);

            // 4. Siapkan data untuk komponen Topbar (sama seperti halaman lain)
            $username = $this->session->userdata('username');
            $top['username'] = $username;
            $top['adminCount']     = $this->m_dashboard->getAdminCount();
            $top['nasabahCount']   = $this->m_dashboard->getNasabahCount();
            $top['transaksiCount'] = $this->m_dashboard->getTransaksiCount();
            $top['artikelCount']   = $this->m_dashboard->getArtikelCount();

            // 5. Load susunan tampilan template admin secara berurutan
            $this->load->view('template/header');
            $this->load->view('template/sidebar');
            $this->load->view('template/topbar', $top);
            
            // Memanggil file view riwayat_harga.php yang sudah kamu buat
            $this->load->view('banksampah/riwayat_harga', $data);
            
            $this->load->view('template/footer');
        }

    public function loadNasabah()
    {
        //search handle
        if ($this->input->post('keyword') == '') {
            $data['keyword'] = null;
            $this->session->unset_userdata('keyword_nasabah');
        } elseif ($this->input->post('keyword')) {
            $data['keyword'] = $this->input->post('keyword');
            $this->session->set_userdata('keyword_nasabah', $data['keyword']);
        } elseif ($this->session->userdata('keyword_nasabah')) {
            $data['keyword'] = $this->session->userdata('keyword_nasabah');
        }


        //get data count after the last query
        $this->db->where('role', 'user');
        $this->db->where('isVerif', '1');
        if ($data['keyword']) {
            $this->db->like('username', $data['keyword']);
        }

        $banjar_id = $this->session->userdata('banjar_id');
        if (!empty($banjar_id)) {
            $this->db->where('banjar_id', $banjar_id);
        }

        $this->db->from('user');

        // Pagination Configuration
        $config['base_url'] = base_url() . 'dashboard/loadNasabah';
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = $this->db->count_all_results();
        $config['per_page'] = 7;

        // Initialize
        $this->pagination->initialize($config);

        // Initialize data Array and pagination
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;
        $start = ($page - 1) * $config['per_page'];
        $data['user'] = $this->m_dashboard->getUserData($config['per_page'], $start, $data['keyword']);
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

    public function loadBerita()
    {
        //get data count
        $beritaCount = $this->m_dashboard->getArtikelCount();

        // Pagination Configuration
        $config['base_url'] = base_url() . 'dashboard/loadBerita';
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = $beritaCount;
        $config['per_page'] = 5;

        // Initialize
        $this->pagination->initialize($config);

        // Initialize data Array and pagination
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;
        $start = ($page - 1) * $config['per_page'];
        $data['berita'] = $this->m_dashboard->getBerita($config['per_page'], $start);
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

    public function loadTransaksi($page = 1)
    {
        //in this case use m_transaksi model
        $this->load->model('m_transaksi');

        //get data count
        $transaksiCount = $this->m_dashboard->getTransaksiCount();

        // Pagination Configuration
        $config['base_url'] = base_url() . 'dashboard/loadTransaksi';
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = $transaksiCount;
        $config['per_page'] = 7;

        // Initialize
        $this->pagination->initialize($config);

        // Initialize data Array and pagination
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;


        $start = ($page - 1) * $config['per_page'];

        $data['transaksi'] = $this->m_transaksi->loadTransaksiAll($config['per_page'], $start);
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

    public function loadSampah()
    {


        //get data count
        $sampahCount = $this->m_dashboard->getSampahCount();

        // Pagination Configuration
        $config['base_url'] = base_url() . 'dashboard/loadSampah';
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = $sampahCount;
        $config['per_page'] = 7;

        // Initialize
        $this->pagination->initialize($config);

        // Initialize data Array and pagination
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;


        $start = ($page - 1) * $config['per_page'];

        $data['sampah'] = $this->m_dashboard->getDataSampah($config['per_page'], $start);
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

    public function tambahNasabah()
    {
        $this->load->model('M_auth');

        $rules = $this->M_auth->validation_fromadmin();
        $this->form_validation->set_rules($rules);

        if ($this->form_validation->run() == FALSE) {
            // Beri tahu View untuk membuka modal kembali
            $this->session->set_flashdata('open_modal', 'tambahNasabahModal');
            $this->session->set_flashdata('failed', 'Gagal menambah nasabah. Pastikan Username/Email belum terpakai.');
            
            $this->loadNasabah();
        } else {
            $id_user = $this->M_auth->Add_fromadmin();
            $this->M_auth->registerTabungan($id_user);
            $this->session->set_flashdata('success', 'Nasabah baru berhasil ditambahkan!');
            redirect('dashboard/loadNasabah');
        }
    }

    public function editNasabah()
    {
        $this->load->model('M_auth');
        $id_user = $this->input->post('id_user');

        // Ambil data nasabah saat ini sebelum diedit
        $current_user = $this->db->get_where('user', ['id_user' => $id_user])->row();

        // Aturan validasi dasar (Wajib Diisi)
        $this->form_validation->set_rules('nama_lengkap', 'Nama Lengkap', 'required');
        $this->form_validation->set_rules('tempat_lahir', 'Tempat Lahir', 'required');
        $this->form_validation->set_rules('tanggal_lahir', 'Tanggal Lahir', 'required');
        $this->form_validation->set_rules('alamat', 'Alamat', 'required');
        $this->form_validation->set_rules('password', 'Password', 'permit_empty|min_length[3]');

        // Trik Pintar Username: Jika username diganti, baru cek is_unique ke database
        $username_rules = 'required|min_length[3]|max_length[32]';
        if ($this->input->post('username') != $current_user->username) {
            $username_rules .= '|is_unique[user.username]';
        }
        $this->form_validation->set_rules('username', 'Username', $username_rules);

        // Trik Pintar Email: Jika email diganti, baru cek is_unique ke database
        $email_rules = 'valid_email';
        if (!empty($this->input->post('email')) && $this->input->post('email') != $current_user->email) {
            $email_rules .= '|is_unique[user.email]';
        }
        $this->form_validation->set_rules('email', 'Email', $email_rules);

        if ($this->form_validation->run() == FALSE) {
            // Jika gagal validasi, tampilkan pesan error lewat SweetAlert flashdata
            $this->session->set_flashdata('failed', 'Gagal update nasabah. ' . validation_errors('', ' '));
            redirect('dashboard/loadNasabah');
        } else {
            // Jika lolos, kirim perintah simpan ke model
            $this->M_auth->update_fromadmin($id_user);
            $this->session->set_flashdata('success', 'Data nasabah berhasil diperbarui!');
            redirect('dashboard/loadNasabah');
        }
    }

    public function importNasabah()
    {
        // Pastikan folder uploads ada di root project kamu
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'xlsx|xls'; // Tambahkan xls buat jaga-jaga
        $config['max_size'] = 10000; // Maksimal 10MB

        $this->upload->initialize($config);

        if (!$this->upload->do_upload('excel_nasabah')) {
            // Jangan echo error, tapi kirim ke Flashdata agar SweetAlert muncul
            $error = $this->upload->display_errors('', ''); // Menghilangkan tag <p> bawaan CodeIgniter
            $this->session->set_flashdata('failed', 'Gagal upload file: ' . $error);
            redirect('dashboard/loadNasabah');
        } else {
            $data = array('upload_data' => $this->upload->data());
            $file_path = './uploads/' . $data['upload_data']['file_name'];

            $this->loadExcel($file_path);
        }
    }

    public function getDetailNasabahAjax()
    {
        // Pastikan hanya request AJAX atau admin terautentikasi yang bisa memicu
        if($this->session->userdata('role') != 'admin'){
            echo json_encode(['error' => 'Akses ditolak']);
            return;
        }

        $id_user = $this->input->get('id');
        if (empty($id_user)) {
            echo json_encode(['error' => 'ID Nasabah tidak valid']);
            return;
        }

        $this->load->model('m_dashboard');

        // 1. Ambil saldo dinamis real-time nasabah
        $saldo = $this->m_dashboard->getSaldoDinamis($id_user);
        $saldo_format = 'Rp ' . number_format($saldo, 0, ',', '.');

        // 2. Query langsung ambil 5 riwayat transaksi terakhir nasabah (Setor & Tarik)
        $this->db->select('tt.*');
        $this->db->from('tabungan_transaksi tt');
        $this->db->join('tabungan t', 'tt.id_tabungan = t.id_tabungan');
        $this->db->where('t.id_user_nasabah', $id_user);
        $this->db->order_by('tt.id_tabungan_transaksi', 'DESC');
        $this->db->limit(5); // Batasi hanya 5 transaksi teratas agar rapi di modal
        $riwayat_query = $this->db->get()->result_array();

        // 3. Lakukan looping untuk mempercantik format rupiah dan tanggal sebelum dikirim ke JavaScript
        foreach ($riwayat_query as &$row) {
            $row['debit_final_format'] = 'Rp ' . number_format($row['debit_final'], 0, ',', '.');
            $row['kredit_format']      = 'Rp ' . number_format($row['kredit'], 0, ',', '.');
            $row['tgl_format']         = date('d M Y', strtotime($row['tgl_tabungan_transaksi']));
        }

        // Susun paket JSON-nya
        $response = [
            'saldo_format' => $saldo_format,
            'riwayat'      => $riwayat_query
        ];

        // Kirim balik ke View
        echo json_encode($response);
    }

    public function resetPasswordNasabah()
    {
        // 1. Validasi keamanan: Pastikan yang mengakses beneran admin
        if($this->session->userdata('role') != 'admin'){
            show_error('Akses ditolak', 403);
            return;
        }

        // 2. Ambil ID nasabah dari URL GET
        $id = $this->input->get('id');

        if(empty($id) || !is_numeric($id)){
            $this->session->set_flashdata('failed', 'ID nasabah tidak valid.');
            redirect('dashboard/loadNasabah');
            return;
        }

        // 3. Panggil model M_auth untuk eksekusi reset
        $this->load->model('M_auth');
        $update = $this->M_auth->resetPasswordFromAdmin($id);

        // 4. Set notifikasi Flashdata untuk SweetAlert
        if($update){
            $this->session->set_flashdata('success', 'Password nasabah berhasil di-reset menjadi 12345678!');
        } else {
            $this->session->set_flashdata('failed', 'Gagal me-reset password nasabah.');
        }

        // 5. Kembalikan ke halaman daftar nasabah
        redirect('dashboard/loadNasabah');
    }

    public function loadExcel($file_path)
    {
        $this->load->model('M_auth');
        $this->load->helper('string'); 

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load($file_path);
        $sheetData = $spreadsheet->getActiveSheet()->toArray();

        $jumlahBerhasil = 0;

        foreach ($sheetData as $key => $row) {
            if ($key == 0)
                continue; // Skip baris pertama (Header tabel Excel)

            // Skip baris kosong (jika username kosong)
            if (empty($row[1]))
                continue;

            // Generate kode unik verifikasi
            $kode = random_string('alnum', 20);

            // --- LOGIKA NAMA LENGKAP & DEFAULT PASSWORD ---
            // Ambil password dari kolom Excel. Jika dikosongkan, otomatis jadi 12345678
            $password_input = !empty($row[3]) ? $row[3] : '12345678';
            
            // Ambil nama lengkap dari Excel. (Opsional/Fallback)
            $nama_lengkap = !empty($row[2]) ? $row[2] : 'Nasabah Baru';
            // ----------------------------------------------

            $data = array(
                'username'      => $row[1],
                'nama_lengkap'  => $nama_lengkap, // <-- Ini yang tadi ketinggalan
                'password'      => password_hash($password_input, PASSWORD_DEFAULT),
                'notelp'        => $row[4],
                'email'         => $row[5],
                'tempat_lahir'  => $row[6],
                'tanggal_lahir' => $row[7],
                'alamat'        => $row[8],
                'role'          => 'user',          
                'kode_verif'    => $kode,     
                'isVerif'       => 1,             
                'banjar_id'     => $this->session->userdata('banjar_id') ?? null
            );

            $userid = $this->M_auth->importnasabah($data);
            if ($userid) {
                $this->M_auth->registerTabungan($userid);
                $jumlahBerhasil++;
            }
        }

        // Hapus file excel dari folder uploads setelah selesai dibaca
        if (file_exists($file_path)) {
            unlink($file_path);
        }

        // Set Flashdata agar ditangkap oleh SweetAlert2
        if ($jumlahBerhasil > 0) {
            $this->session->set_flashdata('success', $jumlahBerhasil . ' Data Nasabah berhasil diimport!');
        } else {
            $this->session->set_flashdata('failed', 'Tidak ada data yang berhasil diimport. Cek format Excel kamu.');
        }

        redirect('dashboard/loadNasabah');
    }


public function prosesAntreanEmail() {
        // 1. Ambil data antrean (Limit 50 agar server tidak berat/timeout)
        $this->db->where('status', 'antri');
        $this->db->limit(50);
        $antrean = $this->db->get('antrian_email')->result_array();

        if (empty($antrean)) {
            echo "Aman! Tidak ada antrean email yang perlu dikirim.";
            return;
        }

        // 2. Load Library Email CodeIgniter dan Konfigurasi SMTP
        $this->load->library('email');
        
        $config = [
            'protocol'    => 'smtp',
            'smtp_host'   => 'ssl://smtp.gmail.com',   // 1. PASTIKAN INI DIUBAH JADI SMTP GOOGLE
            'smtp_user'   => 'jimbaran361@gmail.com',  // 2. Akun Gmail kamu sebagai pengirim
            'smtp_pass'   => 'achr iqgt irsu mjli',    // 3. WAJIB ISI 16 DIGIT "SANDI APLIKASI" GOOGLE (Bukan password Gmail biasamu)
            'smtp_port'   => 465,
            'mailtype'    => 'text',
            'charset'     => 'utf-8',
            'newline'     => "\r\n"
        ];

        $this->email->initialize($config);

        $berhasil = 0;
        $gagal = 0;

        // 3. Looping untuk mengirim email satu per satu
        foreach ($antrean as $row) {
            $this->email->clear();
            $this->email->from($config['smtp_user'], 'Admin Bank Sampah');
            $this->email->to($row['email_tujuan']);
            $this->email->subject($row['subjek']);
            $this->email->message($row['pesan']);

            if ($this->email->send()) {
                // Jika berhasil, ubah status jadi terkirim dan catat waktunya
                $this->db->where('id_antrian', $row['id_antrian']);
                $this->db->update('antrian_email', [
                    'status' => 'terkirim',
                    'tgl_terkirim' => date('Y-m-d H:i:s')
                ]);
                $berhasil++;
            } else {
                // Jika gagal, ubah status jadi gagal
                $this->db->where('id_antrian', $row['id_antrian']);
                $this->db->update('antrian_email', [
                    'status' => 'gagal'
                ]);
                $gagal++;
                
                // // MATA-MATA ERROR: Tampilkan alasan kenapa gagal terkirim
                // echo "<div style='color:red;'>Error untuk email " . $row['email_tujuan'] . ":<br>";
                // echo $this->email->print_debugger() . "</div><hr>";
            }
        }

        echo "Laporan Selesai! Berhasil: $berhasil | Gagal: $gagal";
    }
}

/* End of file banksampah.php */

?>