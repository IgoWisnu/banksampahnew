<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/x-icon" href="<?=base_url()?>img/banjarangkan.png" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Jenis Sampah</title>
</head>
<style>
  body{
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
    margin-top: 255px; 
    position: relative; 
    background: white; 
    border-top-left-radius: 30px; 
    border-top-right-radius: 30px;
    padding-bottom: 50px;
    box-shadow: 0px 10px 10px rgba(0, 0, 0, 0.25);
    transition: all 1s ease-in-out;
  }

  .btn1{
    margin-bottom: 5%;
    font-weight: 500;
    padding: 2%;
    width: 110px;
    top: 12px;
    position: relative;
    background: #00926E;
    border-radius: 30px;
    color: white ;
    transition: opacity 1000ms ease-in-out;
    text-decoration: none;
  }

  .arrow{
    width: 25%;
    top: -1px;
    position: relative;
    
  }

  .table-responsive{
    margin-left: 3%;
    margin-bottom: 5%;
  }

  @media screen and (min-width: 422px) {
    .wrap{
      width: 422px;
    }
  }

  @media screen and (min-width: 700px) {
    .topImg{
      opacity: 0;
    }
    body{
      background: rgb(0,146,110);
      background: linear-gradient(0deg, rgba(0,146,110,1) 0%, rgba(0,146,110,1) 20%, rgba(0,146,110,1) 36%, rgba(29,157,131,1) 52%, rgba(75,176,164,1) 78%, rgba(147,205,217,1) 100%);
      background-attachment: fixed;
      transition: 1s ease;
    }

    .wrap{
      margin-top: 205px;
      border-bottom-right-radius: 30px; 
      border-bottom-left-radius: 30px;
      transition: all 1s ease;
      width: 722px;
    }
    .gap{
      margin-top: 10% ;
    }
  }
</style>
<script>
  document.addEventListener("DOMContentLoaded", function () {
    var bBar = document.querySelector(".bBar");

    function updateTransparency() {
      var scrollPosition = window.scrollY;

      if (scrollPosition > 200) {
        bBar.classList.add("ilang");
      } else {
        bBar.classList.remove("ilang");
      }
    }
    window.addEventListener("scroll", updateTransparency);
  });
</script>
<body>
  <div class="row justify-content-center">
    <div class="col">
      <img class="topImg" src="<?=base_url()?>img/Waste recycling Vectors & Illustrations for Free Download _ Freepik 1@2x.png" alt="">
    </div> 
    <?php include('logo.php'); ?>
    <div class="row justify-content-center">
      <div class="wrap">
        <a class="btn1" type="button" href="<?=base_url()?>home/loadArtikel">
          <img class="arrow" src="<?=base_url()?>img/aKembali.png" alt="">Kembali
        </a>
        <div class="table-responsive">
          <table class="table mt-4" id='postsList'>
            <tr>
              <td>No</td>
              <td>Jenis Sampah</td>
              <td>Kategori</td>
              <td>Sub Kategori</td>
              <td>Harga/kg</td>
            </tr>
            <?php foreach($sampah->result_array() as $key){ ?>
            <tr>
              <td><?=$key['id'] ?></td>
              <td><?=$key['jenis_sampah'] ?></td>
              <td><?=$key['kategori_sampah'] ?></td>
              <td><?=$key['sub_kategori_sampah'] ?></td>
              <td>Rp <?=$key['harga_sampah'] ?></td>
            </tr>
            <?php } ?>
          </table>
        </div>
      </div>
      <div class="gap"></div>
    </div>
    <?php include('menu.php'); ?>
  </div>
</body>
</html>