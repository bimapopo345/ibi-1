-- Tambah kolom catatan di detail_pesanan
ALTER TABLE detail_pesanan ADD COLUMN IF NOT EXISTS catatan TEXT AFTER subtotal;

-- Update ENUM status pesanan
ALTER TABLE pesanan MODIFY COLUMN status ENUM('pending', 'menunggu_pembayaran', 'dibayar', 'diproses', 'selesai', 'dibatalkan') DEFAULT 'pending';

-- Update ENUM metode pembayaran
ALTER TABLE pembayaran MODIFY COLUMN metode_pembayaran ENUM('QRIS', 'transfer_bank') NOT NULL;

-- Tambah kolom untuk informasi pembayaran
ALTER TABLE pembayaran 
ADD COLUMN IF NOT EXISTS nomor_rekening VARCHAR(50) AFTER bukti_pembayaran,
ADD COLUMN IF NOT EXISTS bank_tujuan VARCHAR(50) AFTER nomor_rekening,
ADD COLUMN IF NOT EXISTS nama_pengirim VARCHAR(100) AFTER bank_tujuan;
