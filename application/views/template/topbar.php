<div id="page-content-wrapper" class="bg-light">
            <nav class="navbar navbar-expand-lg navbar-light bg-transparent py-4 px-4">
                <div class="d-flex align-items-center">
                    <button id="menu-toggle" class="btn fs-2 me-2" onclick="toggleSidebar()">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h2 class="fs-2 m-0">Dashboard Admin</h2>
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