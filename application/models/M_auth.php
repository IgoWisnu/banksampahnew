<?php

    defined('BASEPATH') OR exit('No direct script access allowed');
    
    //Import PHPMailer classes into the global namespace
    //These must be at the top of your script, not inside a function
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    

    class M_auth extends CI_Model {
    
        
        public function __construct()
        {
            parent::__construct();
            
        }

        public function validation(){
            return[
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
                
            ];
        }

        public function validation_fromadmin(){
            return[
                [
                    'field' => 'username',
                    'label' => 'Username',
                    'rules' => 'required|min_length[3]|max_length[32]|is_unique[user.username]',
                ],
                [
                    'field' => 'email',
                    'label' => 'Email',
                    'rules' => 'valid_email|is_unique[user.email]',
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
            ];
        }

        public function verify($token) {
            // Check if 'kode_verif' exists
            $user = $this->db->get_where('user', ['kode_verif' => $token])->row();
        
            if ($user && $user->isVerif == 0) {
                // 'kode_verif' exists and 'isVerif' is not 1, proceed with the update
                $this->db->where('kode_verif', $token)
                         ->update('user', ['isVerif' => 1]);
        
                // Check if the update was successful
                $condition = 'verif';
            } elseif($user && $user->isVerif == 1) {
                // 'kode_verif' doesn't exist or 'isVerif' is already 1, no need to update
                $condition = 'already_verif';
            } else{
                $condition = 'not_found';
            }
        
            return $condition;
        }
        

        public function add(){
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
                'isVerif' => 0
            );
            $result = $this->db->insert('user', $data);
            if ($result){
                $mailAddress  = array(
                    'email' => $data['email'],
                    'kode_verif' => $data['kode_verif']
                );
                return $mailAddress;
            }
        }

        public function add_fromadmin(){
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
                'isVerif' => 1
            );
            $this->db->insert('user', $data);
            return $this->db->insert_id();
        }

        public function importnasabah($data) {
            $this->db->insert('user', $data);
            return $this->db->insert_id();
        }

        public function mail($token, $email){

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
             $config['smtp_pass'] = "hfvj qfwc qfbv qlyo";
             $config['smtp_crypto'] = "ssl";
             $config['charset'] = "utf-8";
             $config['mailtype'] = "html";
             $config['newline'] = "\r\n";
             $config['smtp_timeout'] = 30;
             $config['wordwrap'] = TRUE;
 
             //set
             $this->email->initialize($config);
             $this->email->from('no-replay@jimbaran361@gmail.com','BANK SAMPAH');
             $this->email->to($email);
             $this->email->subject("verifikasi email");
             $this->email->message($message);
 
             //check
             if($this->email->send()){
                 Return "email terkirim ke $email";
             } else{
                 Return "email gagal terkirim";
             }

        }
                
        public function registerTabungan($id){
            date_default_timezone_set('Asia/Manila');
            $data = array(
                'id_user_nasabah' => $id,
                'saldo' => '0',
                'tgl_buka_rekening' => date('y-m-d')
            );

            $result = $this->db->insert('tabungan', $data);
            return $result;
        }

        public function registerTabunganWithSaldo($id, $saldo){
            date_default_timezone_set('Asia/Manila');
            $data = array(
                'id_user_nasabah' => $id,
                'saldo' => $saldo,
                'tgl_buka_rekening' => date('y-m-d')
            );

            $result = $this->db->insert('tabungan', $data);
            return $result;
        }

        public function getUser($token){
            $query = $this->db->get_where('user', array('kode_verif' => $token));
            if ($query->num_rows() > 0) {
                $result = $query->row_array();
                return $result['id_user'];
            } else {
                // Record not found
                return null;
            }
        }

        public function checkUser($username){
            $this->db->select('*');
            $this->db->from('user');
            $this->db->where('username', $username);
            $this->db->where('isVerif', 1);
            $user = $this->db->get();
            return $user;
        }

        public function requestPasswordReset($email) {
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
        public function sendPasswordResetEmail($email, $token) {
            $resetLink = "https://localhost/banksampahnew/index.php/auth/reset_password?token={$token}"; 
    
            $message = "
            <html>
            <head>
              <title>Password Reset</title>
              <style>
                /* Add your email styles here */
              </style>
            </head>
            <body>
              <p>Click the following link to reset your password:</p>
              <a href='$resetLink'>Reset Password</a>
            </body>
            </html>
            ";
    
            $config['useragent'] = "Bank Sampah";
            $config['mailpath'] = "usr/bin/sendmail";
            $config['protocol'] = "smtp";
            $config['smtp_host'] = "smtp.gmail.com";
            $config['smtp_port'] = "465";
            $config['smtp_user'] = "jimbaran361@gmail.com";
            $config['smtp_pass'] = "hfvj qfwc qfbv qlyo";
            $config['smtp_crypto'] = "ssl";
            $config['charset'] = "utf-8";
            $config['mailtype'] = "html";
            $config['newline'] = "\r\n";
            $config['smtp_timeout'] = 30;
            $config['wordwrap'] = TRUE;
    
            $this->email->initialize($config);
            $this->email->from('no-reply@jimbaran361.com', 'BANK SAMPAH');
            $this->email->to($email);
            $this->email->subject("Password Reset");
            $this->email->message($message);
    
            if ($this->email->send()) {
                return "Email reset password terkirim ke $email";
            } else {
                return "Gagal mengirim email reset password.";
            }
        }
    
        // Step 2: Verify Reset Token
        public function verifyResetToken($token) {
            $user = $this->db->get_where('user', ['reset_token' => $token])->row();
    
            if ($user && strtotime($user->reset_token_expiry) > time()) {
                return $user->email;
            } else {
                return false;
            }
        }
    
        // Step 3: Reset Password
        public function resetPassword($token, $newPassword) {
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

        public function updateNasabah($id){
            $data = array(
                'username' => $this->input->post('username'),
                'nama_lengkap' => $this->input->post('nama_lengkap'),
                'tempat_lahir' => $this->input->post('tempat_lahir'),
                'tanggal_lahir' => $this->input->post('tanggal_lahir'),
                'alamat' => $this->input->post('alamat'),
                'notelp' => $this->input->post('notelp'),
                'email' => $this->input->post('email'),
            );
            $this->db->where('id_user', $id);
            $this->db->update('user', $data);
        }
    
    
    }
    
    /* End of file m_auth.php */
    
?>