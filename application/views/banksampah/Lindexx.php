<!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8" />
            <meta http-equiv="X-UA-Compatible" content="IE=edge" />
            <meta name="viewport" content="width=device-width, initial-scale=1.0" />
            <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
            <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
            <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" />
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css">
            <link rel="shortcut icon" type="image/x-icon" href="<?=base_url()?>img/logo white.png" />
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"></script>
            <title>Admin Dashboard</title>
        </head>

        <style>
            :root {
            --main-bg-color: #009d63;   
            --main-text-color: #009d63;
            --second-text-color: #bbbec5;
            --second-bg-color: #c1efde;
            }

            .primary-text {
            color: var(--main-text-color);
            }

            .second-text {
            color: var(--second-text-color);
            }

            #empty-container {
                background-color: blue;
                padding: 20px;
                margin-top: 20px;
                color: white;
            }

            .primary-bg {
            background-color: var(--main-bg-color);
            }

            .secondary-bg {
            background-color: var(--second-bg-color);
            }

            .rounded-full {
            border-radius: 100%;
            }

            #wrapper {
            overflow-x: hidden;
            background-color: rgb(0,146,110);
            background: linear-gradient(0deg, rgba(0,146,110,1) 0%, rgba(0,146,110,1) 20%, rgba(0,146,110,1) 36%, rgba(29,157,131,1) 52%, rgba(75,176,164,1) 78%, rgba(147,205,217,1) 100%);
            }
        

            #sidebar-wrapper {
            min-height: 100vh;
            margin-left: -15rem;
            -webkit-transition: margin 0.25s ease-out;
            -moz-transition: margin 0.25s ease-out;
            -o-transition: margin 0.25s ease-out;
            transition: margin 0.25s ease-out;
            }

            #sidebar-wrapper .sidebar-heading {
            padding: 0.875rem 1.25rem;
            font-size: 1.2rem;
            }

            #sidebar-wrapper .list-group {
            width: 15rem;
            
            }

            #page-content-wrapper {
            min-width: 100vw;
            } 

            #wrapper.toggled #sidebar-wrapper {
            margin-left: 0;
            }

            #menu-toggle {
            cursor: pointer;
            color: black;
            }

            .list-group-item {
            border: none;
            padding: 20px 30px;
            }

            .list-group-item.active {
            background-color: transparent;
            color: var(--main-text-color);
            font-weight: bold;
            border: none;
            }

            @media (min-width: 768px) {
            #sidebar-wrapper {
                margin-left: 0;
            }

            #page-content-wrapper {
                min-width: 0;
                width: 100%;
            }

            #wrapper.toggled #sidebar-wrapper {
                margin-left: -15rem;
            }

            .popup-container {
                display: none;
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                background-color: white;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                z-index: 1000;
            }

            .ql-editor {
                background-color: white !important;
                border: 1px solid #ccc; /* Optional: Add a border for better visibility */
                min-height: 150px; /* Adjust the height as needed */
            }

            /* Add this style to set the background of the Quill toolbar to white */
            .ql-toolbar {
                background-color: white !important;
            }

            /* Additional styling for better appearance */
            #editor {
                border: 1px solid #ccc;
                border-radius: 10px;
                margin-top: 8px;
                margin-bottom: 16px;
            }


            
        }
        </style>

