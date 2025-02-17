# Panduan Ngrok Terbaru

## Cara Penggunaan:

1. Pastikan Apache XAMPP sudah running
2. Double click file start_ngrok.bat
3. Tunggu hingga muncul URL ngrok, contoh:
   ```
   Forwarding    https://xxxx-xxx-xxx-xxx.ngrok-free.app -> localhost:80
   ```
4. Akses aplikasi dengan menambahkan /aplikasi_pemesanan di URL:
   ```
   https://xxxx-xxx-xxx-xxx.ngrok-free.app/aplikasi_pemesanan
   ```
   Pastikan:

Ada tanda / di akhir URL
Apache XAMPP running
Aplikasi bisa diakses di localhost/aplikasi_pemesanan

## Penjelasan Konfigurasi:

- Menggunakan parameter `--host-header="localhost"`
- Meneruskan semua request ke port 80
- Memungkinkan akses ke semua folder di htdocs
- Host header diperlukan untuk routing yang benar

## Troubleshooting:

1. Jika muncul error 404:

   - Pastikan path di command ngrok sesuai dengan folder aplikasi
   - Cek Apache XAMPP berjalan
   - Aplikasi bisa diakses di localhost/aplikasi_pemesanan

2. Jika gambar tidak muncul:

   - Periksa path relatif gambar di kode
   - Gunakan ../images/ untuk path gambar

3. Jika database error:
   - Koneksi database tetap ke localhost
   - Cek MySQL XAMPP running

## Tips:

- URL ngrok akan berubah setiap kali direstart
- Bookmark URL selama development
- Gunakan Chrome DevTools untuk debug
- Monitor log ngrok untuk troubleshooting
