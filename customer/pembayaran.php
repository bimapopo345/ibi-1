<?php
session_start();
include '../includes/db.php';

// Cek login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Cek keranjang
if (!isset($_SESSION['keranjang']) || empty($_SESSION['keranjang'])) {
    header('Location: keranjang.php');
    exit;
}

// Hitung total harga
$total_harga = 0;
foreach ($_SESSION['keranjang'] as $id => $jumlah) {
    $stmt = $conn->prepare("SELECT harga FROM produk WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $total_harga += $row['harga'] * $jumlah;
}

// Upload direktori
$upload_dir = "../uploads/bukti_pembayaran/";
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $metode_pembayaran = filter_input(INPUT_POST, 'metode_pembayaran', FILTER_SANITIZE_STRING);
    $nama_pengirim = filter_input(INPUT_POST, 'nama_pengirim', FILTER_SANITIZE_STRING);
    $bank_tujuan = isset($_POST['bank_tujuan']) ? filter_input(INPUT_POST, 'bank_tujuan', FILTER_SANITIZE_STRING) : null;
    $nomor_rekening = isset($_POST['nomor_rekening']) ? filter_input(INPUT_POST, 'nomor_rekening', FILTER_SANITIZE_STRING) : null;

    // Validasi input
    if (empty($metode_pembayaran)) {
        $error = "Pilih metode pembayaran";
    } else {
        // Upload bukti pembayaran
        if (isset($_FILES['bukti_pembayaran']) && $_FILES['bukti_pembayaran']['error'] == 0) {
            $file = $_FILES['bukti_pembayaran'];
            $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png'];

            if (!in_array($file_ext, $allowed)) {
                $error = "Format file tidak didukung. Gunakan JPG, JPEG, atau PNG.";
            } elseif ($file['size'] > 5000000) { // 5MB limit
                $error = "Ukuran file terlalu besar. Maksimal 5MB.";
            } else {
                $filename = uniqid() . "." . $file_ext;
                $filepath = $upload_dir . $filename;

                if (move_uploaded_file($file['tmp_name'], $filepath)) {
                    // Buat pesanan
                    $stmt = $conn->prepare("INSERT INTO pesanan (user_id, total_harga, status) VALUES (?, ?, 'menunggu_pembayaran')");
                    $stmt->bind_param("id", $_SESSION['user_id'], $total_harga);
                    
                    if ($stmt->execute()) {
                        $pesanan_id = $conn->insert_id;

                        // Simpan detail pesanan
                        foreach ($_SESSION['keranjang'] as $produk_id => $jumlah) {
                            $stmt = $conn->prepare("SELECT harga FROM produk WHERE id = ?");
                            $stmt->bind_param("i", $produk_id);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $row = $result->fetch_assoc();
                            
                            $harga_satuan = $row['harga'];
                            $subtotal = $harga_satuan * $jumlah;
                            $catatan = isset($_SESSION['catatan'][$produk_id]) ? $_SESSION['catatan'][$produk_id] : null;

                            $stmt = $conn->prepare("INSERT INTO detail_pesanan (pesanan_id, produk_id, jumlah, harga_satuan, subtotal, catatan) VALUES (?, ?, ?, ?, ?, ?)");
                            $stmt->bind_param("iiiiis", $pesanan_id, $produk_id, $jumlah, $harga_satuan, $subtotal, $catatan);
                            $stmt->execute();
                        }

                        // Simpan data pembayaran
                        $stmt = $conn->prepare("INSERT INTO pembayaran (pesanan_id, jumlah, metode_pembayaran, bukti_pembayaran, bank_tujuan, nomor_rekening, nama_pengirim, status) VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')");
                        $stmt->bind_param("idsssss", $pesanan_id, $total_harga, $metode_pembayaran, $filename, $bank_tujuan, $nomor_rekening, $nama_pengirim);
                        
                        if ($stmt->execute()) {
                            // Hapus keranjang
                            unset($_SESSION['keranjang']);
                            unset($_SESSION['catatan']);
                            
                            $success = "Pembayaran berhasil diupload! Silakan tunggu konfirmasi dari admin.";
                            header("refresh:3;url=riwayat.php");
                        } else {
                            $error = "Gagal menyimpan data pembayaran";
                        }
                    } else {
                        $error = "Gagal membuat pesanan";
                    }
                } else {
                    $error = "Gagal mengupload file";
                }
            }
        } else {
            $error = "Bukti pembayaran harus diupload";
        }
    }
}