<body>
    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        <div class="bg-white" id="sidebar-wrapper">
            <div
                class="sidebar-heading text-center py-4 primary-text fs-4 fw-bold text-uppercase border-bottom">
                <img
                    src="<?= base_url() ?>img/logo green.png"
                    alt="Bank Sampah"
                    class="me-2"
                    style="height: 30px; width: 25px;"/>
                Bank Sampah
            </div>
            <div class="list-group list-group-flush my-3">
                <a 
                    href="<?= base_url() ?>dashboard/index" 
                    class="btn list-group-item list-group-item-action bg-transparent second-text active">
                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard</a>

                <a
                    href="#"
                    id="nasabah-link"
                    class="list-group-item list-group-item-action bg-transparent second-text fw-bold"
                    onclick="loadNasabahContent()">
                    <i class="fas fa-user fa-beat me-2"></i>Nasabah</a>
                <a
                    href="#"
                    id="berita-link"
                    class="list-group-item list-group-item-action bg-transparent second-text fw-bold"
                    onclick="loadBeritaContent()">
                    <i class="fas fa-chart-line me-2"></i>Berita</a>
                <a
                    href="#"
                    id="transaksi-link"
                    class="list-group-item list-group-item-action bg-transparent second-text fw-bold"
                    onclick="loadTransaksiContent()">
                    <i class="fa fa-credit-card me-2"></i>Transaksi</a>
                <a
                    href="#"
                    id="setor-link"
                    class="list-group-item list-group-item-action bg-transparent second-text fw-bold"
                    onclick="loadSetorContent()">
                    <i class="fas fa-arrow-down me-2"></i>Setor</a>
                <a
                    href="#"
                    id="tarik-link"
                    class="list-group-item list-group-item-action bg-transparent second-text fw-bold"
                    onclick="loadTarikContent()">
                    <i class="fas fa-arrow-up me-2"></i>Tarik</a>
                <a
                    href="#"
                    id="pdf-link"
                    class="list-group-item list-group-item-action bg-transparent second-text fw-bold"
                    onclick="loadLaporanContent()">
                    <i class="fas fa-file-pdf me-2"></i>PDF</a>
                <a
                    href="<?php echo base_url('auth/logout/') ?>"
                    class="list-group-item list-group-item-action bg-transparent text-danger fw-bold">
                    <i class="fas fa-power-off me-2"></i>Logout</a>
            </div>
        </div>

        <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-lg navbar-light bg-transparent py-4 px-4">
                <div class="d-flex align-items-center">
                    <i class="fas fa-align-left primary-text fs-4 me-3" id="menu-toggle"></i>
                    <h2 class="fs-2 m-0">Dashboard Admin</h2>
                </div>

                <button
                    class="navbar-toggler"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent"
                    aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item dropdown">
                            <a
                                class="nav-link dropdown-toggle second-text fw-bold"
                                href="#"
                                id="navbarDropdown"
                                role="button"
                                data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i class="fas fa-user me-2"></i><?=$username?>
                            </a>
                            <!-- <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li>
                                    <a class="dropdown-item" href="<?php echo base_url('profile/index/') ?>">Profile</a>
                                </li>
                            </ul> -->
                        </li>
                    </ul>
                </div>
            </nav>

            <div class="container-fluid px-4">
                <div class="row g-3 my-2">
                    <div class="col-md-3">
                        <div
                            class="p-3 bg-white shadow-sm d-flex justify-content-around align-items-center rounded">
                            <div>
                                <h3 class="fs-2"><?php echo $adminCount; ?></h3>
                                <p class="fs-5">Admin</p>
                            </div>
                            <i
                                class="fas fa-user-tie fs-1 primary-text border rounded-full secondary-bg p-3"></i>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div
                            class="p-3 bg-white shadow-sm d-flex justify-content-around align-items-center rounded">
                            <div>
                                <h3 class="fs-2"><?php echo $nasabahCount; ?></h3>
                                <p class="fs-5">Nasabah</p>
                            </div>
                            <i class="fas fa-user fs-1 primary-text border rounded-full secondary-bg p-3"></i>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div
                            class="p-3 bg-white shadow-sm d-flex justify-content-around align-items-center rounded">
                            <div>
                                <h3 class="fs-2"><?php echo $transaksiCount; ?></h3>
                                <p class="fs-5">Transaksi</p>
                            </div>
                            <i
                                class="fas fa-piggy-bank fs-1 primary-text border rounded-full secondary-bg p-3"></i>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div
                            class="p-3 bg-white shadow-sm d-flex justify-content-around align-items-center rounded">
                            <div>
                                <h3 class="fs-2"><?php echo $artikelCount; ?></h3>
                                <p class="fs-5">Berita</p>
                            </div>
                            <i
                                class="fas fa-chart-line fs-1 primary-text border rounded-full secondary-bg p-3"></i>
                        </div>
                    </div>
                </div>

                <!-- flashdata alert handle -->
            <?php 
                            $success = $this->session->flashdata('success');
                            $failed = $this->session->flashdata('failed');

                            if($success){
                                echo '<div class="alert alert-success">'.$success.'</div>';
                            }
                            elseif($failed){
                                echo '<div class="alert alert-failed">'.$failed.'</div>';
                            }
            ?>

                <div id="setor-content" class="mt-3">
                    <!-- Content from Setor will be loaded here -->
                </div>

                <div id="tarik-content" class="mt-3">
                    <!-- Content from Tarik will be loaded here -->

                </div>

                <div id="pdf-content" class="mt-3">
                    <!-- Content from Tarik will be loaded here -->

                </div>

                <div id="nasabah-content" class="mt-3">
                    <!-- Content from Nasabah will be loaded here -->

                </div>

                <div id="transaksi-content">
                    <!-- Content from transaksi will be loaded here -->

                </div>

                <div id="berita-content">
                    <!-- Content from berita will be loaded here -->
                </div>
            </div>
        </div>
    </div>
            
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"></script>
<script>
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

                toggleButton.onclick = function () {
                    el.classList.toggle("toggled");
                };

                dashboardLink.onclick = function () {
                    console.log('dashboard');
                  
                    setor.style.display = "none";
                    tarik.style.display = "none";
                    pdf.style.display = "none";

                };

                function loadSetorContent() {
                     // Fetch content from the setorSampah controller's index function and update #setor-content
                    $("#setor-content").load("setorSampah");
                    setor.style.display = "block";
                    tarik.style.display = "none";
                    pdf.style.display = "none";
                    transaksi.style.display = "none";
                    nasabah.style.display = "none";
                    berita.style.display = "none";
                }
                function loadTarikContent() {
                     // Fetch content from the tarikSampah controller's index function and update #tarik-content
                    $("#tarik-content").load("tarik");
                    tarik.style.display = "block";
                    setor.style.display = "none";
                    pdf.style.display = "none";
                    transaksi.style.display = "none";
                    nasabah.style.display = "none";
                    berita.style.display = "none";
                }

                function loadLaporanContent() {
                    console.log('loadLaporan');
                    $("#pdf-content").load("generatepdf");
                    pdf.style.display = "block";
                    tarik.style.display = "none";
                    setor.style.display = "none";
                    transaksi.style.display = "none";
                    nasabah.style.display = "none";
                    berita.style.display = "none";
                } 

                function loadNasabahContent(){
                    console.log('loadLaporan');
                    $("#nasabah-content").load("dashboard/loadNasabah");
                    nasabah.style.display = "block";
                    pdf.style.display = "none";
                    tarik.style.display = "none";
                    setor.style.display = "none";
                    transaksi.style.display = "none";
                    berita.style.display = "none";
                }

                function loadTransaksiContent(){
                    console.log('loadTransaksi');
                    $("#transaksi-content").load("dashboard/loadTransaksi");
                    transaksi.style.display = "block";
                    nasabah.style.display = "none";
                    pdf.style.display = "none";
                    tarik.style.display = "none";
                    setor.style.display = "none";
                    berita.style.display = "none";
                }

                function loadBeritaContent(){
                    console.log('loadBerita');
                    $("#berita-content").load("dashboard/loadBerita");
                    berita.style.display = "block";
                    nasabah.style.display = "none";
                    pdf.style.display = "none";
                    tarik.style.display = "none";
                    setor.style.display = "none";
                    transaksi.style.display = "none";
                }



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
                
                
                // Fungsi untuk menampilkan/menyembunyikan sidebar
                function toggleSidebar() {
                    var el = document.getElementById("wrapper");
                    el.classList.toggle("toggled");
                    // Simpan status sidebar ke localStorage
                    localStorage.setItem("sidebarToggled", el.classList.contains("toggled"));
                }   

                
                
            </script>

        </body>

        </html>