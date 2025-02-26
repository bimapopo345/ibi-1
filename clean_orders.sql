-- Hapus pembayaran yang tidak memiliki pesanan terkait
DELETE FROM pembayaran 
WHERE pesanan_id NOT IN (SELECT id FROM pesanan);

-- Hapus detail pesanan yang tidak memiliki pesanan terkait
DELETE FROM detail_pesanan 
WHERE pesanan_id NOT IN (SELECT id FROM pesanan);

-- Hapus detail pesanan yang tidak memiliki produk terkait
DELETE FROM detail_pesanan 
WHERE produk_id NOT IN (SELECT id FROM produk);

-- Update status pesanan yang tidak valid menjadi 'dibatalkan'
UPDATE pesanan 
SET status = 'dibatalkan'
WHERE status NOT IN ('pending', 'menunggu_pembayaran', 'dibayar', 'diproses', 'selesai', 'dibatalkan');

-- Hapus pesanan tanpa detail
DELETE FROM pesanan 
WHERE id NOT IN (SELECT DISTINCT pesanan_id FROM detail_pesanan);

-- Hapus pesanan yang tidak memiliki user terkait
DELETE FROM pesanan 
WHERE user_id NOT IN (SELECT id FROM users);
