
<style>
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
<div class="bg-white" id="sidebar-wrapper">
                <div class="sidebar-heading text-center py-4 primary-text fs-4 fw-bold text-uppercase border-bottom">
                    <img src="<?= base_url() ?>img/logo green.png" alt="Bank Sampah" class="me-2" style="height: 30px; width: 25px;" />
                    Bank Sampah
                </div>

                <div class="list-group list-group-flush my-3">
                    <a href="#" id="dashboard-link" class="list-group-item list-group-item-action bg-transparent second-text active"><i
                                class="fas fa-tachometer-alt me-2"></i>Dashboard</a>
                    <a href="#" id="nasabah-link" class="list-group-item list-group-item-action bg-transparent second-text fw-bold"><i
                                class="fas fa-user fa-beat me-2"></i>Nasabah</a>
                    <a href="#" id="berita-link" class="list-group-item list-group-item-action bg-transparent second-text fw-bold"><i
                                class="fas fa-chart-line me-2"></i>Berita</a>
                    <a href="#" id="setor-link" class="list-group-item list-group-item-action bg-transparent second-text fw-bold" onclick="loadSetorContent()"><i 
                                class="fas fa-arrow-down me-2"></i>Setor</a>                          
                    <a href="#" id="tarik-link" class="list-group-item list-group-item-action bg-transparent second-text fw-bold" onclick="loadTarikContent()"><i 
                                class="fas fa-arrow-up me-2"></i>Tarik</a>
                    <?php if($this->session->userdata('role') == 'admin'): ?>
                    <a href="#" id="stok-link" class="list-group-item list-group-item-action bg-transparent second-text fw-bold" onclick="loadStokContent()"><i 
                                class="fas fa-boxes me-2"></i>Sistem Stok</a>
                    <?php endif; ?>
                    <a href="<?php echo base_url('auth/logout/') ?>" class="list-group-item list-group-item-action bg-transparent text-danger fw-bold"><i
                                class="fas fa-power-off me-2"></i>Logout</a>
                </div>
</div>  