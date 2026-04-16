<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/x-icon" href="<?=base_url()?>img/banjarangkan.png" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Document</title>
</head>
<style>
    body{
        height: 100%;
        background: rgb(0,146,110);
        background: linear-gradient(0deg, rgba(0,146,110,1) 0%, rgba(0,146,110,1) 20%, rgba(0,146,110,1) 36%, rgba(29,157,131,1) 52%, rgba(75,176,164,1) 78%, rgba(147,205,217,1) 100%);
        background-attachment: fixed;
    }

    .Panel{ 
        padding-bottom: 60px;
        width: 372px;
        min-height: 650px;
        background: white;
        border-top-right-radius: 30px;
        border-top-left-radius: 30px;
        transition: 1s ease;
    }

    .layImg{
        justify-content: center;
        display: flex;
    }

    .img{
        width: 120px;
        margin-top: 60px;
        position: relative;
    }

    .deskripsi{
        font-weight: 450;
        text-align: center;
        display: flex;
        justify-content: center;
        font-size: 20px;
    }

    .resend{
        font-weight: 450;
        margin-top: 220px;
        position: relative;
    }

    .t1{ 
        margin-top: 45px;
        text-align: center;
        position: relative;
        color: white; 
        font-size: 55px; 
        font-weight: 700; 
        word-wrap: break-word;
    }

    .t2{
        margin-bottom: 60px;
        color: white;
        text-align: center;
        position: relative;
        font-size: 20px; 
        font-weight: 600; 
        word-wrap: break-word;
    }
   
    .link{
        text-decoration: none;
    }

    @media screen and (min-width: 400px){
        .Panel{
            border-bottom-right-radius: 30px;
            border-bottom-left-radius: 30px;
            transition: 1s ease;
        }

    }

</style>

<body>
    <div class="Container col">
        <div class="row justify-content-center">
            <div class="Verifikasi row t1 justify-content-center">
                Verifikasi
            </div>
            <div class="t2">
                Kami telah mengirimkan kode <br> verifikasi pada email anda
            </div>
            
            <div class="Panel">                    
                <div class="layImg">
                    <img class="img" src="/img/email.png" alt="">
                </div>
                
                <div class="resend row justify-content-center">
                    Terdapat Masalah?<a href="" class="link row justify-content-center">Laporkan!</a>
                </div>
            </div>
            <div class="gap"></div>
        </div>
    </div>
</body>
</html>