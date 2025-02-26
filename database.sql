-- Create database
CREATE DATABASE IF NOT EXISTS aplikasi_pemesanan;
USE aplikasi_pemesanan;

-- Create users table
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    role ENUM('admin', 'customer') NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    no_telp VARCHAR(15),
    alamat TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create produk table
CREATE TABLE produk (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(100) NOT NULL,
    harga DECIMAL(10, 2) NOT NULL,
    deskripsi TEXT,
    gambar VARCHAR(255),
    status ENUM('tersedia', 'tidak_tersedia') DEFAULT 'tersedia',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create pesanan table
CREATE TABLE pesanan (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    total_harga DECIMAL(10, 2) NOT NULL,
    status ENUM('pending', 'menunggu_pembayaran', 'dibayar', 'diproses', 'selesai', 'dibatalkan') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Create detail_pesanan table
CREATE TABLE detail_pesanan (
    id INT PRIMARY KEY AUTO_INCREMENT,
    pesanan_id INT NOT NULL,
    produk_id INT NOT NULL,
    jumlah INT NOT NULL,
    harga_satuan DECIMAL(10, 2) NOT NULL,
    subtotal DECIMAL(10, 2) NOT NULL,
    catatan TEXT,
    FOREIGN KEY (pesanan_id) REFERENCES pesanan(id) ON DELETE CASCADE,
    FOREIGN KEY (produk_id) REFERENCES produk(id)
);

-- Create pembayaran table
CREATE TABLE pembayaran (
    id INT PRIMARY KEY AUTO_INCREMENT,
    pesanan_id INT NOT NULL,
    jumlah DECIMAL(10, 2) NOT NULL,
    metode_pembayaran ENUM('QRIS', 'transfer_bank') NOT NULL,
    bukti_pembayaran VARCHAR(255),
    nomor_rekening VARCHAR(50),
    bank_tujuan VARCHAR(50),
    nama_pengirim VARCHAR(100),
    status ENUM('pending', 'verified', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    verified_at TIMESTAMP NULL,
    FOREIGN KEY (pesanan_id) REFERENCES pesanan(id) ON DELETE CASCADE
);

-- Create notifikasi table
CREATE TABLE notifikasi (
    id INT PRIMARY KEY AUTO_INCREMENT,
    pesanan_id INT,
    judul VARCHAR(100) NOT NULL,
    pesan TEXT NOT NULL,
    tipe ENUM('info', 'success', 'warning', 'error') DEFAULT 'info',
    dibaca BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (pesanan_id) REFERENCES pesanan(id) ON DELETE CASCADE
);

-- Create indexes for better performance
CREATE INDEX idx_user_id ON pesanan(user_id);
CREATE INDEX idx_pesanan_id ON detail_pesanan(pesanan_id);
CREATE INDEX idx_produk_id ON detail_pesanan(produk_id);
CREATE INDEX idx_pembayaran_pesanan ON pembayaran(pesanan_id);
CREATE INDEX idx_notifikasi_pesanan ON notifikasi(pesanan_id);
CREATE INDEX idx_notifikasi_dibaca ON notifikasi(dibaca);

-- Insert default admin account
INSERT INTO users (username, password, nama_lengkap, role, email) VALUES 
('admin', '$2y$10$8HhwNQZh.qXGFG3yqZ5bVOMqLq8kqgzH0tR0Ncd0mGEgumxVxjCuO', 'Administrator', 'admin', 'admin@localhost.com');
-- Default password: admin123

-- Insert sample products
INSERT INTO produk (nama, harga, deskripsi, gambar, status) VALUES
('Ayam Bakar', 25000.00, 'Ayam bakar bumbu special dengan sambal', 'ayam bakar.jpg', 'tersedia'),
('Ayam Geprek', 20000.00, 'Ayam geprek dengan sambal level 1-5', 'ayam geprek.jpg', 'tersedia'),
('Lele Goreng', 15000.00, 'Lele goreng renyah dengan sambal terasi', 'lele goreng.jpg', 'tersedia');

-- Create required folders
-- Note: You need to manually create these folders or use PHP script:
-- uploads/bukti_pembayaran/
