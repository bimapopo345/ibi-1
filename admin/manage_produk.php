<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add'])) {
        $nama_produk = $_POST['nama_produk'];
        $harga = $_POST['harga'];
        $deskripsi = $_POST['deskripsi'];
        $gambar = $_FILES['gambar']['name'];
        move_uploaded_file($_FILES['gambar']['tmp_name'], "../images/$gambar");

        $query = "INSERT INTO produk (nama_produk, harga, deskripsi, gambar) VALUES ('$nama_produk', '$harga', '$deskripsi', '$gambar')";
        mysqli_query($conn, $query);
    } elseif (isset($_POST['delete'])) {
        $id = $_POST['id'];
        $query = "DELETE FROM produk WHERE id = $id";
        mysqli_query($conn, $query);
    }
}

$produk = mysqli_query($conn, "SELECT * FROM produk");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Produk - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<!-- Include navbar yang sama dengan dashboard -->
<nav class="bg-white shadow-lg">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="flex-shrink-0 flex items-center">
                    <i class="fas fa-utensils text-2xl text-orange-600"></i>
                    <span class="ml-2 text-xl font-bold">Admin Panel</span>
                </div>
            </div>
            
            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center">
                <a href="manage_produk.php" class="px-3 py-2 rounded-md text-sm font-medium text-orange-600">
                    <i class="fas fa-box-open mr-1"></i> Produk
                </a>
                <a href="notifikasi.php" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-orange-600">
                    <i class="fas fa-bell mr-1"></i> Notifikasi
                </a>
                <a href="konfirmasi_pembayaran.php" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-orange-600">
                    <i class="fas fa-credit-card mr-1"></i> Pembayaran
                </a>
                <a href="logout.php" class="ml-4 px-4 py-2 rounded-md text-sm font-medium text-white bg-orange-600 hover:bg-orange-700">
                    <i class="fas fa-sign-out-alt mr-1"></i> Logout
                </a>
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden flex items-center">
                <button id="mobile-menu-button" class="text-gray-500 hover:text-orange-600">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>
        </div>
    </div>

    <?php include 'mobile_menu.php'; ?>
</nav>

<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Form Tambah Produk -->
    <div class="mb-8 bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Tambah Produk Baru</h2>
        <form method="POST" enctype="multipart/form-data" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Nama Produk</label>
                <input type="text" 
                       name="nama_produk" 
                       required 
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Harga</label>
                <input type="number" 
                       name="harga" 
                       required 
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                <textarea name="deskripsi" 
                          rows="3" 
                          class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500"></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Gambar Produk</label>
                <input type="file" 
                       name="gambar" 
                       required 
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500">
            </div>
            <button type="submit" 
                    name="add" 
                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                <i class="fas fa-plus mr-2"></i>
                Tambah Produk
            </button>
        </form>
    </div>

    <!-- Tabel Produk -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Daftar Produk</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php while ($row = mysqli_fetch_assoc($produk)) { ?>
                        <tr>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <img src="../images/<?php echo htmlspecialchars($row['gambar']); ?>" 
                                         alt="<?php echo htmlspecialchars($row['nama_produk']); ?>"
                                         class="h-10 w-10 rounded-full object-cover">
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            <?php echo htmlspecialchars($row['nama_produk']); ?>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                <?php echo htmlspecialchars($row['deskripsi']); ?>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium">
                                <form method="POST" class="inline">
                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" 
                                            name="delete" 
                                            onclick="return confirm('Yakin ingin menghapus produk ini?');"
                                            class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i>
                                        Hapus
                                    </button>
                                </form>
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

<script>
    // Mobile menu toggle
    document.getElementById('mobile-menu-button').addEventListener('click', function() {
        document.getElementById('mobile-menu').classList.toggle('hidden');
    });
</script>
</body>
</html>
