-- Alter tabel pembayaran untuk menambahkan ENUM status yang benar
ALTER TABLE pembayaran 
MODIFY COLUMN status ENUM('pending', 'verified', 'rejected') DEFAULT 'pending';

-- Update status yang NULL atau tidak valid menjadi 'pending'
UPDATE pembayaran SET status = 'pending' WHERE status IS NULL OR status NOT IN ('pending', 'verified', 'rejected');

-- Pastikan kolom verified_at ada
ALTER TABLE pembayaran ADD COLUMN IF NOT EXISTS verified_at TIMESTAMP NULL;
