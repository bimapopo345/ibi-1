-- Buat tabel notifikasi
CREATE TABLE IF NOT EXISTS notifikasi (
    id INT PRIMARY KEY AUTO_INCREMENT,
    pesanan_id INT,
    judul VARCHAR(100) NOT NULL,
    pesan TEXT NOT NULL,
    tipe ENUM('info', 'success', 'warning', 'error') DEFAULT 'info',
    dibaca BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (pesanan_id) REFERENCES pesanan(id) ON DELETE CASCADE
);

-- Tambah index untuk performa
CREATE INDEX idx_pesanan_id ON notifikasi(pesanan_id);
CREATE INDEX idx_dibaca ON notifikasi(dibaca);
CREATE INDEX idx_created_at ON notifikasi(created_at);
