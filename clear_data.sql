-- Matikan foreign key checks sementara
SET FOREIGN_KEY_CHECKS = 0;

-- Hapus semua detail pesanan dulu
TRUNCATE TABLE detail_pesanan;

-- Hapus semua data pembayaran
TRUNCATE TABLE pembayaran;

-- Terakhir hapus pesanan
TRUNCATE TABLE pesanan;

-- Reset auto increment
ALTER TABLE detail_pesanan AUTO_INCREMENT = 1;
ALTER TABLE pembayaran AUTO_INCREMENT = 1;
ALTER TABLE pesanan AUTO_INCREMENT = 1;

-- Aktifkan kembali foreign key checks
SET FOREIGN_KEY_CHECKS = 1;
