<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Berita</title>
    <!-- Tambahkan link CSS Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1>Edit Berita</h1>
        <form id="editForm" action="<?php echo base_url('dashboard/updateBerita') ?>" method="post" enctype="multipart/form-data">  
            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
            <input type="hidden" name="id" value="<?php echo $artikel['id']; ?>">
            <input type="hidden" name="gambarBerita_existing" value="<?php echo $artikel['gambar']; ?>">

            <div class="form-group">
                <label for="judulBerita">Judul Berita:</label>
                <input type="text" class="form-control" id="judulBerita" name="judulBerita" value="<?php echo $artikel['judul']; ?>">
                <?php echo form_error('judulBerita', '<div class="text-danger">', '</div>'); ?>
            </div>

            <div class="form-group">
                <label for="gambarBerita">Upload Gambar Berita:</label>
                <input type="file" class="form-control-file" id="gambarBerita" name="gambarBerita" accept="image/*">
                <img src="<?= base_url('uploads/' . $artikel['gambar']); ?>" alt="Current Image" style="max-width: 200px;">
                <?php echo form_error('gambarBerita', '<div class="text-danger">', '</div>'); ?>
            </div>

            <div class="form-group">
                <label for="deskripsiBerita">Deskripsi Berita:</label>
                <div id="editor"></div>
                <input type="hidden" id="deskripsiBerita" name="deskripsiBerita" value="<?php echo htmlspecialchars($artikel['deskripsi']); ?>">
                <?php echo form_error('deskripsiBerita', '<div class="text-danger">', '</div>'); ?>
            </div>

            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </form>
    </div>

    <!-- Tambahkan link CSS QuillJS -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

    <!-- Tambahkan script QuillJS -->
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

    <script>
        var quill = new Quill('#editor', {
            theme: 'snow'
        });

        // Set nilai awal menggunakan clipboard.dangerouslyPasteHTML
        var clipboard = quill.getModule('clipboard');
        quill.root.innerHTML = '<?php echo addslashes($artikel['deskripsi']); ?>';


        // Dengarkan pengiriman formulir dan perbarui input tersembunyi
        document.getElementById('editForm').addEventListener('submit', function () {
            document.getElementById('deskripsiBerita').value = quill.root.innerHTML;
        });
    </script>
</body>
</html>
