<?php
session_start();
include '../includes/db.php';

// Mengambil ID produk dari URL
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

// Mengambil data produk dari database
$produk = mysqli_query($conn, "SELECT * FROM produk WHERE id = $id");
$row = mysqli_fetch_assoc($produk);

// Jika produk tidak ditemukan, redirect ke index.php
if (!$row) {
    header('Location: index.php');
    exit;
}
?>

<?php include '../includes/header.php'; ?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="md:flex">
            <!-- Product Image -->
            <div class="md:w-1/2">
                <img src="../images/<?php echo htmlspecialchars($row['gambar']); ?>" 
                     alt="<?php echo htmlspecialchars($row['nama_produk']); ?>"
                     class="w-full h-96 object-cover">
            </div>
            
            <!-- Product Details -->
            <div class="md:w-1/2 p-6">
                <h1 class="text-3xl font-bold mb-4"><?php echo htmlspecialchars($row['nama_produk']); ?></h1>
                
                <div class="text-2xl font-bold text-orange-600 mb-4">
                    Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?>
                </div>
                
                <div class="mb-6">
                    <h3 class="text-gray-700 text-lg font-semibold mb-2">Deskripsi:</h3>
                    <p class="text-gray-600"><?php echo htmlspecialchars($row['deskripsi']); ?></p>
                </div>
                
                <form method="POST" action="keranjang.php" class="space-y-4">
                    <input type="hidden" name="produk_id" value="<?php echo $row['id']; ?>">
                    
                    <div class="flex items-center space-x-4">
                        <label for="jumlah" class="text-gray-700">Jumlah:</label>
                        <input type="number" 
                               name="jumlah" 
                               id="jumlah" 
                               min="1" 
                               value="1" 
                               required 
                               class="w-20 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                    </div>
                    
                    <div class="flex space-x-4">
                        <button type="submit" 
                                class="flex-1 bg-orange-600 hover:bg-orange-700 text-white font-bold py-3 px-6 rounded-lg transition-colors">
                            <i class="fas fa-shopping-cart mr-2"></i>
                            Masukkan ke Keranjang
                        </button>
                        
                        <a href="index.php" 
                           class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-3 px-6 rounded-lg transition-colors">
                            Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
