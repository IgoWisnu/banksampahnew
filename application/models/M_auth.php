<?php

defined('BASEPATH') OR exit('No direct script access allowed');

//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;



class M_auth extends CI_Model
{


    public function __construct()
    {
        parent::__construct();

    }

    public function validation()
    {
        return [
            [
                'field' => 'username',
                'label' => 'Username',
                'rules' => 'required|min_length[3]|max_length[32]|is_unique[user.username]',
            ],
            [
                'field' => 'email',
                'label' => 'Email',
                'rules' => 'required|valid_email|is_unique[user.email]',
            ],
            [
                'field' => 'nama_lengkap',
                'label' => 'NamaLengkap',
                'rules' => 'required',
            ],
            [
                'field' => 'tanggal_lahir',
                'label' => 'TanggalLahir',
                'rules' => 'required',
            ],
            [
                'field' => 'alamat',
                'label' => 'Alamat',
                'rules' => 'required',
            ],
            [
                'field' => 'notelp',
                'label' => 'Notelp',
                'rules' => 'required',
            ],
            [
                'field' => 'password',
                'label' => 'password',
                'rules' => 'required|min_length[3]',
            ],
            [
                'field' => 'verify_password',
                'label' => 'VerifyPassword',
                'rules' => 'required|min_length[3]',
            ],
            [
                'field' => 'banjar_id',
                'label' => 'Banjar',
                'rules' => 'required',
            ],

        ];
    }

    public function validation_fromadmin()
    {
        return [
            [
                'field' => 'username',
                'label' => 'Username',
                'rules' => 'required|min_length[3]|max_length[32]|is_unique[user.username]',
            ],
            [
                'field' => 'password',
                'label' => 'Password',
                'rules' => 'permit_empty|min_length[3]', 
            ],
            [
                'field' => 'nama_lengkap',
                'label' => 'Nama Lengkap',
                'rules' => 'required',
            ],
            [
                'field' => 'tempat_lahir',
                'label' => 'Tempat Lahir',
                'rules' => 'required',
            ],
            [
                'field' => 'tanggal_lahir',
                'label' => 'Tanggal Lahir',
                'rules' => 'required',
            ],
            [
                'field' => 'alamat',
                'label' => 'Alamat',
                'rules' => 'required',
            ],
            [
                'field' => 'email',
                'label' => 'Email',
                'rules' => 'valid_email|is_unique[user.email]',
            ]
        ];
    }

    public function verify($token)
    {
        // Check if 'kode_verif' exists
        $user = $this->db->get_where('user', ['kode_verif' => $token])->row();

        if ($user && $user->isVerif == 0) {
            // 'kode_verif' exists and 'isVerif' is not 1, proceed with the update
            $this->db->where('kode_verif', $token)
                ->update('user', ['isVerif' => 1]);

            // Check if the update was successful
            $condition = 'verif';
        } elseif ($user && $user->isVerif == 1) {
            // 'kode_verif' doesn't exist or 'isVerif' is already 1, no need to update
            $condition = 'already_verif';
        } else {
            $condition = 'not_found';
        }

        return $condition;
    }


    public function add()
    {
        $kode = random_string('alnum', 20);
        $role = 'user';
        $data = array(
            'username' => $this->input->post('username'),
            'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
            'nama_lengkap' => $this->input->post('nama_lengkap'),
            'tempat_lahir' => $this->input->post('tempat_lahir'),
            'tanggal_lahir' => $this->input->post('tanggal_lahir'),
            'alamat' => $this->input->post('alamat'),
            'notelp' => $this->input->post('notelp'),
            'role' => $role,
            'kode_verif' => $kode,
            'email' => $this->input->post('email'),
            'isVerif' => 0,
            'banjar_id' => $this->input->post('banjar_id')
        );
        $result = $this->db->insert('user', $data);
        if ($result) {
            $mailAddress = array(
                'email' => $data['email'],
                'kode_verif' => $data['kode_verif']
            );
            return $mailAddress;
        }
    }

    public function resetPasswordFromAdmin($id_user)
    {
        // Enkripsi password default menjadi hash secure
        $default_password = password_hash('12345678', PASSWORD_DEFAULT);
        
        $data = array(
            'password' => $default_password
        );

        $this->db->where('id_user', $id_user);
        return $this->db->update('user', $data); // Eksekusi update SQL
    }

