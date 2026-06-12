<?php
    
    defined('BASEPATH') OR exit('No direct script access allowed');
    
    class M_artikel extends CI_Model {
    
        public function getData(){
            // FILTER BANJAR & UNIVERSAL
            $banjar_id = $this->session->userdata('banjar_id');
            
            if(!empty($banjar_id)){
                // Gunakan group_start() agar kondisi OR dibungkus dalam kurung "( )"
                $this->db->group_start();
                $this->db->where('banjar_id', $banjar_id);
                $this->db->or_where('banjar_id IS NULL', null, false);
                $this->db->group_end();
            } else {
                // Jika yang login adalah Guest (tidak punya banjar), 
                // tampilkan hanya berita yang Universal saja
                $this->db->where('banjar_id IS NULL', null, false);
            }

            // Tampilkan berita terbaru di atas
            $this->db->order_by('id', 'desc');
            $result = $this->db->get('artikel');
            return $result->result_array();
        }

        public function getDetail($id){
            $this->db->where('id', $id);
            $query = $this->db->get('artikel')->result_array();

            // Kembalikan data baris pertama
            if(!empty($query)){
                return $query[0];
            }
            return null;
        }
    
    }
    
    /* End of file M_artikel.php */
    
?>