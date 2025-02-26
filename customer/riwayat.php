<?php
session_start();
require_once '../includes/db.php';

// Cek login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header('Location: login.php');
    exit;
}

// Ambil riwayat pesanan
$user_id = $_SESSION['user_id'];
$query = "SELECT p.*, pb.status as status_pembayaran, pb.metode_pembayaran 
          FROM pesanan p 
          LEFT JOIN pembayaran pb ON p.id = pb.pesanan_id 
          WHERE p.user_id = ? 
          ORDER BY p.created_at DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$pesanan = $stmt->get_result();

include '../includes/header.php';
?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="p-6">
                <h2 class="text-2xl font-bold mb-6">Riwayat Pesanan</h2>

                <?php if ($pesanan->num_rows > 0): ?>
                    <div class="space-y-6">
                        <?php while ($row = $pesanan->fetch_assoc()): ?>
                            <div class="border rounded-lg overflow-hidden">
                                <div class="bg-gray-50 px-4 py-3 flex justify-between items-center">
                                    <div>
                                        <span class="text-sm text-gray-600">ID Pesanan:</span>
                                        <span class="font-medium ml-2">#<?php echo str_pad($row['id'], 5, '0', STR_PAD_LEFT); ?></span>
                                    </div>
                                    <div>
                                        <span class="text-sm text-gray-600">Tanggal:</span>
                                        <span class="font-medium ml-2">
                                            <?php echo date('d/m/Y H:i', strtotime($row['created_at'])); ?>
                                        </span>
                                    </div>
                                </div>

                                <div class="p-4">
                                    <?php
                                    // Ambil detail pesanan
                                    $detail_query = "SELECT dp.*, p.nama_produk, p.gambar 
                                                   FROM detail_pesanan dp 
                                                   JOIN produk p ON dp.produk_id = p.id 
                                                   WHERE dp.pesanan_id = ?";
                                    $detail_stmt = $conn->prepare($detail_query);
                                    $detail_stmt->bind_param("i", $row['id']);
                                    $detail_stmt->execute();
                                    $detail_result = $detail_stmt->get_result();
                                    ?>

                                    <div class="space-y-4">
                                        <?php while ($detail = $detail_result->fetch_assoc()): ?>
                                            <div class="flex items-center">
                                                <img src="../images/<?php echo htmlspecialchars($detail['gambar']); ?>" 
                                                     alt="<?php echo htmlspecialchars($detail['nama_produk']); ?>"
                                                     class="w-16 h-16 object-cover rounded">
                                                <div class="ml-4 flex-1">
                                                    <h4 class="font-medium"><?php echo htmlspecialchars($detail['nama_produk']); ?></h4>
                                                    <p class="text-sm text-gray-600">
                                                        <?php echo $detail['jumlah']; ?> x Rp <?php echo number_format($detail['harga_satuan'], 0, ',', '.'); ?>
                                                    </p>
                                                    <?php if (!empty($detail['catatan'])): ?>
                                                        <p class="text-sm text-gray-500 mt-1">
                                                            Catatan: <?php echo htmlspecialchars($detail['catatan']); ?>
                                                        </p>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="text-right">
                                                    <span class="font-medium">
                                                        Rp <?php echo number_format($detail['subtotal'], 0, ',', '.'); ?>
                                                    </span>
                                                </div>
                                            </div>
                                        <?php endwhile; ?>
                                    </div>

                                    <div class="mt-4 pt-4 border-t">
                                        <div class="flex justify-between items-center">
                                            <div>
                                                <span class="text-sm text-gray-600">Status Pesanan:</span>
                                                <?php
                                                $status_class = [
                                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                                    'menunggu_pembayaran' => 'bg-blue-100 text-blue-800',
                                                    'dibayar' => 'bg-green-100 text-green-800',
                                                    'diproses' => 'bg-purple-100 text-purple-800',
                                                    'selesai' => 'bg-green-100 text-green-800',
                                                    'dibatalkan' => 'bg-red-100 text-red-800'
                                                ];
                                                $status_text = [
                                                    'pending' => 'Menunggu Konfirmasi',
                                                    'menunggu_pembayaran' => 'Menunggu Pembayaran',
                                                    'dibayar' => 'Sudah Dibayar',
                                                    'diproses' => 'Sedang Diproses',
                                                    'selesai' => 'Selesai',
                                                    'dibatalkan' => 'Dibatalkan'
                                                ];
                                                ?>
                                                <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $status_class[$row['status']]; ?>">
                                                    <?php echo $status_text[$row['status']]; ?>
                                                </span>
                                            </div>
                                            <div class="text-right">
                                                <span class="text-sm text-gray-600">Total Pesanan:</span>
                                                <span class="ml-2 text-lg font-bold text-orange-600">
                                                    Rp <?php echo number_format($row['total_harga'], 0, ',', '.'); ?>
                                                </span>
                                            </div>
                                        </div>

                                        <?php if ($row['status'] === 'menunggu_pembayaran'): ?>
                                            <div class="mt-4">
                                                <a href="pembayaran.php?id=<?php echo $row['id']; ?>" 
                                                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                                                    <i class="fas fa-credit-card mr-2"></i>
                                                    Lakukan Pembayaran
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-8">
                        <div class="text-gray-400 mb-4">
                            <i class="fas fa-receipt text-6xl"></i>
                        </div>
                        <p class="text-gray-600">Belum ada riwayat pesanan</p>
                        <a href="index.php" class="inline-block mt-4 text-orange-600 hover:text-orange-700">
                            Mulai Pesan Sekarang
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
