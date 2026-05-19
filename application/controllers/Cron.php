<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Load library email bawaan CI
        $this->load->library('email');
    }

    public function proses_antrean() {
        // 1. Ambil maksimal 10 email yang masih antri (supaya server tidak ngos-ngosan)
        $this->db->where('status', 'antri');
        $this->db->limit(10); 
        $antrian = $this->db->get('antrian_email')->result_array();

        if (empty($antrian)) {
            echo "Tidak ada antrean email saat ini.";
            return;
        }

        // 2. Konfigurasi SMTP Email (Contoh menggunakan Gmail)
        // PENTING: Ganti dengan kredensial email kamu
        $config = array(
            'protocol'  => 'smtp',
            'smtp_host' => 'ssl://smtp.gmail.com', 
            'smtp_user' => 'EMAIL_KAMU@gmail.com', // <-- Ganti emailmu
            'smtp_pass' => 'PASSWORD_APP_GMAIL',   // <-- Ganti dengan App Password Gmail
            'smtp_port' => 465,
            'mailtype'  => 'text', 
            'charset'   => 'utf-8',
            'newline'   => "\r\n"
        );
        $this->email->initialize($config);

        $berhasil = 0;
        $gagal = 0;

        // 3. Looping dan kirim email satu per satu
        foreach ($antrian as $row) {
            $this->email->clear();
            $this->email->from('no-reply@banksampah.com', 'Info Bank Sampah');
            $this->email->to($row['email_tujuan']);
            $this->email->subject($row['subjek']);
            $this->email->message($row['pesan']);

            if ($this->email->send()) {
                // Jika sukses, ubah status jadi terkirim dan catat waktunya
                $this->db->where('id_antrian', $row['id_antrian']);
                $this->db->update('antrian_email', array(
                    'status' => 'terkirim',
                    'tgl_terkirim' => date('Y-m-d H:i:s')
                ));
                $berhasil++;
            } else {
                // Jika gagal, ubah status jadi gagal
                $this->db->where('id_antrian', $row['id_antrian']);
                $this->db->update('antrian_email', array(
                    'status' => 'gagal'
                ));
                $gagal++;
            }
        }

        echo "Proses antrean selesai. Berhasil: $berhasil, Gagal: $gagal";
    }
}