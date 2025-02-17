# Panduan Setup Ngrok untuk XAMPP

## 1. Persiapan

### Download dan Install Ngrok

1. Kunjungi https://ngrok.com/download
2. Download versi sesuai sistem operasi
3. Extract file ngrok ke folder yang mudah diakses (misal: C:\ngrok)

### Daftar dan Dapatkan Auth Token

1. Buat akun di ngrok.com
2. Setelah login, dapatkan auth token di dashboard
3. Buka command prompt, arahkan ke folder ngrok
4. Jalankan perintah:

```bash
ngrok config add-authtoken YOUR_AUTH_TOKEN
```

## 2. Konfigurasi XAMPP

1. Start Apache di XAMPP Control Panel
2. Pastikan aplikasi bisa diakses di http://localhost/aplikasi_pemesanan
3. Default port Apache XAMPP adalah 80

## 3. Menjalankan Ngrok

1. Buka Command Prompt sebagai Administrator
2. Arahkan ke folder ngrok (cd C:\ngrok)
3. Jalankan perintah:

```bash
ngrok http 80
```

4. Ngrok akan memberikan URL publik (contoh: https://abcd1234.ngrok.io)
5. Akses aplikasi dengan menambahkan path:
   https://abcd1234.ngrok.io/aplikasi_pemesanan

## 4. Hal yang Perlu Diperhatikan

### Database

- Pastikan koneksi database di includes/db.php tetap menggunakan localhost
- Port MySQL default 3306 tidak perlu di-expose

### File Upload

- Folder images/ harus memiliki permission write
- Gunakan path relatif untuk gambar (../images/)

### Keamanan

- URL ngrok bersifat public dan temporary
- Jangan share URL ke publik
- Gunakan hanya untuk testing/development
- Matikan ngrok jika sudah selesai (CTRL+C)

## 5. Troubleshooting

1. Jika gambar tidak muncul:

   - Periksa path relatif di kode
   - Pastikan folder images memiliki permission yang benar

2. Jika database error:

   - Periksa konfigurasi database di includes/db.php
   - Pastikan MySQL XAMPP running

3. Jika ngrok error:
   - Pastikan port 80 tidak digunakan aplikasi lain
   - Cek auth token sudah benar
   - Restart ngrok

## 6. Tips Tambahan

- Bookmark URL ngrok selama session development
- Gunakan fitur inspect di browser untuk debug
- Monitor log ngrok untuk melihat request
- Matikan semua aplikasi yang menggunakan port 80