    public function update_fromadmin($id_user)
    {
        $data = array(
            'username'      => $this->input->post('username'),
            'nama_lengkap'  => $this->input->post('nama_lengkap'),
            'tempat_lahir'  => $this->input->post('tempat_lahir'),
            'tanggal_lahir' => $this->input->post('tanggal_lahir'),
            'alamat'        => $this->input->post('alamat'),
            'notelp'        => $this->input->post('notelp'),
            'email'         => $this->input->post('email')
        );

        // Ambil input password baru
        $password = $this->input->post('password');
        
        // Logika: Hanya update password jika kolom password diisi oleh admin
        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $this->db->where('id_user', $id_user);
        return $this->db->update('user', $data); // Eksekusi update
    }

    public function add_fromadmin()
    {
        $kode = random_string('alnum', 20);
        $role = 'user';

        $password = $this->input->post('password');
        
        if (empty($password)) {
            $password = '12345678';
        }

        $data = array(
            'username' => $this->input->post('username'),
            'password' => password_hash($password, PASSWORD_DEFAULT), 
            'nama_lengkap' => $this->input->post('nama_lengkap'),
            'tempat_lahir' => $this->input->post('tempat_lahir'),
            'tanggal_lahir' => $this->input->post('tanggal_lahir'),
            'alamat' => $this->input->post('alamat'),
            'notelp' => $this->input->post('notelp'),
            'role' => $role,
            'kode_verif' => $kode,
            'email' => $this->input->post('email'),
            'isVerif' => 1,
            'banjar_id' => $this->session->userdata('banjar_id') ? $this->session->userdata('banjar_id') : $this->input->post('banjar_id')
        );
        $this->db->insert('user', $data);
        return $this->db->insert_id();
    }

    public function importnasabah($data)
    {
        $this->db->insert('user', $data);
        return $this->db->insert_id();
    }

    public function mail($token, $email)
    {

        //link
        $verificationLink = "https://www.sampah.lab-trpl.id/auth/verify?token={$token}"; // Replace with your actual verification link
        $message = "
             <html>
             <head>
               <title>Email Verification</title>
               <style>
                 body {
                   font-family: 'Arial', sans-serif;
                   background-color: #131313;
                   color: #333;
                 }
                 .container {
                   max-width: 600px;
                   margin: 0 auto;
                   padding: 20px;
                   background-color: #fff;
                   border-radius: 5px;
                   box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                 }
                 h1 {
                   color: #557C55;
                 }
                 p {
                   margin-bottom: 20px;
                 }
                 .verification-link {
                   display: inline-block;
                   padding: 10px 20px;
                   background-color: #557C55;
                   color: #fff;
                   text-decoration: none;
                   border-radius: 3px;
                 }
               </style>
             </head>
             <body>
               <div class='container'>
                 <h1>Email Verification</h1>
                 <p>Dear user,</p>
                 <p>Please click the following link to verify your email address:</p>
                 <a href='$verificationLink' class='verification-link'>Verify Email</a>
               </div>
             </body>
             </html>
             ";



        //config
        $config['useragent'] = "Codeigniter";
        $config['mailpath'] = "usr/bin/sendmail";
        $config['protocol'] = "smtp";
        $config['smtp_host'] = "smtp.gmail.com";
        $config['smtp_port'] = "465";
        $config['smtp_user'] = "jimbaran361@gmail.com";
        $config['smtp_pass'] = "achr iqgt irsu mjli";
        $config['smtp_crypto'] = "ssl";
        $config['charset'] = "utf-8";
        $config['mailtype'] = "html";
        $config['newline'] = "\r\n";
        $config['smtp_timeout'] = 30;
        $config['wordwrap'] = TRUE;

        //set
        $this->email->initialize($config);
        $this->email->from('no-replay@jimbaran361@gmail.com', 'BANK SAMPAH');
        $this->email->to($email);
        $this->email->subject("verifikasi email");
        $this->email->message($message);

        //check
        if ($this->email->send()) {
            return "email terkirim ke $email";
        } else {
            return "email gagal terkirim";
        }

    }

    public function registerTabungan($id)
    {
        date_default_timezone_set('Asia/Manila');
        $data = array(
            'id_user_nasabah' => $id,
            'saldo' => '0',
            'tgl_buka_rekening' => date('y-m-d')
        );

        $result = $this->db->insert('tabungan', $data);
        return $result;
    }

