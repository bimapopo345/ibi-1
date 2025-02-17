<?php
session_start();
include '../includes/db.php';
include '../includes/header.php';

// Jika belum login, redirect ke halaman login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Ambil riwayat pesanan user
$user_id = $_SESSION['user_id'];
$query = "SELECT p.*, dp.*, pr.nama_produk, pr.gambar 
          FROM pesanan p 
          JOIN detail_pesanan dp ON p.id = dp.pesanan_id 
          JOIN produk pr ON dp.produk_id = pr.id 
          WHERE p.user_id = $user_id 
          ORDER BY p.created_at DESC";
$result = mysqli_query($conn, $query);
?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Riwayat Pesanan</h1>

    <?php
    $current_pesanan_id = null;
    while ($row = mysqli_fetch_assoc($result)) {
        if ($current_pesanan_id !== $row['pesanan_id']) {
            // Tutup div sebelumnya jika bukan pesanan pertama
            if ($current_pesanan_id !== null) {
                echo '</div></div>';
            }
            $current_pesanan_id = $row['pesanan_id'];
            ?>
            <div class="bg-white rounded-lg shadow-lg mb-6 overflow-hidden">
                <div class="border-b border-gray-200 p-4 bg-gray-50">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-lg font-semibold">Pesanan #<?php echo $row['pesanan_id']; ?></h2>
                            <p class="text-gray-600"><?php echo date('d F Y H:i', strtotime($row['created_at'])); ?></p>
                        </div>
                        <div class="text-right">
                            <span class="inline-block px-3 py-1 rounded-full text-sm
                                <?php
                                switch ($row['status']) {
                                    case 'pending':
                                        echo 'bg-yellow-100 text-yellow-800';
                                        break;
                                    case 'dibayar':
                                        echo 'bg-blue-100 text-blue-800';
                                        break;
                                    case 'diproses':
                                        echo 'bg-purple-100 text-purple-800';
                                        break;
                                    case 'selesai':
                                        echo 'bg-green-100 text-green-800';
                                        break;
                                    case 'dibatalkan':
                                        echo 'bg-red-100 text-red-800';
                                        break;
                                }
                                ?>">
                                <?php echo ucfirst($row['status']); ?>
                            </span>
                            <p class="font-semibold mt-2">Total: Rp <?php echo number_format($row['total_harga'], 0, ',', '.'); ?></p>
                        </div>
                    </div>
                </div>
                <div class="p-4">
        <?php
        }
        ?>
        <div class="flex items-center py-2 border-b border-gray-100 last:border-0">
            <img src="../images/<?php echo htmlspecialchars($row['gambar']); ?>" alt="<?php echo htmlspecialchars($row['nama_produk']); ?>" class="w-16 h-16 object-cover rounded">
            <div class="ml-4 flex-grow">
                <h3 class="font-semibold"><?php echo htmlspecialchars($row['nama_produk']); ?></h3>
                <p class="text-gray-600">
                    <?php echo $row['jumlah']; ?> x Rp <?php echo number_format($row['harga_satuan'], 0, ',', '.'); ?>
                </p>
            </div>
            <div class="text-right">
                <p class="font-semibold">Rp <?php echo number_format($row['subtotal'], 0, ',', '.'); ?></p>
            </div>
        </div>
    <?php
    }
    // Tutup div terakhir
    if ($current_pesanan_id !== null) {
        echo '</div></div>';
    }
    ?>

    <?php if (mysqli_num_rows($result) == 0) { ?>
        <div class="text-center py-8">
            <div class="text-gray-400 mb-4">
                <i class="fas fa-shopping-bag text-6xl"></i>
            </div>
            <p class="text-gray-600 mb-4">Anda belum memiliki riwayat pesanan</p>
            <a href="../customer/index.php" class="inline-block bg-orange-600 hover:bg-orange-700 text-white font-bold py-2 px-6 rounded-full transition-colors">
                Mulai Pesan
            </a>
        </div>
    <?php } ?>
</div>

<?php include '../includes/footer.php'; ?>
