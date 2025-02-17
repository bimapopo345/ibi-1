<?php
session_start();
include '../includes/db.php';

// Ambil pesanan terakhir dengan detail produk
$query = "SELECT p.*, u.nama_lengkap, u.no_telp, dp.jumlah, dp.harga_satuan, dp.subtotal, pr.nama_produk, pr.gambar
          FROM pesanan p
          JOIN users u ON p.user_id = u.id
          JOIN detail_pesanan dp ON p.id = dp.pesanan_id
          JOIN produk pr ON dp.produk_id = pr.id
          WHERE p.id = (SELECT MAX(id) FROM pesanan)";
$result = mysqli_query($conn, $query);
$pesanan_details = [];
$first_row = mysqli_fetch_assoc($result);

if ($first_row) {
    // Simpan informasi pesanan
    $pesanan_info = [
        'id' => $first_row['id'],
        'nama_lengkap' => $first_row['nama_lengkap'],
        'no_telp' => $first_row['no_telp'],
        'total_harga' => $first_row['total_harga'],
        'status' => $first_row['status'],
        'created_at' => $first_row['created_at']
    ];

    // Simpan detail produk
    do {
        $pesanan_details[] = [
            'nama_produk' => $first_row['nama_produk'],
            'gambar' => $first_row['gambar'],
            'jumlah' => $first_row['jumlah'],
            'harga_satuan' => $first_row['harga_satuan'],
            'subtotal' => $first_row['subtotal']
        ];
    } while ($first_row = mysqli_fetch_assoc($result));
}

include '../includes/header.php';
?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="p-6">
                <div class="flex items-center justify-center mb-6">
                    <div class="bg-green-100 rounded-full p-3">
                        <i class="fas fa-check-circle text-3xl text-green-500"></i>
                    </div>
                </div>
                
                <h1 class="text-2xl font-bold text-center text-gray-800 mb-6">Pesanan Berhasil!</h1>
                
                <div class="border-t border-b border-gray-200 py-4 mb-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Nomor Pesanan</p>
                            <p class="font-semibold">#<?php echo str_pad($pesanan_info['id'], 5, '0', STR_PAD_LEFT); ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Tanggal</p>
                            <p class="font-semibold"><?php echo date('d F Y H:i', strtotime($pesanan_info['created_at'])); ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Nama Pemesan</p>
                            <p class="font-semibold"><?php echo htmlspecialchars($pesanan_info['nama_lengkap']); ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Nomor Telepon</p>
                            <p class="font-semibold"><?php echo htmlspecialchars($pesanan_info['no_telp']); ?></p>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <?php foreach ($pesanan_details as $detail) { ?>
                        <div class="flex items-center space-x-4">
                            <img src="../images/<?php echo htmlspecialchars($detail['gambar']); ?>" 
                                 alt="<?php echo htmlspecialchars($detail['nama_produk']); ?>"
                                 class="w-16 h-16 object-cover rounded">
                            <div class="flex-1">
                                <h3 class="font-medium"><?php echo htmlspecialchars($detail['nama_produk']); ?></h3>
                                <p class="text-sm text-gray-600">
                                    <?php echo $detail['jumlah']; ?> x Rp <?php echo number_format($detail['harga_satuan'], 0, ',', '.'); ?>
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold">Rp <?php echo number_format($detail['subtotal'], 0, ',', '.'); ?></p>
                            </div>
                        </div>
                    <?php } ?>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-semibold">Total Pembayaran</span>
                        <span class="text-2xl font-bold text-orange-600">
                            Rp <?php echo number_format($pesanan_info['total_harga'], 0, ',', '.'); ?>
                        </span>
                    </div>
                </div>

                <div class="mt-8 flex justify-center space-x-4">
                    <a href="pesanan.php" class="bg-orange-600 hover:bg-orange-700 text-white font-bold py-2 px-6 rounded-lg transition-colors">
                        Lihat Pesanan
                    </a>
                    <a href="index.php" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-6 rounded-lg transition-colors">
                        Kembali ke Menu
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
