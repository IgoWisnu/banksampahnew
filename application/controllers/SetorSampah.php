<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SetorSampah extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_setor');
        $this->load->model('m_dashboard');

        if ($this->session->userdata('role') != 'admin') {
            redirect('auth');
        }
    }

    public function index()
    {   
        $username = $this->session->userdata('username');
        $top['username']       = $username;
        $top['adminCount']     = $this->m_dashboard->getAdminCount();
        $top['nasabahCount']   = $this->m_dashboard->getNasabahCount();
        $top['transaksiCount'] = $this->m_dashboard->getTransaksiCount();
        $top['artikelCount']   = $this->m_dashboard->getArtikelCount();

        $data['option']        = $this->m_setor->loadSelect();
        $data['margin_value']  = $this->m_setor->getMargin(); // Penting: Menampilkan margin di view

        $this->load->view('template/header');
        $this->load->view('template/sidebar');
        $this->load->view('template/topbar', $top);
        $this->load->view('banksampah/setor', $data);
        $this->load->view('template/footer');
    }

    public function setor()
    {
        $input  = $this->input->post('cari');
        $output = '';

        if ($input) {
            $data = $this->m_setor->cariUser($input);
            if ($data->num_rows() > 0) {
                $output  = '<div class="list-group shadow-sm rounded-3 overflow-hidden border mb-3">';
                foreach ($data->result() as $row) {
                    $nama = !empty($row->nama_lengkap) ? $row->nama_lengkap : $row->username;
                    $avatar = 'https://ui-avatars.com/api/?name=' . urlencode($nama) . '&background=198754&color=fff&size=64';
                    
                    $output .= '<div class="list-group-item list-group-item-action p-3 result-item" 
                                    style="cursor: pointer;"
                                    data-user-id="'.$row->id_user.'" 
                                    data-username="'.$row->username.'">
                                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="'.$avatar.'" class="rounded-circle border flex-shrink-0" width="40" height="40" alt="Avatar">
                                            <div>
                                                <h6 class="fw-bold text-dark mb-0">'.htmlspecialchars($nama).'</h6>
                                                <small class="text-muted"><i class="fas fa-at text-success me-1"></i>'.htmlspecialchars($row->username).' &bull; ID: #'.htmlspecialchars($row->id_user).'</small>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-success px-3 rounded-pill fw-bold">
                                            <i class="fas fa-check me-1"></i> Pilih
                                        </button>
                                    </div>
                                </div>';
                }
                $output .= '</div>';
            } else {
                $output = '<div class="alert alert-warning border-0 shadow-sm rounded-3 mb-3 py-2 px-3 small fw-medium">
                            <i class="fas fa-exclamation-circle me-1"></i> Mitra tidak ditemukan dengan kata kunci "'.htmlspecialchars($input).'"
                           </div>';
            }
        }
        echo $output;
    }

    public function insert_data()
    {
        $trSampah = array(
            'id_user_staff' => $this->session->userdata('id'),
            'id_user_nasabah' => $this->input->post('id_user'),
            'tgl_transaksi' => date('y-m-d')
        );
        $id = $this->m_setor->insertData($trSampah);

        $DtSampah = array(
            'id_transaksi_sampah' => $id,
            'id_jenis_sampah' => $this->input->post('id_jenis_sampah'),
            'total_harga' => $this->input->post('total_harga'),
            'berat_sampah' => $this->input->post('berat_sampah')
        );
    }

    /**
     * Proses kalkulasi & insert setor sampah dengan hitungan Margin Banjar.
     */
    public function kalkulasi()
    {
        // 1. Validasi input mitra & item sampah
        $id_user = $this->input->post('id_user');
        $list_jenis = $this->input->post('id_jenis_sampah');
        $list_berat = $this->input->post('berat_sampah');

        if (empty($id_user)) {
            $this->session->set_flashdata('failed', 'Gagal: Silakan cari dan pilih mitra terlebih dahulu.');
            redirect('setorsampah');
            return;
        }

        if (empty($list_jenis) || !is_array($list_jenis) || count($list_jenis) == 0) {
            $this->session->set_flashdata('failed', 'Gagal: Daftar sampah tidak boleh kosong.');
            redirect('setorsampah');
            return;
        }

        $has_valid_item = false;
        foreach ($list_jenis as $idx => $id_j) {
            $b = isset($list_berat[$idx]) ? floatval($list_berat[$idx]) : 0;
            if (!empty($id_j) && $b > 0) {
                $has_valid_item = true;
            }
        }

        if (!$has_valid_item) {
            $this->session->set_flashdata('failed', 'Gagal: Mohon pilih jenis sampah dan masukkan berat (Kg) lebih dari 0.');
            redirect('setorsampah');
            return;
        }

        // 2. Pakai DB transaction dari update-feature-1 agar aman & atomic
        $this->db->trans_start();

        // Header transaksi
        $id_transaksi = $this->m_setor->insertSampah();

        // Detail per jenis sampah
        $this->m_setor->insertDtSampah($id_transaksi);

        // Update total kotor transaksi sampah
        $total = $this->m_setor->updateTotal($id_transaksi);

        // 3. Logika Hitung Margin (Fitur Utama Kamu)
        $margin_value = $this->m_setor->getMargin();
        $margin = round($total * ($margin_value / 100));
        $totalWithMargin = $total - $margin;

        // Cari ID Tabungan
        $id_tabungan = $this->m_setor->cariIdTabungan();
        
        // Simpan ke tabungan_transaksi dengan membawa parameter margin & totalWithMargin
        $this->m_setor->insertTabungan($id_transaksi, $id_tabungan, $total, $margin, $totalWithMargin);

        // Update saldo menggunakan nominal yang sudah dipotong margin
        $this->m_setor->updateDebitSaldo($id_tabungan, $totalWithMargin);

        $this->db->trans_complete();

        // 4. Flashdata & Sync Cache dari update-feature-1
        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('failed', 'Setor sampah gagal diproses.');
        } else {
            // Sinkronisasi cache saldo dinamis nasabah
            $this->m_dashboard->syncSaldoCache($id_user);
            $this->session->set_flashdata('success', 'Setor Sampah Berhasil');
        }

        redirect('setorSampah');
    }

    /**
     * Hitung total harga otomatis (AJAX).
     */
    public function hitungHarga()
    {
        $id    = $this->input->post('id');
        $berat = floatval($this->input->post('berat'));

        $harga = $this->m_setor->cariHarga($id);
        $total = floor($harga * $berat);
        echo $total;
    }
}