include '../includes/header.php';
?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="p-6">
                <h2 class="text-2xl font-bold mb-6">Pembayaran</h2>

                <?php if ($error): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        <?php echo $success; ?>
                    </div>
                <?php endif; ?>

                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-2">Total Pembayaran:</h3>
                    <p class="text-3xl font-bold text-orange-600">
                        Rp <?php echo number_format($total_harga, 0, ',', '.'); ?>
                    </p>
                </div>

                <form method="POST" enctype="multipart/form-data" class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Metode Pembayaran</label>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <input type="radio" 
                                       id="QRIS" 
                                       name="metode_pembayaran" 
                                       value="QRIS" 
                                       class="hidden peer" 
                                       required>
                                <label for="QRIS" 
                                       class="block p-4 border rounded-lg text-center cursor-pointer peer-checked:border-orange-500 peer-checked:bg-orange-50">
                                    <i class="fas fa-qrcode text-2xl mb-2"></i>
                                    <span class="block font-medium">QRIS</span>
                                </label>
                            </div>
                            <div>
                                <input type="radio" 
                                       id="transfer_bank" 
                                       name="metode_pembayaran" 
                                       value="transfer_bank" 
                                       class="hidden peer" 
                                       required>
                                <label for="transfer_bank" 
                                       class="block p-4 border rounded-lg text-center cursor-pointer peer-checked:border-orange-500 peer-checked:bg-orange-50">
                                    <i class="fas fa-university text-2xl mb-2"></i>
                                    <span class="block font-medium">Transfer Bank</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Detail Transfer Bank -->
                    <div id="transfer_details" class="hidden space-y-4">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-medium mb-2">Informasi Rekening:</h4>
                            <div class="space-y-2">
                                <p><span class="text-gray-600">Bank:</span> BCA</p>
                                <p><span class="text-gray-600">No. Rekening:</span> 1234567890</p>
                                <p><span class="text-gray-600">Atas Nama:</span> Warung Makan</p>
                            </div>
                        </div>

                        <div>
                            <label for="bank_tujuan" class="block text-sm font-medium text-gray-700">Bank Pengirim</label>
                            <select name="bank_tujuan" 
                                    id="bank_tujuan" 
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                                <option value="">Pilih Bank</option>
                                <option value="BCA">BCA</option>
                                <option value="Mandiri">Mandiri</option>
                                <option value="BNI">BNI</option>
                                <option value="BRI">BRI</option>
                            </select>
                        </div>

                        <div>
                            <label for="nomor_rekening" class="block text-sm font-medium text-gray-700">Nomor Rekening Pengirim</label>
                            <input type="text" 
                                   id="nomor_rekening" 
                                   name="nomor_rekening" 
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                        </div>
                    </div>

                    <!-- QRIS Details -->
                    <div id="qris_details" class="hidden">
                        <div class="bg-gray-50 p-4 rounded-lg text-center">
                            <img src="../images/qris-example.png" alt="QRIS Code" class="mx-auto max-w-xs">
                            <p class="mt-2 text-sm text-gray-600">Scan QRIS code di atas menggunakan aplikasi e-wallet Anda</p>
                        </div>
                    </div>

                    <div>
                        <label for="nama_pengirim" class="block text-sm font-medium text-gray-700">Nama Pengirim</label>
                        <input type="text" 
                               id="nama_pengirim" 
                               name="nama_pengirim" 
                               required 
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Upload Bukti Pembayaran</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg">
                            <div class="space-y-1 text-center">
                                <i class="fas fa-upload text-gray-400 text-3xl mb-2"></i>
                                <div class="flex text-sm text-gray-600">
                                    <label for="bukti_pembayaran" class="relative cursor-pointer bg-white rounded-md font-medium text-orange-600 hover:text-orange-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-orange-500">
                                        <span>Upload file</span>
                                        <input id="bukti_pembayaran" 
                                               name="bukti_pembayaran" 
                                               type="file" 
                                               accept="image/*" 
                                               class="sr-only" 
                                               required>
                                    </label>
                                    <p class="pl-1">atau drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">
                                    PNG, JPG, JPEG up to 5MB
                                </p>
                            </div>
                        </div>
                        <!-- Preview Image -->
                        <div id="preview" class="mt-2 hidden">
                            <img id="preview_image" src="#" alt="Preview" class="max-h-48 rounded">
                        </div>
                    </div>

                    <div class="flex space-x-4">
                        <button type="submit" 
                                class="flex-1 bg-orange-600 text-white py-2 px-4 rounded-lg hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2">
                            Konfirmasi Pembayaran
                        </button>
                        <a href="keranjang.php" 
                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-gray-700 bg-gray-100 hover:bg-gray-200">
                            Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Toggle payment method details
document.querySelectorAll('input[name="metode_pembayaran"]').forEach(radio => {
    radio.addEventListener('change', function() {
        document.getElementById('transfer_details').classList.toggle('hidden', this.value !== 'transfer_bank');
        document.getElementById('qris_details').classList.toggle('hidden', this.value !== 'QRIS');
        
        // Reset required attributes based on payment method
        const bankFields = ['bank_tujuan', 'nomor_rekening'];
        bankFields.forEach(field => {
            document.getElementById(field).required = (this.value === 'transfer_bank');
        });
    });
});

// Image preview
document.getElementById('bukti_pembayaran').addEventListener('change', function(e) {
    const preview = document.getElementById('preview');
    const previewImg = document.getElementById('preview_image');
    
    if (this.files && this.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.classList.remove('hidden');
        }
        
        reader.readAsDataURL(this.files[0]);
    }
});
</script>

<?php include '../includes/footer.php'; ?>
