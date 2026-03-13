<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/x-icon" href="<?=base_url()?>img/banjarangkan.png" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>SignUp</title>
</head>
  
<style>
    .background{
        height: 1500px;
        background: rgb(0,146,110);
        background: linear-gradient(0deg, rgba(0,146,110,1) 0%, rgba(0,146,110,1) 20%, rgba(0,146,110,1) 36%, rgba(29,157,131,1) 52%, rgba(75,176,164,1) 78%, rgba(147,205,217,1) 100%);
    }

    .Panel{
        width: 370px;
        height: 1000px; 
        background: white;
        border-radius: 30px; 
    }

    .SelamKenal{
        top: 30px; 
        text-align: center;
        position: relative;
        color: white; 
        font-size: 40px; 
        font-weight: 700; 
        word-wrap: break-word;
    }

    .SilahkanIsiDataDiriUntukMenggunakanBankSampah{
        top: 50px;
        margin-bottom: 85px;
        color: white;
        text-align: center;
        position: relative;
        font-size: 15px; 
        font-weight: 600; 
        word-wrap: break-word;
    }

    .AtauSudahPunyaAkun{
        margin-top: -30px;
        margin-bottom: 15px;
        color: white;
        text-align: center;
        position: relative;
        font-size: 15px; 
        font-weight: 600; 
        word-wrap: break-word;
    }

    .tSignUp{
        top: 35px;
        margin-bottom: 70px;
        text-align: center;
        position: relative;
        color: #333333; 
        font-size: 40px; 
        font-weight: 700; 
        word-wrap: break-word;
    }

    .form{
        left: 25px;
        justify-content: center;
        display: flex;
        font-size:   90%;
        font-weight: 500;
    }

    .form-control{
        width: 300px;
        border-width: 2px;
        border-color: black;
        padding: 11px;
        border-radius: 5mm;
    }
    
    
    .btn{
        border-radius: 5mm;
        top: 25px;
        position: relative;
        background: rgb(0,146,110);
    }

    .btn:hover{
        background: rgb(0,146,110);
    }

    .tbSignUp{
        color: white;
        font-weight: 700;
        font-size: 5mm;
    }

    .link{
        text-decoration: none;
    }

</style>
<body class="background">
    <form action="<?=base_url('auth/mail') ?>" method="post">
        <div class="row justify-content-center">
            <div class="SelamKenal">
                Salam Kenal
            </div>
            <div class="SilahkanIsiDataDiriUntukMenggunakanBankSampah w-75">
                Silahkan Isi Data Diri Untuk Menggunakan Bank Sampah
            </div>
            <div class="AtauSudahPunyaAkun">
                Atau Sudah Punya Akun?<a class="link" href="<?=base_url('auth') ?>">Login</a>
            </div>
        </div>
    
        <div class="row justify-content-center">
            <div class="Panel">
                <div class="tSignUp">SignUp</div>
                <div class="form"> 
                    <div class="pb-2">Username:
                        <input type="text" id="username" class="form-control row justify-content-center" name="username" placeholder="Enter your username" value="<?=set_value('username') ?>">
                        <small class="text-danger"><?= form_error('username') ?></small>
                    </div>
                </div>
                <div class="form">
                    <div class="pb-2">Email:
                        <input type="email" id="email" class="form-control row justify-content-center" name="email" placeholder="Enter your email address" value="<?=set_value('email') ?>">
                        <small class="text-danger"><?= form_error('email') ?></small>
                    </div>
                </div>
                <div class="form">
                    <div class="pb-2">Nama Lengkap:
                        <input type="text" id="nama_lengkap" class="form-control row justify-content-center" name="nama_lengkap" placeholder="Enter your full name" value="<?=set_value('nama_lengkap') ?>">
                        <small class="text-danger"><?= form_error('nama_lengkap') ?></small>
                    </div>
                </div>
                <div class="form">
                    <div class="pb-2">Tempat Lahir:
                        <input type="text" id="tempat_lahir" class="form-control row justify-content-center" name="tempat_lahir" placeholder="Enter your place of birth" value="<?=set_value('tempat_lahir') ?>">
                        <small class="text-danger"><?= form_error('tempat_lahir') ?></small>
                    </div>
                </div>
                <div class="form">
                    <div class="pb-2">Tanggal Lahir:
                        <input type="date" id="tanggal_lahir" class="form-control row justify-content-center" name="tanggal_lahir" placeholder="Enter your date of birth" value="<?=set_value('tanggal_lahir') ?>">
                        <small class="text-danger"><?= form_error('tanggal_lahir') ?></small>
                    </div>
                </div>
                <div class="form">
                    <div class="pb-2">Alamat:
                        <input type="text" id="alamat" class="form-control row justify-content-center" name="alamat" placeholder="Enter your address" value="<?=set_value('alamat') ?>">
                        <small class="text-danger"><?= form_error('alamat') ?></small>
                    </div>
                </div>
                <div class="form">
                    <div class="pb-2">No Telp:
                        <input type="text" id="notelp" class="form-control row justify-content-center" name="notelp" placeholder="Enter your phone number" value="<?=set_value('notelp') ?>">
                        <small class="text-danger"><?= form_error('notelp') ?></small>
                        </div>
                    </div>
                <div class="form">
                    <div class=" pb-2">Password:
                        <input type="password" id="password" class="form-control row justify-content-center" name="password" placeholder="Enter your password">
                        <small class="text-danger"><?= form_error('password') ?></small>
                    </div>
                </div>
                <div class="form">
                    <div class=" pb-2">Verify Password:
                        <input type="password" id="verify_password" class="form-control row justify-content-center" name="verify_password" placeholder="Re-enter your password" >
                        <small class="text-danger"><?= form_error('verify_password') ?></small>
                    </div>
                </div>
                <div class="form row justify-content-center">
                    <div class="form-check ps-5 pe-5 pt-4 ms-4">
                        <input class="form-check-input" type="checkbox" id="agreementCheck">
                        Saya menyetujui semua ketentuan yang berlaku
                    </div>
                </div>
                <div class="row justify-content-center">
                    <input type="submit" class="tbSignUp btn w-50" value="SignUp"></input>
                </div>
            </div>
        </div>
    </form>
</body>
</html>