<body>
    <!-- Nasabah -->
    <div id="nasabah-table-container" class="containered pt-3">
        <div class="row mx-2">

                <!-- Modal Tambah Nasabah -->
                <div
                    class="modal fade"
                    id="tambahNasabahModal"
                    tabindex="-1"
                    aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Nasabah</h1>
                                <button
                                    type="button"
                                    class="btn-close"
                                    data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form
                                    action="<?= base_url('dashboard/tambahnasabah') ?>"
                                    method="post"
                                    enctype="multipart/form-data">

                                    <div class="mb-3">
                                        <label for="username" class="form-label">Username</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="username"
                                            name="username"
                                            placeholder="Masukkan Username Nasabah">
                                    </div>
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <input
                                            type="password"
                                            class="form-control"
                                            id="password"
                                            name="password"
                                            placeholder="Masukkan Password Nasabah">
                                    </div>
                                    <div class="mb-3">
                                        <label for="notelp" class="form-label">Nomor HP</label>
                                        <input
                                            type="number"
                                            class="form-control"
                                            id="notelp"
                                            name="notelp">
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email Nasabah</label>
                                        <input
                                            type="number"
                                            class="form-control"
                                            id="email"
                                            name="email">
                                    </div>
                                    <div class="">
                                        <h4>Data Pelengkap</h4>
                                    </div>
                                    <div class="mb-3">
                                        <label for="nama_lengkap" class="form-label">Nama Lengkap Nasabah</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="nama_lengkap"
                                            name="nama_lengkap"
                                            placeholder="Masukkan Nama Lengkap Nasabah">
                                    </div>
                                    <div class="mb-3">
                                        <label for="tempat_lahir" class="form-label">tempat_lahir</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="tempat_lahir"
                                            name="tempat_lahir"
                                            placeholder="Masukkan tempat_lahir Nasabah">
                                    </div>
                                    <div class="mb-3">
                                        <label for="tanggal_lahir" class="form-label">tanggal Lahir</label>
                                        <input
                                            type="date"
                                            class="form-control"
                                            id="tanggal_lahir"
                                            name="tanggal_lahir">
                                    </div>
                                    <div class="mb-3">
                                        <label for="alamat" class="form-label">Alamat</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="alamat"
                                            name="alamat">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Tambah</button>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

                
                <!-- Modal Import excel Nasabah -->
                <div
                    class="modal fade"
                    id="importNasabahModal"
                    tabindex="-1"
                    aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Import Excel Nasabah</h1>
                                <button
                                    type="button"
                                    class="btn-close"
                                    data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form
                                    action="<?= base_url('dashboard/importnasabah') ?>"
                                    method="post"
                                    enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <label for="username" class="form-label">Username</label>
                                        <input
                                            type="file"
                                            class="form-control"
                                            id="excel_nasabah"
                                            name="excel_nasabah">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Tambah</button>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabel Nasabah -->
                <h3 class="fs-4 mb-3">Tabel Nasabahsaas</h3>
                <div class="row align-items-start">

                    <div class="col-lg-6">
                        <form action="<?=base_url()?>dashboard/loadNasabah" method="post">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Enter username/email" id="keyword" name="keyword">
                                <input type="submit" class="btn btn-primary" id="submit" name="submit" value="search"></input>
                            </div>
                        </form>
                    </div>
                    <?=$_SESSION['keyword_nasabah'] ?>
                    <div class="col-lg-4">
                        <button
                            type="button"
                            class="btn btn-success mb-3 ms-0"
                            data-bs-toggle="modal"
                            data-bs-target="#tambahNasabahModal">
                            Tambah Nasabah
                        </button>
                        <a
                            href="<?=base_url()?>uploads/excel/template_banksampah.xlsx"
                            class="btn btn-primary mb-3 ms-0"
                            >
                            Download Excel
                        </a>
                        <button
                            type="button"
                            class="btn btn-warning mb-3 ms-0"
                            data-bs-toggle="modal"
                            data-bs-target="#importNasabahModal">
                            Import Excel
                        </button>
                    </div>
                </div>
            </div>
            <div class="row mx-3">
                <div class="table-responsive ">
                    <table class="table bg-light rounded shadow-sm table-hover">
                        <thead>
                            <tr>
                                <th scope="col">username</th>
                                <th scope="col">Nama Lengkap</th>
                                <th scope="col">Tanggal Lahir</th>
                                <th scope="col">Email</th>
                                <th scope="col">Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($user->result_array() as $key) { 
                                if ($key['role'] === 'admin') {
                                    continue;
                                }
                            ?>
                            <tr>
                                <td><?php echo $key['username'] ?></td>
                                <td><?php echo $key['nama_lengkap'] ?></td>
                                <td><?php echo $key['tanggal_lahir'] ?></td>
                                <td><?php echo $key['email'] ?></td>
                                <td>
                                    <button
                                        type="button"
                                        class="btn btn-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#exampleModal"
                                        data-profile="<?php echo $key['profile'] ?>"
                                        data-nama="<?php echo $key['nama_lengkap'] ?>"
                                        data-tempat-lahir="<?php echo $key['tempat_lahir'] ?>"
                                        data-tanggal-lahir="<?php echo $key['tanggal_lahir'] ?>"
                                        data-alamat="<?php echo $key['alamat'] ?>"
                                        data-email="<?php echo $key['email'] ?>"
                                        data-telepon="<?php echo $key['notelp'] ?>">
                                        Detail
                                    </button>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                <!-- Paginate -->
                <div style="margin-top: 10px;" id="pagination" class="">
                    <?=$pagination ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div
        class="modal fade"
        id="exampleModal"
        tabindex="-1"
        aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">User Details</h1>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img
                        id="modal-profile-img"
                        src=""
                        alt="Profile Image"
                        class="img-fluid rounded-circle mx-auto d-block">
                    <p id="modal-nama"></p>
                    <p id="modal-tempat-lahir"></p>
                    <p id="modal-tanggal-lahir"></p>
                    <p id="modal-alamat"></p>
                    <p id="modal-email"></p>
                    <p id="modal-telepon"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script>
        $('#exampleModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var modal = $(this);
            var profileImageSrc = '<?= base_url('uploads/profile/'); ?>' + button.data('profile');

            modal
                .find('#modal-profile-img')
                .attr('src', profileImageSrc);
            modal
                .find('#modal-nama')
                .text('Nama Lengkap: ' + button.data('nama'));
            modal
                .find('#modal-tempat-lahir')
                .text('Tempat Lahir: ' + button.data('tempat-lahir'));
            modal
                .find('#modal-tanggal-lahir')
                .text('Tanggal Lahir: ' + button.data('tanggal-lahir'));
            modal
                .find('#modal-alamat')
                .text('Alamat: ' + button.data('alamat'));
            modal
                .find('#modal-email')
                .text('Email: ' + button.data('email'));
            modal
                .find('#modal-telepon')
                .text('Nomor Telepon: ' + button.data('telepon'));
        });
    </script>
</body>
</html>