# Aplikasi Pemesanan Makanan

Aplikasi pemesanan makanan berbasis web dengan fitur manajemen pesanan, pembayaran online, dan notifikasi realtime. Dibuat menggunakan PHP, MySQL, dan TailwindCSS.

## 📋 Fitur Utama

### Customer

- Registrasi dan login customer
- Melihat menu makanan
- Menambahkan ke keranjang
- Proses checkout dengan catatan pesanan
- Upload bukti pembayaran (QRIS/Transfer Bank)
- Riwayat pesanan
- Notifikasi status pesanan

### Admin

- Login admin dashboard
- Manajemen produk/menu
- Konfirmasi pembayaran
- Update status pesanan
- Notifikasi pesanan baru
- Riwayat transaksi

## 🔧 Teknologi yang Digunakan

- PHP 7.4+
- MySQL 5.7+
- TailwindCSS 3.0
- FontAwesome Icons
- JavaScript
- Composer (PHP Package Manager)

## 💻 Panduan Instalasi

### Persyaratan Sistem

- XAMPP/WAMP/LAMP dengan PHP 7.4 atau lebih tinggi
- MySQL 5.7 atau lebih tinggi
- Web Browser modern (Chrome/Firefox/Safari)
- Composer

### Langkah Instalasi

1. Clone atau download repository ini ke folder htdocs:

```bash
git clone https://github.com/username/aplikasi_pemesanan.git
cd aplikasi_pemesanan
```

2. Install dependencies menggunakan Composer:

```bash
composer install
```

3. Buat database dan import struktur:

   - Buka phpMyAdmin (http://localhost/phpmyadmin)
   - Buat database baru dengan nama 'aplikasi_pemesanan'
   - Import file `database.sql`

4. Konfigurasi koneksi database:
   - Buka file `includes/db.php`
   - Sesuaikan credential database:

```php
$host = 'localhost';
$user = 'root';  // sesuaikan username
$pass = '';      // sesuaikan password
$db   = 'aplikasi_pemesanan';
```

5. Buat folder upload dan atur permission:

```bash
mkdir -p uploads/bukti_pembayaran
chmod 777 uploads/bukti_pembayaran
```

6. Konfigurasi email (opsional untuk notifikasi):
   - Buka file `includes/mail_helper.php`
   - Sesuaikan konfigurasi SMTP:

```php
$mail->Host = 'smtp.gmail.com';
$mail->Username = 'your-email@gmail.com';
$mail->Password = 'your-app-password';
```

7. Akses aplikasi:
   - Customer: http://localhost/aplikasi_pemesanan/
   - Admin: http://localhost/aplikasi_pemesanan/admin/

### Akun Default

#### Admin

- Username: admin
- Password: admin123
- Email: admin@localhost.com

## 📁 Struktur Folder

```
aplikasi_pemesanan/
├── admin/              # Admin dashboard & management
├── customer/           # Customer pages
├── includes/           # Shared components & configs
├── css/               # Stylesheets
├── js/                # JavaScript files
├── images/            # Static images
├── uploads/           # User uploaded files
│   └── bukti_pembayaran/  # Payment proofs
├── database.sql       # Database structure & initial data
├── composer.json      # PHP dependencies
└── README.md         # Documentation
```

## 🖼️ Screenshots

### Customer Panel

![Customer Home](screenshots/customer-home.png)
![Order Process](screenshots/order-process.png)

### Admin Panel

![Admin Dashboard](screenshots/admin-dashboard.png)
![Order Management](screenshots/order-management.png)

## 🔒 Keamanan

- Password di-hash menggunakan bcrypt
- Validasi input untuk mencegah SQL injection
- Sanitasi output untuk mencegah XSS
- CSRF protection pada form
- Pembatasan akses folder sensitif

## 🤝 Kontribusi

Silakan buat Pull Request untuk kontribusi. Untuk perubahan besar, buat Issue terlebih dahulu untuk diskusi.

## 📝 Lisensi

[MIT License](LICENSE)

## ✨ Credit

Dibuat dengan ❤️ oleh [Nama Anda]
