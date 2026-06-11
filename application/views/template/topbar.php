<div id="page-content-wrapper" class="bg-light">
    <nav class="navbar navbar-expand navbar-light bg-transparent py-3 px-4">
        <div class="d-flex align-items-center">
            <i class="fas fa-align-left primary-text fs-4 me-3" id="menu-toggle"></i>
            <h2 class="fs-4 fs-sm-2 m-0 fw-bold">
                <?= ($this->session->userdata('role') == 'superadmin') ? 'Dashboard Superadmin' : 'Dashboard Admin'; ?>
            </h2>
            <?php
            $CI =& get_instance();
            $banjar_id = $CI->session->userdata('banjar_id');
            $banjar_name = 'Semua Banjar';
            if ($banjar_id) {
                $CI->load->model('M_banjar');
                $banjar = $CI->M_banjar->get_by_id($banjar_id);
                if ($banjar) {
                    $banjar_name = $banjar->nama;
                }
            }
            ?>
            <span class="badge bg-success ms-2 ms-sm-3 fs-7 fs-sm-6"><?= $banjar_name ?></span>
        </div>

        <div class="navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-0">
                <li class="nav-item">
                    <a class="nav-link second-text fw-bold" href="#">
                        <i class="fas fa-user me-2"></i><?= $username ?>
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container-fluid px-4">
        <div class="row g-3 my-2">

            <?php if ($this->session->userdata('role') == 'superadmin'): ?>
                <div class="col-6 col-lg-3">
                    <div class="p-3 bg-white shadow-sm d-flex justify-content-around align-items-center rounded h-100">
                        <div>
                            <h3 class="fs-2 mb-0 card-stat-num"><?= isset($banjarCount) ? $banjarCount : 0 ?></h3>
                            <p class="fs-5 text-muted mb-0 card-stat-label">Total Banjar</p>
                        </div>
                        <i class="fas fa-building fs-1 primary-text border rounded-full secondary-bg p-3 card-stat-icon"></i>
                    </div>
                </div>

                <div class="col-6 col-lg-3">
                    <div class="p-3 bg-white shadow-sm d-flex justify-content-around align-items-center rounded h-100">
                        <div>
                            <h3 class="fs-2 mb-0 card-stat-num"><?= isset($adminCount) ? $adminCount : 0; ?></h3>
                            <p class="fs-5 text-muted mb-0 card-stat-label">Admin Banjar</p>
                        </div>
                        <i class="fas fa-user-shield fs-1 primary-text border rounded-full secondary-bg p-3 card-stat-icon"></i>
                    </div>
                </div>

            <?php else: ?>
                <div class="col-6 col-lg-3">
                    <div class="p-3 bg-white shadow-sm d-flex justify-content-around align-items-center rounded h-100">
                        <div>
                            <h3 class="fs-2 mb-0 card-stat-num"><?= isset($adminCount) ? $adminCount : 0; ?></h3>
                            <p class="fs-5 text-muted mb-0 card-stat-label">Admin</p>
                        </div>
                        <i class="fas fa-user-tie fs-1 primary-text border rounded-full secondary-bg p-3 card-stat-icon"></i>
                    </div>
                </div>

                <div class="col-6 col-lg-3">
                    <div class="p-3 bg-white shadow-sm d-flex justify-content-around align-items-center rounded h-100">
                        <div>
                            <h3 class="fs-2 mb-0 card-stat-num"><?= isset($nasabahCount) ? $nasabahCount : 0; ?></h3>
                            <p class="fs-5 text-muted mb-0 card-stat-label">Nasabah</p>
                        </div>
                        <i class="fas fa-user fs-1 primary-text border rounded-full secondary-bg p-3 card-stat-icon"></i>
                    </div>
                </div>

                <div class="col-6 col-lg-3">
                    <div class="p-3 bg-white shadow-sm d-flex justify-content-around align-items-center rounded h-100">
                        <div>
                            <h3 class="fs-2 mb-0 card-stat-num"><?= isset($transaksiCount) ? $transaksiCount : 0; ?></h3>
                            <p class="fs-5 text-muted mb-0 card-stat-label">Transaksi</p>
                        </div>
                        <i class="fas fa-piggy-bank fs-1 primary-text border rounded-full secondary-bg p-3 card-stat-icon"></i>
                    </div>
                </div>

                <div class="col-6 col-lg-3">
                    <div class="p-3 bg-white shadow-sm d-flex justify-content-around align-items-center rounded h-100">
                        <div>
                            <h3 class="fs-2 mb-0 card-stat-num"><?= isset($artikelCount) ? $artikelCount : 0; ?></h3>
                            <p class="fs-5 text-muted mb-0 card-stat-label">Berita</p>
                        </div>
                        <i class="fas fa-chart-line fs-1 primary-text border rounded-full secondary-bg p-3 card-stat-icon"></i>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>