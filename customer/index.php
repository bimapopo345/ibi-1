<?php
session_start();
include '../includes/db.php';

// Handle search
$search = isset($_GET['search']) ? $_GET['search'] : '';
$query = "SELECT * FROM produk";
if (!empty($search)) {
    $search = mysqli_real_escape_string($conn, $search);
    $query .= " WHERE nama_produk LIKE '%$search%' OR deskripsi LIKE '%$search%'";
}
$result = mysqli_query($conn, $query);
?>

<?php include '../includes/header.php'; ?>

<div class="container mx-auto px-4 py-8">
    <!-- Search Section -->
    <div class="mb-8">
        <form method="GET" class="max-w-xl mx-auto">
            <div class="flex gap-4">
                <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" 
                       placeholder="Cari menu..." 
                       class="flex-1 px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-orange-500">
                <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-2 rounded-lg transition-colors">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>
    </div>

    <!-- Products Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                <a href="detail_produk.php?id=<?php echo $row['id']; ?>" class="block">
                    <img src="../images/<?php echo htmlspecialchars($row['gambar']); ?>" 
                         alt="<?php echo htmlspecialchars($row['nama_produk']); ?>" 
                         class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h3 class="text-xl font-semibold mb-2"><?php echo htmlspecialchars($row['nama_produk']); ?></h3>
                        <p class="text-gray-600 mb-4 line-clamp-2"><?php echo htmlspecialchars($row['deskripsi']); ?></p>
                        <div class="flex justify-between items-center">
                            <span class="text-orange-600 font-bold text-lg">
                                Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?>
                            </span>
                            <button class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg transition-colors">
                                Pesan
                            </button>
                        </div>
                    </div>
                </a>
            </div>
        <?php } ?>
    </div>

    <?php if (mysqli_num_rows($result) == 0) { ?>
        <div class="text-center py-8">
            <div class="text-gray-400 mb-4">
                <i class="fas fa-search text-6xl"></i>
            </div>
            <p class="text-gray-600">Tidak ada menu yang ditemukan</p>
        </div>
    <?php } ?>
</div>

<?php include '../includes/footer.php'; ?>
