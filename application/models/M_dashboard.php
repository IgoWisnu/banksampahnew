<?php


defined('BASEPATH') OR exit('No direct script access allowed');

class M_dashboard extends CI_Model {

    public function getData(){
        $this->db->where('role', 'user');
        $banjar_id = $this->session->userdata('banjar_id');
        if(!empty($banjar_id)){
            $this->db->where('banjar_id', $banjar_id);
        }
        $data = $this->db->get('user'); 
        return $data;
    }

    public function getUserData($limit, $offset, $keyword){
        if($keyword){
            $this->db->like('username', $keyword);
        }
        $this->db->select('*');
        $this->db->from('user');
        $this->db->where('role', 'user');
        $this->db->where('isVerif', '1');
        
        $banjar_id = $this->session->userdata('banjar_id');
        if(!empty($banjar_id)){
            $this->db->where('banjar_id', $banjar_id);
        }
        
        $this->db->order_by('id_user', 'desc');
        $this->db->limit($limit, $offset);  
        $query = $this->db->get();

        return $query;
    }

    public function getAdminCount(){
        $this->db->where('role', 'admin');
        $count = $this->db->count_all_results('user');
        return $count;
    }

    public function getNasabahCount(){
        $this->db->where('role', 'user');
        $this->db->where('isVerif', '1');
        
        $banjar_id = $this->session->userdata('banjar_id');
        if(!empty($banjar_id)){
            $this->db->where('banjar_id', $banjar_id);
        }
        
        $query = $this->db->get('user');

        return $query->num_rows();
    }

    public function getBerita($limit, $offset){
        $this->db->order_by('id', 'desc');
        $this->db->limit($limit, $offset);
        $data = $this->db->get('artikel'); 
        return $data;
    }

    public function getTransaksi($limit, $offset){
        $this->db->limit($limit, $offset);
        $query = $this->db->get('tabungan_transaksi');
        
        return $query;
    }

    public function getTransaksiCount(){
        $data = $this->db->count_all('tabungan_transaksi'); 
        return $data;
    }

    public function getArtikelCount(){
        $count = $this->db->count_all('artikel');
        return $count;
    }

    public function insertBerita($gambarBerita) {
        // Masukkan data ke dalam tabel artikel
        
        $data = array(
            'judul' => $this->input->post('judulBerita'),
            'gambar' => $gambarBerita,
            'deskripsi' => $this->input->post('deskripsiBerita')
        );
    
        $result = $this->db->insert('artikel', $data);
        return $result;
    }
    

    public function deleteData($id){
        $this->db->where('id', $id);
        $result = $this->db->delete('artikel');

        return $result;
    }
    
    public function getBeritaById($id) {
        $this->db->where('id', $id);
        $data = $this->db->get('artikel')->row_array();
        return $data;  
    }

    public function updateBerita($id, $gambarBerita) {
        // Fungsi untuk mengupdate berita berdasarkan ID
        
        $this->db->where('id', $id);
        $data = array(
            'judul' => $this->input->post('judulBerita'),
            'gambar' => $gambarBerita,
            'deskripsi' => $this->input->post('deskripsiBerita')
        );
        $result = $this->db->update('artikel', $data);

        return $result;
    }

    public function getDataSampah($limit, $offset){
        $this->db->order_by('id', 'desc');
        $this->db->limit($limit, $offset);
        $query = $this->db->get('jenis_sampah');

        return $query;
    }

    public function getSampahCount(){
        $count = $this->db->count_all('jenis_sampah');
        return $count;
    }
    
    

}

/* End of file ModelName.php */


?>