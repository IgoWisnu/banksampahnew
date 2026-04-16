                </div>
            </div>
        </div>
            
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"></script>
        <script>
                /*
                var el = document.getElementById("wrapper");
                var toggleButton = document.getElementById("menu-toggle");
                var dashboardLink = document.getElementById("dashboard-link");
            
                var nasabahmodalcontainer = document.getElementById("nasabah-modal-container");
                var setor = document.getElementById("setor-content");
                var tarik = document.getElementById("tarik-content");
                var pdf = document.getElementById("pdf-content");
                var nasabah = document.getElementById("nasabah-content");
                var transaksi = document.getElementById("transaksi-content");
                var berita = document.getElementById("berita-content");

                dashboardLink.onclick = function () {
                    console.log('dashboard');
                  
                    setor.style.display = "none";
                    tarik.style.display = "none";
                    pdf.style.display = "none";

                };

                function tampilkanFormTambahBerita() {
                    var formTambahBerita = document.getElementById("form-tambah-berita");
                    formTambahBerita.style.display = "block";
                }

                function tampilkanFormEditBerita() {
                    var formTambahBerita = document.getElementById("form-edit-berita");
                    formTambahBerita.style.display = "block";
                }

                function tambahBerita() {
                        try {
                            var judulBerita = document.getElementById("judulBerita").value;
                            var gambarBerita = document.getElementById("gambarBerita").value;
                            var deskripsiBerita = document.getElementById("deskripsiBerita").value;

                            // Lakukan sesuatu dengan data berita, misalnya simpan ke database

                            var formTambahBerita = document.getElementById("form-tambah-berita");
                            formTambahBerita.style.display = "none";
                        } catch (error) {
                            console.error("Terjadi kesalahan:", error);
                        }
                }
                
                */

                function logoutModal() {
                    $('#logoutModal').modal('show');
                    console.log('confirm : ');
                    // Set the 'id' data to the confirm button
                };

                // Function to handle the confirmed deletion
                function logout() {
                    console.log('action :');
                    // Call your controller method to delete the item
                    window.location.href = "<?php echo site_url('auth/logout'); ?>"
                };
                
                // Fungsi untuk menampilkan/menyembunyikan sidebar
                function toggleSidebar() {
                    var wrapper = document.getElementById("wrapper");
                    wrapper.classList.toggle("toggled");
                }

          
        </script>

    </body>

</html>