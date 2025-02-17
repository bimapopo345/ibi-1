<?php
include 'includes/db.php';
$produk_favorit = mysqli_query($conn, "SELECT * FROM produk LIMIT 3");
?>

<?php include 'includes/header.php'; ?>

<!-- Hero Section -->
<div class="relative bg-gray-900 h-[500px]">
    <img src="images/bg.jpg" alt="Background" class="w-full h-full object-cover opacity-50">
    <div class="absolute inset-0 flex flex-col justify-center items-center text-center text-white p-4">
        <h1 class="text-4xl md:text-6xl font-bold mb-4">Warung Makan Lezat</h1>
        <p class="text-xl md:text-2xl mb-8">Nikmati kelezatan masakan tradisional dengan cita rasa modern</p>
        <a href="customer/index.php" class="bg-orange-600 hover:bg-orange-700 text-white font-bold py-3 px-8 rounded-full transition-colors">
            Pesan Sekarang
        </a>
    </div>
</div>

<!-- Fitur Section -->
<div class="container mx-auto px-4 py-16">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="text-center">
            <div class="bg-orange-100 w-16 h-16 mx-auto rounded-full flex items-center justify-center mb-4">
                <i class="fas fa-utensils text-2xl text-orange-600"></i>
            </div>
            <h3 class="text-xl font-semibold mb-2">Menu Lezat</h3>
            <p class="text-gray-600">Berbagai pilihan menu makanan lezat dengan bumbu pilihan</p>
        </div>
        <div class="text-center">
            <div class="bg-orange-100 w-16 h-16 mx-auto rounded-full flex items-center justify-center mb-4">
                <i class="fas fa-motorcycle text-2xl text-orange-600"></i>
            </div>
            <h3 class="text-xl font-semibold mb-2">Pengantaran Cepat</h3>
            <p class="text-gray-600">Layanan pengantaran cepat ke lokasi Anda</p>
        </div>
        <div class="text-center">
            <div class="bg-orange-100 w-16 h-16 mx-auto rounded-full flex items-center justify-center mb-4">
                <i class="fas fa-star text-2xl text-orange-600"></i>
            </div>
            <h3 class="text-xl font-semibold mb-2">Kualitas Terjamin</h3>
            <p class="text-gray-600">Bahan-bahan berkualitas dan higienis</p>
        </div>
    </div>
</div>

<!-- Menu Favorit Section -->
<div class="bg-gray-100 py-16">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-12">Menu Favorit</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php while ($produk = mysqli_fetch_assoc($produk_favorit)) { ?>
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <img src="images/<?php echo htmlspecialchars($produk['gambar']); ?>" alt="<?php echo htmlspecialchars($produk['nama_produk']); ?>" class="w-full h-48 object-cover">
                <div class="p-6">
                    <h3 class="text-xl font-semibold mb-2"><?php echo htmlspecialchars($produk['nama_produk']); ?></h3>
                    <p class="text-gray-600 mb-4"><?php echo htmlspecialchars($produk['deskripsi']); ?></p>
                    <div class="flex justify-between items-center">
                        <span class="text-orange-600 font-bold">Rp <?php echo number_format($produk['harga'], 0, ',', '.'); ?></span>
                        <a href="customer/detail_produk.php?id=<?php echo $produk['id']; ?>" class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded">Pesan</a>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
        <div class="text-center mt-8">
            <a href="customer/index.php" class="inline-block bg-orange-600 hover:bg-orange-700 text-white font-bold py-3 px-8 rounded-full transition-colors">
                Lihat Semua Menu
            </a>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
