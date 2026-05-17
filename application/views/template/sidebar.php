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
                    href="<?=base_url()?>dashboard/loadNasabah"
                    id="nasabah-link"
                    class="list-group-item list-group-item-action bg-transparent second-text fw-bold"
                    >
                    <i class="fas fa-user fa-beat me-2"></i>Nasabah</a>
                <a
                    href="<?=base_url()?>dashboard/loadBerita"
                    id="berita-link"
                    class="list-group-item list-group-item-action bg-transparent second-text fw-bold"
                    >
                    <i class="fas fa-chart-line me-2"></i>Berita</a>
                <a
                    href="<?=base_url()?>dashboard/loadTransaksi"
                    id="transaksi-link"
                    class="list-group-item list-group-item-action bg-transparent second-text fw-bold"
                    >
                    <i class="fa fa-credit-card me-2"></i>Transaksi</a>
                
                <a
                    href="<?=base_url()?>dashboard/loadSampah"
                    id="transaksi-link"
                    class="list-group-item list-group-item-action bg-transparent second-text fw-bold"
                    >
                    <i class="fa fa-trash me-2"></i>Sampah</a>

                <a
                    href="<?=base_url()?>dashboard/loadRiwayatHarga"
                    id="transaksi-link"
                    class="list-group-item list-group-item-action bg-transparent second-text fw-bold"
                    >
                    <i class="fa fa-history me-2"></i>Riwayat Harga</a>

                <a
                    href="<?=base_url()?>setorSampah"
                    id="setor-link"
                    class="list-group-item list-group-item-action bg-transparent second-text fw-bold"
                    >
                    <i class="fas fa-arrow-down me-2"></i>Setor</a>
                <a
                    href="<?=base_url()?>tarik"
                    id="tarik-link"
                    class="list-group-item list-group-item-action bg-transparent second-text fw-bold"
                    >
                    <i class="fas fa-arrow-up me-2"></i>Tarik</a>
                <a
                    href="<?=base_url()?>generatepdf"
                    id="pdf-link"
                    class="list-group-item list-group-item-action bg-transparent second-text fw-bold"
                    >
                    <i class="fas fa-file-pdf me-2"></i>PDF</a>
                <a
                    type="button"
                    onclick="logoutModal()"
                    class="list-group-item list-group-item-action bg-transparent text-danger fw-bold">
                    <i class="fas fa-power-off me-2"></i>Logout</a>
            </div>
        </div>

        <div
            class="modal fade"
            id="logoutModal"
            tabindex="-1"
            aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Logout Dashboard Admin</h1>
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Apakah anda yakin ingin logout?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-danger" id="confirmDelete" onclick="logout()">Logout</button>
                    </div>
                </div>
            </div>
        </div>