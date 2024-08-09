<div id="page-content-wrapper" class="bg-light">
            <nav class="navbar navbar-expand-lg navbar-light bg-transparent py-4 px-4">
                <div class="d-flex align-items-center">
                    <button id="menu-toggle" class="btn fs-2 me-2" onclick="toggleSidebar()">
                        <i class="fas fa-bars"></i>
                    </button>
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
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li>
                                    <a class="dropdown-item" href="<?php echo base_url('profile/index/') ?>">Profile</a>
                                </li>
                            </ul>
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