    public function getUser($token)
    {
        $query = $this->db->get_where('user', array('kode_verif' => $token));
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            return $result['id_user'];
        } else {
            // Record not found
            return null;
        }
    }

    public function checkUser($username)
    {
        $this->db->select('*');
        $this->db->from('user');
        $this->db->where('username', $username);
        $this->db->where('isVerif', 1);
        $user = $this->db->get();
        return $user;
    }

    public function requestPasswordReset($email)
    {
        $user = $this->db->get_where('user', ['email' => $email])->row();

        if ($user) {
            // Generate a secure random token and expiry time (1 hour from now)
            $token = bin2hex(random_bytes(32));
            $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

            // Store the reset token and expiry in the database
            $this->db->where('email', $email)
                ->update('user', ['reset_token' => $token, 'reset_token_expiry' => $expiry]);

            // Send reset email
            return $this->sendPasswordResetEmail($email, $token);
        } else {
            return "Email tidak ditemukan.";
        }
    }

    // Send Password Reset Email
    // Send Password Reset Email
    public function sendPasswordResetEmail($email, $token)
    {
        // Ubah link mengarah ke fungsi doResetToDefault di controller Auth
        $resetLink = base_url("auth/doResetToDefault?token={$token}");

        $message = "
            <div style='font-family: Arial, sans-serif; max-width: 500px; margin: 0 auto; padding: 20px; border: 1px solid #e5e7eb; border-radius: 10px;'>
                <h2 style='color: #00926E; text-align: center;'>Reset Password</h2>
                <p style='color: #374151; font-size: 15px;'>Halo,</p>
                <p style='color: #374151; font-size: 15px;'>Kami menerima permintaan untuk mereset password akun Bank Sampah Anda. Silakan klik tombol di bawah ini untuk mengembalikan password Anda ke default <b></b>.</p>
                
                <div style='text-align: center; margin: 30px 0;'>
                    <a href='{$resetLink}' style='background-color: #00926E; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: bold; font-size: 16px; display: inline-block;'>Reset</a>
                </div>
                
                <p style='color: #6b7280; font-size: 13px; text-align: center;'>Setelah berhasil login, segera ubah password Anda di menu Profil untuk keamanan.<br>Jika Anda tidak meminta reset ini, abaikan saja email ini.</p>
            </div>
            ";

        $config['useragent'] = "Bank Sampah";
        $config['mailpath'] = "usr/bin/sendmail";
        $config['protocol'] = "smtp";
        $config['smtp_host'] = "smtp.gmail.com";
        $config['smtp_port'] = "465";
        $config['smtp_user'] = "jimbaran361@gmail.com";
        $config['smtp_pass'] = "achr iqgt irsu mjli"; // Pastikan App Password Google ini aktif ya
        $config['smtp_crypto'] = "ssl";
        $config['charset'] = "utf-8";
        $config['mailtype'] = "html";
        $config['newline'] = "\r\n";
        $config['smtp_timeout'] = 30;
        $config['wordwrap'] = TRUE;

        $this->email->initialize($config);
        $this->email->from('no-reply@banksampah', 'BANK SAMPAH');
        $this->email->to($email);
        $this->email->subject("Password Reset Bank Sampah");
        $this->email->message($message);

        if ($this->email->send()) {
            return "Email reset password terkirim ke $email. Silakan cek kotak masuk atau folder spam Anda.";
        } else {
            return "Gagal mengirim email reset password. Pastikan koneksi internet stabil.";
        }
    }

    // Step 2: Verify Reset Token
    public function verifyResetToken($token)
    {
        $user = $this->db->get_where('user', ['reset_token' => $token])->row();

        if ($user && strtotime($user->reset_token_expiry) > time()) {
            return $user->email;
        } else {
            return false;
        }
    }

    // Step 3: Reset Password
    public function resetPassword($token, $newPassword)
    {
        $user = $this->db->get_where('user', ['reset_token' => $token])->row();

        if ($user && strtotime($user->reset_token_expiry) > time()) {
            $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);

            $this->db->where('reset_token', $token)
                ->update('user', [
                    'password' => $passwordHash,
                    'reset_token' => null,
                    'reset_token_expiry' => null
                ]);

            return "Password berhasil direset.";
        } else {
            return "Token tidak valid atau sudah kadaluarsa.";
        }
    }


}

/* End of file m_auth.php */

?>