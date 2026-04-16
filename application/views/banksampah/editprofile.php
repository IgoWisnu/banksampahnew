<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - Banksampah</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script>
    tailwind.config = {
        theme: {
        extend: {
            fontFamily: { sans: ['Inter', 'sans-serif'] },
            colors: { brand: { green: '#00926E', dark: '#006c50', yellow: '#f59e0b', light: '#fef3c7' } }
        }
        }
    }
    </script>
</head>
<body class="bg-gray-100 font-sans antialiased text-gray-800">

    <div class="w-full mx-auto bg-gray-50 min-h-screen relative shadow-none overflow-x-hidden pb-24 md:pb-32">
        <!-- Header Green Block -->
        <div class="absolute top-0 left-0 right-0 h-[220px] md:h-[300px] bg-brand-green shadow-md z-0 overflow-hidden">
            <img src="<?= base_url() ?>img/trash.jpeg"
                class="hidden md:block absolute right-0 top-0 w-2/3 h-full object-cover mix-blend-overlay opacity-20"
                alt="Background Graphic">
            <div class="hidden md:block absolute inset-0 bg-gradient-to-r from-brand-dark/60 via-brand-green/80 to-transparent"></div>
        </div>

        <div class="relative z-10 pt-8 px-6 md:px-12 flex flex-col md:flex-row justify-center mt-4">
            <div class="w-full max-w-4xl md:w-8/12 lg:w-7/12 mt-8 z-20">
                
                <div class="bg-white/95 backdrop-blur-sm rounded-[2rem] shadow-2xl p-6 md:p-8 border border-gray-100 relative">
                    <a href="<?=base_url()?>profile" class="inline-flex items-center text-sm font-semibold text-gray-500 hover:text-brand-green transition-colors mb-6">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Kembali
                    </a>

                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Edit Profile</h2>

                    <?php echo form_open_multipart('profile/updateProfile');?>
                    <?php foreach($user->result_array() as $key){ ?>

                    <!-- Profile Image Upload Section -->
                    <div class="flex flex-col items-center mb-8">
                        <div class="relative mb-4 group cursor-pointer inline-block">
                            <img id="blah" src="<?=base_url()?>uploads/profile/<?=$key['profile']?>" alt="" class="w-32 h-32 rounded-2xl object-cover border-4 border-white shadow-lg bg-gray-100">
                            <!-- Input overlay -->
                            <div class="absolute inset-0 bg-black/40 rounded-2xl flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            <input type="file" name="userfile" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="readURL(this);">
                            <input type="hidden" name="oldimg" value="<?=$key['profile']?>">
                        </div>
                        <p class="text-xs text-gray-500 font-medium">Klik gambar untuk mengubah (Max 5MB)</p>
                    </div>

                    <!-- Hidden input for user id -->
                    <input type="hidden" name="id_user" value="<?=$key['id_user']?>">

                    <!-- Form Fields -->
                    <div class="space-y-5">
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Username</label>
                            <input type="text" name="username" value="<?=$key['username']?>" disabled class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-100 text-gray-500 cursor-not-allowed focus:outline-none transition-all duration-200">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                            <input type="text" name="email" value="<?=$key['email']?>" disabled class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-100 text-gray-500 cursor-not-allowed focus:outline-none transition-all duration-200">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" value="<?=$key['nama_lengkap']?>" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-green focus:border-transparent transition-all duration-200">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Tempat Lahir</label>
                                <input type="text" name="tempat_lahir" value="<?=$key['tempat_lahir']?>" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-green focus:border-transparent transition-all duration-200">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" value="<?=$key['tanggal_lahir']?>" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-green focus:border-transparent transition-all duration-200">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat</label>
                            <textarea name="alamat" rows="3" class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-green focus:border-transparent transition-all duration-200 resize-none"><?=$key['alamat']?></textarea>
                        </div>
                    </div>

                    <div class="mt-8">
                        <button type="submit" class="w-full bg-brand-green hover:bg-brand-dark text-white font-bold py-3 px-4 rounded-xl shadow-lg shadow-brand-green/30 transition-colors active:scale-95 duration-200">
                            Simpan Perubahan
                        </button>
                    </div>

                    <?php } ?>
                    <?php echo form_close(); ?>
                </div>

            </div>
        </div>
        
    </div>

    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    document.getElementById('blah').setAttribute('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html>