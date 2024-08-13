<body>
    <!-- SAMpah -->
    <div id="sampah-table-container" class="containered pt-3">
        <div class="row mx-2">

                <!-- Modal tambah sampah -->
                <div
                    class="modal fade"
                    id="tambahSampahModal"
                    tabindex="-1"
                    aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Sampah</h1>
                                <button
                                    type="button"
                                    class="btn-close"
                                    data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form
                                    action="<?= base_url('dashboard/tambahSampah') ?>"
                                    method="post"
                                    enctype="multipart/form-data">

                                    <div class="mb-3">
                                        <label for="jenis_sampah" class="form-label">Jenis Sampah</label>
                                            <input
                                                type="text"
                                                class="form-control"
                                                name="jenis_sampah"
                                                placeholder="Masukkan Jenis Sampah"
                                                >
                                    </div>
                                    <div class="mb-3">
                                        <label for="kategori_sampah" class="form-label">Kategori</label>
                                            <input
                                                type="text"
                                                class="form-control"
                                                name="kategori_sampah"
                                                placeholder="Masukkan Kategori Sampah"
                                                >
                                    </div>
                                    <div class="mb-3">
                                        <label for="sub_kategori_sampah" class="form-label">Sub Kategori</label>
                                            <input
                                                type="text"
                                                class="form-control"
                                                name="sub_kategori_sampah"
                                                placeholder="Masukkan Sub Kategori Sampah"
                                                >
                                    </div>
                                    <div class="mb-3">
                                        <label for="harga" class="form-label">Harga</label>
                                            <input
                                                type="number"
                                                class="form-control"
                                                name="harga"
                                                placeholder="Masukkan Harga"
                                                >
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

                               
                <!-- Modal Import excel Sampah -->
                <div
                    class="modal fade"
                    id="importSampahModal"
                    tabindex="-1"
                    aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Import Excel Sampah</h1>
                                <button
                                    type="button"
                                    class="btn-close"
                                    data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form
                                    action="<?= base_url('dashboard/importsampah') ?>"
                                    method="post"
                                    enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <label for="excel_sampah" class="form-label">Excel Sampah</label>
                                        <input
                                            type="file"
                                            class="form-control"
                                            id="excel_sampah"
                                            name="excel_sampah">
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

                <!-- Tabel Sampah -->
                <h3 class="fs-4 mb-3">Tabel Sampah</h3>
                <div class="row align-items-start">

                    <div class="col-lg-6">
                        <form action="<?=base_url()?>dashboard/loadSampah" method="post">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Enter username/email" id="keyword" name="keyword">
                                <input type="submit" class="btn btn-primary" id="submit" name="submit" value="search"></input>
                            </div>
                        </form>
                    </div>
                    <div class="col-lg-4">
                        <button
                            type="button"
                            class="btn btn-success mb-3 ms-0"
                            data-bs-toggle="modal"
                            data-bs-target="#tambahSampahModal">
                            Tambah Sampah
                        </button>
                        <a
                            href="<?=base_url()?>uploads/excel/template_sampah_banksampah.xlsx"
                            class="btn btn-primary mb-3 ms-0"
                            >
                            Download Excel
                        </a>
                        <button
                            type="button"
                            class="btn btn-warning mb-3 ms-0"
                            data-bs-toggle="modal"
                            data-bs-target="#importSampahModal">
                            Import Excel
                        </button>
                    </div>
                </div>
            </div>
            <div class="row mx-3">
                <div class="table-responsive">
                    <table class="wtable table bg-light rounded shadow-sm table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Jenis Sampah</th>
                                <th>Kategori</th>
                                <th>Sub Kategori</th>
                                <th>Harga/kg</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($sampah->result_array() as $key) { ?>
                            <tr>
                                <td><?=$key['id'] ?></td>
                                <td><?=$key['jenis_sampah'] ?></td>
                                <td><?=$key['kategori_sampah'] ?></td>
                                <td><?=$key['sub_kategori_sampah'] ?></td>
                                <td>Rp <?=$key['harga_sampah'] ?></td>
                                <td>
                                <a href="<?=base_url()?>dashboard/editSampah?id_sampah=<?=$key['id']?>" class="btn btn-warning">Edit</a>
                                    <a href="<?=base_url()?>dashboard/deleteSampah?id_sampah=<?=$key['id']?>" class="btn btn-danger">Delete</a>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

                <!-- Paginate -->
                <div style='margin-top: 10px;' id='pagination' class="">
                    <?=$pagination ?>
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