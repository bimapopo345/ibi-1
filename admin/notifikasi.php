<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

// Tandai notifikasi sebagai dibaca jika ada action=read
if (isset($_GET['action']) && $_GET['action'] === 'read') {
    mysqli_query($conn, "UPDATE notifikasi SET dibaca = TRUE WHERE dibaca = FALSE");
    header('Location: notifikasi.php');
    exit;
}

// Ambil jumlah notifikasi yang belum dibaca
$notifikasi_query = mysqli_query($conn, "SELECT COUNT(*) as count FROM notifikasi WHERE dibaca = 0");
$notifikasi_count = mysqli_fetch_assoc($notifikasi_query)['count'];

// Ambil notifikasi
$notifikasi = mysqli_query($conn, "SELECT n.*, p.id as pesanan_id, u.nama_lengkap 
                                  FROM notifikasi n 
                                  JOIN pesanan p ON n.pesanan_id = p.id 
                                  JOIN users u ON p.user_id = u.id 
                                  ORDER BY n.created_at DESC 
                                  LIMIT 50");

include 'header_admin.php';
?>

<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-orange-100 rounded-md p-3">
                    <i class="fas fa-bell text-orange-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h2 class="text-2xl font-bold text-gray-900">Notifikasi</h2>
                    <p class="text-gray-600">Pemberitahuan aktivitas pesanan</p>
                </div>
            </div>
            <?php if ($notifikasi_count > 0): ?>
                <a href="?action=read" 
                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors">
                    <i class="fas fa-check-double mr-2"></i>
                    Tandai Semua Dibaca
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Notifikasi List -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="divide-y divide-gray-200">
            <?php if (mysqli_num_rows($notifikasi) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($notifikasi)): ?>
                    <div class="p-6 <?php echo $row['dibaca'] ? 'bg-white' : 'bg-orange-50'; ?>">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center">
                                    <?php
                                    $icon_class = [
                                        'info' => 'text-blue-500',
                                        'success' => 'text-green-500',
                                        'warning' => 'text-yellow-500',
                                        'error' => 'text-red-500'
                                    ];
                                    $icon = [
                                        'info' => 'fa-info-circle',
                                        'success' => 'fa-check-circle',
                                        'warning' => 'fa-exclamation-circle',
                                        'error' => 'fa-times-circle'
                                    ];
                                    ?>
                                    <i class="fas <?php echo $icon[$row['tipe']]; ?> <?php echo $icon_class[$row['tipe']]; ?> text-xl mr-3"></i>
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-900">
                                            <?php echo htmlspecialchars($row['judul']); ?>
                                        </h3>
                                        <p class="text-sm text-gray-500">
                                            <?php echo htmlspecialchars($row['pesan']); ?>
                                        </p>
                                        <div class="mt-1 text-xs text-gray-400 flex items-center flex-wrap">
                                            <span><?php echo date('d/m/Y H:i', strtotime($row['created_at'])); ?></span>
                                            <span class="mx-2">•</span>
                                            <span>Pesanan #<?php echo str_pad($row['pesanan_id'], 5, '0', STR_PAD_LEFT); ?></span>
                                            <span class="mx-2">•</span>
                                            <span><?php echo htmlspecialchars($row['nama_lengkap']); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php if (!$row['dibaca']): ?>
                                <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                    Baru
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="p-6 text-center text-gray-500">
                    <i class="fas fa-bell-slash text-4xl mb-2"></i>
                    <p>Tidak ada notifikasi</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Link Kembali -->
    <div class="mt-6 text-center">
        <a href="dashboard.php" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-gray-700 bg-gray-100 hover:bg-gray-200">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Dashboard
        </a>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
