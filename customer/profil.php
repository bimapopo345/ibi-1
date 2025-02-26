<?php
session_start();
require_once '../includes/db.php';

// Cek login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$success = '';
$error = '';

// Ambil data user
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Proses update profil
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_lengkap = filter_input(INPUT_POST, 'nama_lengkap', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $no_telp = filter_input(INPUT_POST, 'no_telp', FILTER_SANITIZE_STRING);
    $alamat = filter_input(INPUT_POST, 'alamat', FILTER_SANITIZE_STRING);
    $password_lama = $_POST['password_lama'] ?? '';
    $password_baru = $_POST['password_baru'] ?? '';
    $konfirmasi_password = $_POST['konfirmasi_password'] ?? '';

    // Validasi input
    if (empty($nama_lengkap) || empty($email)) {
        $error = "Nama lengkap dan email harus diisi";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid";
    } else {
        // Cek email sudah digunakan atau tidak (kecuali email sendiri)
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->bind_param("si", $email, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $error = "Email sudah digunakan";
        } else {
            // Jika ada password lama, berarti user ingin ganti password
            if (!empty($password_lama)) {
                if (!password_verify($password_lama, $user['password'])) {
                    $error = "Password lama tidak sesuai";
                } elseif (empty($password_baru) || empty($konfirmasi_password)) {
                    $error = "Password baru dan konfirmasi password harus diisi";
                } elseif ($password_baru !== $konfirmasi_password) {
                    $error = "Password baru dan konfirmasi tidak cocok";
                } else {
                    // Update dengan password baru
                    $hashed_password = password_hash($password_baru, PASSWORD_DEFAULT);
                    $stmt = $conn->prepare("UPDATE users SET nama_lengkap = ?, email = ?, no_telp = ?, alamat = ?, password = ? WHERE id = ?");
                    $stmt->bind_param("sssssi", $nama_lengkap, $email, $no_telp, $alamat, $hashed_password, $user_id);
                }
            } else {
                // Update tanpa password
                $stmt = $conn->prepare("UPDATE users SET nama_lengkap = ?, email = ?, no_telp = ?, alamat = ? WHERE id = ?");
                $stmt->bind_param("ssssi", $nama_lengkap, $email, $no_telp, $alamat, $user_id);
            }
            
            if ($stmt->execute()) {
                $_SESSION['nama_lengkap'] = $nama_lengkap;
                $success = "Profil berhasil diperbarui";
                
                // Refresh data user
                $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $user = $result->fetch_assoc();
            } else {
                $error = "Gagal memperbarui profil";
            }
        }
    }
}

include '../includes/header.php';
?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="p-6">
                <h2 class="text-2xl font-bold mb-6">Profil Saya</h2>

                <?php if ($success): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        <?php echo $success; ?>
                    </div>
                <?php endif; ?>

                <?php if ($error): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" class="space-y-6">
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                        <input type="text" 
                               id="username" 
                               value="<?php echo htmlspecialchars($user['username']); ?>" 
                               disabled
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50">
                    </div>

                    <div>
                        <label for="nama_lengkap" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                        <input type="text" 
                               id="nama_lengkap" 
                               name="nama_lengkap" 
                               value="<?php echo htmlspecialchars($user['nama_lengkap']); ?>" 
                               required
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="<?php echo htmlspecialchars($user['email']); ?>" 
                               required
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                    </div>

                    <div>
                        <label for="no_telp" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                        <input type="tel" 
                               id="no_telp" 
                               name="no_telp" 
                               value="<?php echo htmlspecialchars($user['no_telp']); ?>" 
                               pattern="[0-9]{10,15}"
                               title="Masukkan nomor telepon yang valid (10-15 digit)"
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                    </div>

                    <div>
                        <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
                        <textarea id="alamat" 
                                  name="alamat" 
                                  rows="3" 
                                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500"><?php echo htmlspecialchars($user['alamat']); ?></textarea>
                    </div>

                    <div class="border-t pt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Ganti Password</h3>
                        <div class="space-y-4">
                            <div>
                                <label for="password_lama" class="block text-sm font-medium text-gray-700">Password Lama</label>
                                <input type="password" 
                                       id="password_lama" 
                                       name="password_lama" 
                                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                            </div>

                            <div>
                                <label for="password_baru" class="block text-sm font-medium text-gray-700">Password Baru</label>
                                <input type="password" 
                                       id="password_baru" 
                                       name="password_baru" 
                                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                            </div>

                            <div>
                                <label for="konfirmasi_password" class="block text-sm font-medium text-gray-700">Konfirmasi Password Baru</label>
                                <input type="password" 
                                       id="konfirmasi_password" 
                                       name="konfirmasi_password" 
                                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                            </div>
                        </div>
                    </div>

                    <div class="flex space-x-4">
                        <button type="submit" 
                                class="flex-1 bg-orange-600 text-white py-2 px-4 rounded-lg hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2">
                            Simpan Perubahan
                        </button>
                        <a href="index.php" 
                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-gray-700 bg-gray-100 hover:bg-gray-200">
                            Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
