<?php
session_start();
include '../includes/db.php';

// Redirect jika keranjang kosong
if (!isset($_SESSION['keranjang']) || empty($_SESSION['keranjang'])) {
    header('Location: keranjang.php');
    exit();
}

// Inisialisasi total_harga
$total_harga = 0;

// Hitung total harga dari keranjang
if (isset($_SESSION['keranjang'])) {
    foreach ($_SESSION['keranjang'] as $id => $jumlah) {
        $stmt = $conn->prepare("SELECT harga FROM produk WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $total_harga += $row['harga'] * $jumlah;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = htmlspecialchars(trim($_POST['nama_pemesan'] ?? ''));
    $no_telp = htmlspecialchars(trim($_POST['nomor_whatsapp'] ?? ''));

    // Validasi input
    if (empty($nama) || empty($no_telp)) {
        echo "<script>alert('Nama dan nomor telepon harus diisi!'); window.history.back();</script>";
        exit();
    }

    // Validasi format nomor telepon (minimal 10 digit, maksimal 15 digit)
    if (!preg_match('/^[0-9]{10,15}$/', str_replace(['-', ' ', '+'], '', $no_telp))) {
        echo "<script>alert('Format nomor telepon tidak valid!'); window.history.back();</script>";
        exit();
    }

    // Buat user baru untuk pesanan ini
    $query = "INSERT INTO users (username, password, nama_lengkap, no_telp, role) 
              VALUES ('guest_" . time() . "', 'guest', ?, ?, 'customer')";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $nama, $no_telp);
    $stmt->execute();
    $user_id = $conn->insert_id;

    // Simpan pesanan
    $query = "INSERT INTO pesanan (user_id, total_harga, status) VALUES (?, ?, 'pending')";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("id", $user_id, $total_harga);
    $stmt->execute();
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

        $query = "INSERT INTO detail_pesanan (pesanan_id, produk_id, jumlah, harga_satuan, subtotal) 
                 VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iiiii", $pesanan_id, $produk_id, $jumlah, $harga_satuan, $subtotal);
        $stmt->execute();
    }

    // Hapus keranjang setelah pemesanan
    unset($_SESSION['keranjang']);
    header('Location: konfirmasi.php');
    exit();
}

include '../includes/header.php';
?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold mb-8">Pembayaran</h1>
        
        <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-8">
            <div class="p-6">
                <h2 class="text-xl font-semibold mb-4">Detail Pesanan</h2>
                <div class="space-y-4">
                    <?php
                    if (isset($_SESSION['keranjang'])) {
                        foreach ($_SESSION['keranjang'] as $id => $jumlah) {
                            $stmt = $conn->prepare("SELECT nama_produk, harga FROM produk WHERE id = ?");
                            $stmt->bind_param("i", $id);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $row = $result->fetch_assoc();
                            ?>
                            <div class="flex justify-between items-center border-b pb-4">
                                <div>
                                    <h3 class="font-medium"><?php echo htmlspecialchars($row['nama_produk']); ?></h3>
                                    <p class="text-gray-600"><?php echo $jumlah; ?> x Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></p>
                                </div>
                                <span class="font-semibold">Rp <?php echo number_format($row['harga'] * $jumlah, 0, ',', '.'); ?></span>
                            </div>
                            <?php
                        }
                    }
                    ?>
                    <div class="flex justify-between items-center pt-4">
                        <span class="text-lg font-semibold">Total:</span>
                        <span class="text-2xl font-bold text-orange-600">Rp <?php echo number_format($total_harga, 0, ',', '.'); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <form method="POST" class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="p-6 space-y-6">
                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700">Nama Pemesan</label>
                    <input type="text" 
                           id="nama_pemesan" 
                           name="nama_pemesan" 
                           required 
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                </div>
                <div>
                    <label for="no_telp" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                    <input type="tel" 
                           id="nomor_whatsapp" 
                           name="nomor_whatsapp" 
                           pattern="[0-9]{10,15}"
                           title="Masukkan nomor telepon yang valid (10-15 digit)"
                           placeholder="Contoh: 081234567890"
                           required 
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                    <p class="mt-1 text-sm text-gray-500">Format: 10-15 digit angka tanpa spasi atau tanda strip</p>
                </div>
                <div class="flex space-x-4">
                    <button type="submit" 
                            id="submit-btn"
                            class="flex-1 bg-orange-600 hover:bg-orange-700 text-white font-bold py-3 px-6 rounded-lg transition-colors">
                        Konfirmasi Pembayaran
                    </button>
                    <a href="keranjang.php" 
                       class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-3 px-6 rounded-lg transition-colors">
                        Kembali
                    </a>
                </div>
                
                <script>
                    document.getElementById('submit-btn').addEventListener('click', function(e) {
                        const nama = document.getElementById('nama_pemesan').value.trim();
                        const telp = document.getElementById('nomor_whatsapp').value.trim();
                        
                        if (!nama || !telp) {
                            e.preventDefault();
                            alert('Silakan isi semua field yang diperlukan');
                            return;
                        }
                        
                        if (!/^[0-9]{10,15}$/.test(telp.replace(/[\s-+]/g, ''))) {
                            e.preventDefault();
                            alert('Nomor telepon harus berisi 10-15 digit angka');
                            return;
                        }
                        
                        this.classList.add('opacity-50', 'cursor-not-allowed');
                        this.innerHTML = 'Memproses...';
                        this.form.submit();
                    });
                </script>
            </div>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
