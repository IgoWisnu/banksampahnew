<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="shortcut icon" type="image/x-icon" href="<?=base_url()?>img/banjarangkan.png" />
    <title>Setor</title>
</head>
<style>
.background{
    height: 1000px;
    background: rgb(0,146,110);
    background: linear-gradient(0deg, rgba(0,146,110,1) 0%, rgba(0,146,110,1) 20%, rgba(0,146,110,1) 36%, rgba(29,157,131,1) 52%, rgba(75,176,164,1) 78%, rgba(147,205,217,1) 100%);
}

.Panel{
    width: 370px;
    height: 550px; 
    background: white;
    border-top-left-radius: 30px; 
    border-top-right-radius: 30px;
    border-bottom-left-radius: 30px;
    border-bottom-right-radius: 30px;
}

.SelamatDatangKembali{
    top: 30px; 
    text-align: center;
    position: relative;
    color: white; 
    font-size: 40px; 
    font-weight: 700; 
    word-wrap: break-word;
}

.SilahkanMasukanUsernameDanPasswordKamu{
    top: 80px;
    margin-bottom: 100px;
    color: white;
    text-align: center;
    position: relative;
    font-size: 20px; 
    font-weight: 600; 
    word-wrap: break-word;
}

.tLogin{
    top: 35px;
    text-align: center;
    position: relative;
    color: #333333; 
    font-size: 40px; 
    font-weight: 700; 
    word-wrap: break-word;
}

.Username{
    left: 25px;
    top: 70px; 
    position: relative; 
    color: #333333; 
    font-size: 15px; 
    font-weight: 600; 
}

.Password{
    left: 25px;
    top: 100px; 
    position: relative; 
    color: #333333; 
    font-size: 15px;  
    font-weight: 600; 
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
    top: 180px;
    position: relative;
    background-color: #00926E;
}

.btn:hover{
    background-color: #00926E;
}

.tbLogin{
    color: white;
    font-weight: 700;
    font-size: 5mm;
}

.stick{
    left: -2px;
    top: 216px;
    height: 45px;
    position: relative;
    width: 1px;
    margin-right: -5px;
    margin-left: -5px;
    background: black;
}

.link{
    text-decoration: none;
}

</style>
<body>
    <?php
        
    ?>
    <div class="background col justify-content-center">
        <div class="row justify-content-center">
            <div class="SelamatDatangKembali">
                BANKSAMPAH
            </div>
            <div class="SilahkanMasukanUsernameDanPasswordKamu w-75">
                Maaf kamu harus login <br> untuk dapat mengakses halaman ini
            </div>
            <div class="Panel">
                <div class="row justify-content-center">
                    <a href="<?=base_url() ?>auth/goRegister" class="btn btn-success col-10 mb-2 ">Register Sekarang</a>
                    <a href="<?=base_url() ?>auth/logout" class="btn btn-success col-10">Login akun banksampah</a>
                </div>
            </div>
            
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>