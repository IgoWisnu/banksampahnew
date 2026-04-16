<?php
    
    defined('BASEPATH') OR exit('No direct script access allowed');
    
    class M_jenis_sampah extends CI_Model {
        
        public function loadData(){
            $data = $this->db->get('jenis_sampah');
            return $data;
        }

        public function get_count() {
            return $this->db->count_all('jenis_sampah');
        }

        public function get_paginated($limit, $start) {
            $this->db->limit($limit, $start);
            $query = $this->db->get('jenis_sampah');
            return $query;
        }
    
    }
    
    /* End of file m_jenis_sampah.php */
    
?>