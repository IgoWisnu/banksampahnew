<style>
.bBar{
  width: 100%;
  height: 5.5%;
  bottom: 0;
  position: fixed;
  border-top-right-radius: 30px; 
  border-top-left-radius: 30px;
  background-color: white;
  box-shadow: 0px -4px 4px rgba(0, 0, 0, 0.25);
  transition: 1s ease;
  align-items: center;
}

.history,.home,.profile{
  width: 40px;
}

@media screen and (min-width: 422px) {
  .bBar{
    width: 422px;
  }
}

@media screen and (min-width: 700px) {
  .bBar{
    position: fixed;
    height: 5.5%;
    margin-bottom: 5px;
    border-bottom-right-radius: 30px; 
    border-bottom-left-radius: 30px;
    border-top-left-radius: 0px;
    border-top-right-radius: 0px;
    background-color: white;
    box-shadow: none;
    bottom: 94.5%;
    transition: all 1s ease;
    box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25); 
  }
  .bBar.ilang {
    background-color: white;
    box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
    transition: all 0.5s ease;
  }
}

</style>
<script>
  document.addEventListener("DOMContentLoaded", function () {
    var bBar = document.querySelector("");

    function updateTransparency() {
      var scrollPosition = window.scrollY;

      if (scrollPosition > 180) {
        bBar.classList.add("ilang");
      } else {
        bBar.classList.remove("ilang");
      }
    }

    window.addEventListener("scroll", updateTransparency);
  });
</script>

<div class="d-flex justify-content-center">
  <div class="bBar d-flex justify-content-evenly" id="myID">
  <a href="<?=base_url()?>riwayat">
    <img src="<?=base_url()?>img/history.png" alt="" class="history">
  </a>
  <a href="<?=base_url()?>home/loadArtikel">
    <img src="<?=base_url()?>img/home.png" alt="" class="home">
  </a>
  <a href="<?=base_url()?>Profile">
    <img src="<?=base_url()?>img/profile.png" alt="" class="profile">
  </a>
  </div>
</div>
