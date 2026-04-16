<body>
    <!-- SAMpah -->
    <div id="sampah-table-container" class="containered pt-3">
        <div class="row mx-2">
            <form action="<?=base_url()?>dashboard/updateSampah" method="post">
                        <input
                            type="hidden"
                            name="id_sampah"
                            value="<?=$data_sampah['id'] ?>"
                            >
                <div class="mb-3">
                    <label for="jenis_sampah" class="form-label">Jenis Sampah</label>
                        <input
                            type="text"
                            class="form-control"
                            name="jenis_sampah"
                            placeholder="Masukkan Jenis Sampah"
                            value="<?=$data_sampah['jenis_sampah'] ?>"
                            >
                </div>
                <div class="mb-3">
                    <label for="kategori_sampah" class="form-label">Kategori</label>
                        <input
                            type="text"
                            class="form-control"
                            name="kategori_sampah"
                            placeholder="Masukkan Kategori Sampah"
                            value="<?=$data_sampah['kategori_sampah'] ?>"
                            >
                </div>
                <div class="mb-3">
                    <label for="sub_kategori_sampah" class="form-label">Sub Kategori</label>
                        <input
                            type="text"
                            class="form-control"
                            name="sub_kategori_sampah"
                            placeholder="Masukkan Sub Kategori Sampah"
                            value="<?=$data_sampah['sub_kategori_sampah'] ?>"
                            >
                </div>
                <div class="mb-3">
                    <label for="harga_sampah" class="form-label">Harga</label>
                        <input
                            type="number"
                            class="form-control"
                            name="harga_sampah"
                            placeholder="Masukkan Harga Sampah"
                            value="<?=$data_sampah['harga_sampah'] ?>"
                            >
                </div>
                <button type="submit" class="btn btn-success mb-3">Simpan Perubahan</button>
            </form>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</body>
</html>