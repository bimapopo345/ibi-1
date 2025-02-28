<?php
session_start();
include '../includes/db.php';

require_once '../includes/mail_helper.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

// Ambil jumlah notifikasi yang belum dibaca
$notifikasi_query = mysqli_query($conn, "SELECT COUNT(*) as count FROM notifikasi WHERE dibaca = 0");
$notifikasi_count = mysqli_fetch_assoc($notifikasi_query)['count'];

$pesanan = mysqli_query($conn, "SELECT p.id, p.user_id, p.total_harga, p.status AS pesanan_status, p.created_at, 
                                      pb.status AS pembayaran_status, pb.bukti_pembayaran, pb.verified_at,
                                      u.nama_lengkap, u.no_telp, u.email 
                               FROM pesanan p 
                               JOIN users u ON p.user_id = u.id
                               LEFT JOIN pembayaran pb ON p.id = pb.pesanan_id
                               WHERE p.status IN ('pending', 'menunggu_pembayaran', 'diproses')
                               ORDER BY p.created_at DESC");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && isset($_POST['id'])) {
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
        $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);
        
        if ($action === 'delete') {
            // Hapus data pembayaran
            $stmt = $conn->prepare("DELETE FROM pembayaran WHERE pesanan_id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            
            // Hapus detail pesanan
            $stmt = $conn->prepare("DELETE FROM detail_pesanan WHERE pesanan_id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            
            // Hapus pesanan
            $stmt = $conn->prepare("DELETE FROM pesanan WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();

            header('Location: konfirmasi_pembayaran.php');
            exit;
        } elseif ($action === 'konfirmasi') {
            // Update status pesanan
            $stmt = $conn->prepare("UPDATE pesanan SET status = 'diproses' WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            
            // Update status pembayaran
            $stmt = $conn->prepare("UPDATE pembayaran SET status = 'verified', verified_at = CURRENT_TIMESTAMP WHERE pesanan_id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();

            // Tambah notifikasi
            $stmt = $conn->prepare("INSERT INTO notifikasi (pesanan_id, judul, pesan, tipe) VALUES (?, 'Pesanan Dikonfirmasi', 'Pembayaran telah dikonfirmasi dan pesanan sedang diproses', 'info')");
            $stmt->bind_param("i", $id);
            $stmt->execute();

            header('Location: konfirmasi_pembayaran.php');
            exit;
        } elseif ($action === 'selesai') {
            // Update status pesanan
            $stmt = $conn->prepare("UPDATE pesanan SET status = 'selesai' WHERE id = ?");
            $stmt->bind_param("i", $id);
            
            if ($stmt->execute()) {
                // Ambil data customer untuk email
                $stmt = $conn->prepare("SELECT u.nama_lengkap, u.email FROM pesanan p JOIN users u ON p.user_id = u.id WHERE p.id = ?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $result = $stmt->get_result();
                $customer = $result->fetch_assoc();
                
                // Kirim email notifikasi
                $emailTemplate = getEmailTemplatePesananSelesai($customer['nama_lengkap'], $id);
                kirimEmail($customer['email'], "Pesanan Anda Telah Selesai", $emailTemplate);

                // Tambah notifikasi
                $stmt = $conn->prepare("INSERT INTO notifikasi (pesanan_id, judul, pesan, tipe) VALUES (?, 'Pesanan Selesai', 'Pesanan Anda telah selesai dan siap diambil', 'success')");
                $stmt->bind_param("i", $id);
                $stmt->execute();
            }

            header('Location: konfirmasi_pembayaran.php');
            exit;
        }
    }
}

include 'header_admin.php';
?>

<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <div class="flex items-center">
            <div class="flex-shrink-0 bg-orange-100 rounded-md p-3">
                <i class="fas fa-credit-card text-orange-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <h2 class="text-2xl font-bold text-gray-900">Konfirmasi Pembayaran</h2>
                <p class="text-gray-600">Verifikasi pembayaran pesanan customer</p>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Pesanan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pemesan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bukti</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php while ($row = mysqli_fetch_assoc($pesanan)) { ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900">#<?php echo str_pad($row['id'], 5, '0', STR_PAD_LEFT); ?></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($row['nama_lengkap']); ?></div>
                                <div class="text-sm text-gray-500"><?php echo htmlspecialchars($row['no_telp']); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-orange-600">
                                    Rp <?php echo number_format($row['total_harga'], 0, ',', '.'); ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php 
                                $status_class = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'menunggu_pembayaran' => 'bg-yellow-100 text-yellow-800',
                                    'diproses' => 'bg-blue-100 text-blue-800',
                                    'selesai' => 'bg-green-100 text-green-800',
                                    'dibatalkan' => 'bg-red-100 text-red-800'
                                ];
                                $status_text = [
                                    'pending' => 'Menunggu Konfirmasi',
                                    'menunggu_pembayaran' => 'Menunggu Konfirmasi',
                                    'diproses' => 'Sedang Diproses',
                                    'selesai' => 'Selesai',
                                    'dibatalkan' => 'Dibatalkan'
                                ];
                                ?>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $status_class[$row['pesanan_status']] ?? 'bg-yellow-100 text-yellow-800'; ?>">
                                    <?php echo $status_text[$row['pesanan_status']] ?? 'Menunggu Konfirmasi'; ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <?php if (!empty($row['bukti_pembayaran'])): ?>
                                    <button type="button" 
                                            onclick="showPaymentProof('<?php echo htmlspecialchars($row['bukti_pembayaran']); ?>')"
                                            class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-image"></i> Lihat Bukti
                                    </button>
                                <?php else: ?>
                                    <span class="text-gray-400">
                                        <i class="fas fa-image"></i> Belum ada bukti
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <?php if ($row['pesanan_status'] == 'pending' || $row['pesanan_status'] == 'menunggu_pembayaran'): ?>
                                    <form method="POST" class="inline mr-2">
                                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                        <input type="hidden" name="action" value="delete">
                                        <button type="submit" 
                                                class="text-white bg-red-600 hover:bg-red-700 px-4 py-2 rounded-md transition-colors"
                                                onclick="return confirm('Yakin ingin menghapus pesanan ini? Tindakan ini tidak dapat dibatalkan.')">
                                            <i class="fas fa-trash mr-1"></i>
                                            Hapus
                                        </button>
                                    </form>
                                    <form method="POST" class="inline">
                                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                        <input type="hidden" name="action" value="konfirmasi">
                                        <button type="submit" 
                                                class="text-white bg-green-600 hover:bg-green-700 px-4 py-2 rounded-md transition-colors"
                                                onclick="return confirm('Konfirmasi pembayaran ini?')">
                                            <i class="fas fa-check mr-1"></i>
                                            Konfirmasi
                                        </button>
                                    </form>
                                <?php elseif ($row['pesanan_status'] == 'diproses'): ?>
                                    <form method="POST" class="inline">
                                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                        <input type="hidden" name="action" value="selesai">
                                        <button type="submit" 
                                                class="text-white bg-orange-600 hover:bg-orange-700 px-4 py-2 rounded-md transition-colors"
                                                onclick="return confirm('Tandai pesanan ini sebagai selesai?')">
                                            <i class="fas fa-check-double mr-1"></i>
                                            Selesai
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php } ?>

                        <?php if (mysqli_num_rows($pesanan) == 0) { ?>
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                <i class="fas fa-inbox text-4xl mb-2"></i>
                                <p>Tidak ada pembayaran yang perlu dikonfirmasi</p>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
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

<!-- Modal untuk menampilkan bukti pembayaran -->
<div id="paymentProofModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white p-4 rounded-lg max-w-2xl w-full mx-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">Bukti Pembayaran</h3>
            <button onclick="closePaymentProof()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="relative">
            <img id="paymentProofImage" src="" alt="Bukti Pembayaran" class="w-full h-auto max-h-[70vh] object-contain">
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle
    document.getElementById('mobile-menu-button').addEventListener('click', function() {
        document.getElementById('mobile-menu').classList.toggle('hidden');
    });
});

// Fungsi untuk menampilkan bukti pembayaran
function showPaymentProof(filename) {
    const modal = document.getElementById('paymentProofModal');
    const image = document.getElementById('paymentProofImage');
    image.src = '../uploads/bukti_pembayaran/' + filename;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

// Fungsi untuk menutup modal bukti pembayaran
function closePaymentProof() {
    const modal = document.getElementById('paymentProofModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
</script>

<?php include '../includes/footer.php'; ?>
