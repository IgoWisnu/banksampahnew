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
  .background{
    min-height: 50%; 
    background: #00926E;
  }

  .layImg{
    display: flex;
    justify-content: center;
  }

  .topImg{
    width: 430px;
    position: absolute;
    transition: opacity 1000ms ease-in-out;
  }

  .wrap{
    min-height: 73%;
    margin-top: 255px; 
    position: absolute; 
    background: white; 
    border-top-left-radius: 30px; 
    border-top-right-radius: 30px;
    box-shadow: 0px 10px 10px rgba(0, 0, 0, 0.25);
    transition: all 1s ease-in-out; 
  }

  .t1{
    top: 55px;
    font-size: 30px;
    font-weight: 700;
    position: relative;
  }

  .lCheck{
    top: 40px;
    width: 20%;
    position: relative;
  }
  
  .tTotalBayar{
    font-weight: 600;
    font-size: 20px;
  }

  .tDetailTransaksi{
    font-weight: 600;
    margin-top: 1%;
    margin-bottom: 1%;
  }
  
  .c1{
    top: 60px;
    border-top: none;
    border-left: none;
    border-right: none;
    border-radius: 0px;
    position: relative;
  }

  .btn1{
    font-weight: 500;
    padding: 2%;
    width: 110px;
    top: 12px;
    position: relative;
    background: #00926E;
    border-radius: 30px;
    color: white ;
    text-decoration: none;
  }

  .arrow{
    width: 25%;
    top: -1px;
    position: relative;
    
  }

  @media screen and (min-width: 422px) {
    .wrap{
      width: 422px;
    }
  }

  @media screen and (max-width: 300px){
    .LayBtn1{
      top: 50px;
    }
    
  }
  @media screen and (min-width: 700px) {
    .topImg{
      opacity: 0;
    }
    .background{
      background: rgb(0,146,110);
      background: linear-gradient(0deg, rgba(0,146,110,1) 0%, rgba(0,146,110,1) 20%, rgba(0,146,110,1) 36%, rgba(29,157,131,1) 52%, rgba(75,176,164,1) 78%, rgba(147,205,217,1) 100%);
      background-attachment: fixed;
      transition: 1s ease;
    }
    .wrap{
      margin-top: 205px;
      border-bottom-right-radius: 30px; 
      border-bottom-left-radius: 30px;
      margin-top: 105px;
      transition: all 1s ease;
    }

  }

</style>
<body class="background">
  <div class="layImg">
    <img class="topImg" src="<?=base_url()?>img/Waste recycling Vectors & Illustrations for Free Download _ Freepik 1@2x.png" alt="">
  </div>
  <?php include('logo.php'); ?>
  <div class="col">
    <div class="row justify-content-center">
      <div class="wrap">
        <div class="layBtn">
          <?php
          if($this->session->userdata('role') == 'admin'){
              ?>
              <a class="btn1" type="button" href="<?=base_url()?>dashboard/loadTransaksi">
                <img class="arrow" src="<?=base_url()?>img/aKembali.png" alt="">Kembali
              </a>
              <?php
            }
            else{
              ?>
              <a class="btn1" type="button" href="<?=base_url()?>home/loadArtikel">
                <img class="arrow" src="<?=base_url()?>img/aKembali.png" alt="">Kembali
              </a>
              <?php
            }
            ?>
        </div>
        <div class="row justify-content-center">
          <img class="lCheck" src="<?=base_url()?>img/check.png" alt="">
        </div>
          <div class="t1 row justify-content-center">Transaksi Berhasil</div>
          <div class=""></div>

          <?php foreach($detail->result_array() as $key){ ?>
          <div class="row justify-content-center mt-3">
            <div class="c1 card col-10">
              <div class="date d-flex justify-content-between">
                <div class="text"><?=$key['tgl_tabungan_transaksi'] ?></div>
                <div class="userid me-1"><?=$key['id_user']?></div>
              </div>
            </div>

            <div class="c1 card col-10">
              <div class="tTotalBayar d-flex justify-content-between mt-2 mb-2">Total Bayar
                <div class="Rp">Rp <?=$key['debit'] + $key['kredit']?></div>
              </div>
            </div>

            <div class="c1 card col-10">
              <div class="tDetailTransaksi">Detail Transaksi</div>
              <div class="tIdTransaksi d-flex justify-content-between">ID Transaksi
                <div class="idTransaksi"><?=$key['id_tabungan_transaksi']?></div>
              </div>
              <div class="tTransaksi d-flex justify-content-between">Jenis Transaksi
                <div class="jTeransaksi">Tarik Saldo</div>
              </div>
              <div class="tTotalBayarDT d-flex justify-content-between">Total Bayar
                <div class="Rp">Rp <?=$key['debit'] + $key['kredit']?></div>
              </div>
            </div>

            <div class="c1 card col-10">
              <div class="tDetailTransaksi d-flex justify-content-between">Jenis Sampah
                <div class="idTransaksi">harga</div>
              </div>
              <br>
              <?php foreach($sampah->result_array() as $some){ ?>
                <div class="tIdTransaksi d-flex justify-content-between"><?=$some['jenis_sampah'] ?>
                  <div class="idTransaksi"><?=$some['berat_sampah']?>kg x Rp<?=$some['harga_sampah']?></div>
                </div>
              <?php } ?>
            </div>
          </div>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>
</body>
</html>