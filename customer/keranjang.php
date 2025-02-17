<?php
session_start();
include '../includes/db.php'; // Menghubungkan ke database

// Hapus item dari keranjang
if (isset($_GET['action']) && $_GET['action'] == 'hapus' && isset($_GET['id'])) {
    $produk_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    unset($_SESSION['keranjang'][$produk_id]);
    header('Location: keranjang.php'); // Redirect ke halaman keranjang
    exit;
}

// Menambahkan produk ke keranjang
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['produk_id'])) {
    $produk_id = filter_input(INPUT_POST, 'produk_id', FILTER_SANITIZE_NUMBER_INT);
    $jumlah = filter_input(INPUT_POST, 'jumlah', FILTER_SANITIZE_NUMBER_INT);

    // Jika keranjang belum ada, inisialisasi
    if (!isset($_SESSION['keranjang'])) {
        $_SESSION['keranjang'] = [];
    }

    // Tambahkan produk ke keranjang
    if (isset($_SESSION['keranjang'][$produk_id])) {
        $_SESSION['keranjang'][$produk_id] += $jumlah; // Update jumlah jika produk sudah ada
    } else {
        $_SESSION['keranjang'][$produk_id] = $jumlah; // Tambah produk baru
    }

    header('Location: keranjang.php'); // Redirect ke halaman keranjang
    exit;
}

?>

<?php include '../includes/header.php'; ?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Keranjang Belanja</h1>

    <?php if (isset($_SESSION['keranjang']) && !empty($_SESSION['keranjang'])) { ?>
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga Satuan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php
                        $total_belanja = 0;
                        foreach ($_SESSION['keranjang'] as $id => $jumlah) {
                            $stmt = $conn->prepare("SELECT * FROM produk WHERE id = ?");
                            $stmt->bind_param("i", $id);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $row = $result->fetch_assoc();
                            $subtotal = $row['harga'] * $jumlah;
                            $total_belanja += $subtotal;
                        ?>
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <img class="h-16 w-16 object-cover rounded" src="../images/<?php echo htmlspecialchars($row['gambar']); ?>" alt="<?php echo htmlspecialchars($row['nama_produk']); ?>">
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($row['nama_produk']); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <input type="number" 
                                           name="jumlah[<?php echo $id; ?>]" 
                                           value="<?php echo htmlspecialchars($jumlah); ?>" 
                                           min="1"
                                           class="w-20 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?>
                                </td>
                                <td class="px-6 py-4 text-sm font-medium text-orange-600">
                                    Rp <?php echo number_format($subtotal, 0, ',', '.'); ?>
                                </td>
                                <td class="px-6 py-4">
                                    <a href="keranjang.php?action=hapus&id=<?php echo $id; ?>" 
                                       onclick="return confirm('Apakah Anda yakin ingin menghapus item ini?');"
                                       class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <!-- Total dan Checkout -->
            <div class="p-6 bg-gray-50">
                <div class="flex justify-between items-center mb-6">
                    <span class="text-lg font-semibold">Total Belanja:</span>
                    <span class="text-2xl font-bold text-orange-600">Rp <?php echo number_format($total_belanja, 0, ',', '.'); ?></span>
                </div>

                <form action="pembayaran.php" method="POST" class="space-y-4">
                    <div>
                        <input type="text" 
                               name="nama_pemesan" 
                               placeholder="Nama Lengkap" 
                               required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                    </div>
                    <div>
                        <input type="text" 
                               name="nomor_whatsapp" 
                               placeholder="Nomor WhatsApp" 
                               required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                    </div>
                    <div class="flex space-x-4">
                        <button type="submit" 
                                class="flex-1 bg-orange-600 hover:bg-orange-700 text-white font-bold py-3 px-6 rounded-lg transition-colors">
                            <i class="fas fa-shopping-cart mr-2"></i>
                            Checkout
                        </button>
                        <a href="index.php" 
                           class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-3 px-6 rounded-lg transition-colors">
                            Lanjut Belanja
                        </a>
                    </div>
                </form>
            </div>
        </div>
    <?php } else { ?>
        <div class="text-center py-16">
            <div class="text-gray-400 mb-4">
                <i class="fas fa-shopping-cart text-6xl"></i>
            </div>
            <p class="text-gray-600 mb-4">Keranjang Anda masih kosong</p>
            <a href="index.php" class="inline-block bg-orange-600 hover:bg-orange-700 text-white font-bold py-3 px-8 rounded-full transition-colors">
                Mulai Belanja
            </a>
        </div>
    <?php } ?>
</div>

<?php include '../includes/footer.php'; ?>
