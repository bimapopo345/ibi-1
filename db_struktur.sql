-- Buat database jika belum ada
CREATE DATABASE IF NOT EXISTS aplikasi_pemesanan;
USE aplikasi_pemesanan;

-- Tabel users
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

-- Tabel produk
CREATE TABLE produk (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama_produk VARCHAR(100) NOT NULL,
    deskripsi TEXT,
    harga DECIMAL(10,2) NOT NULL,
    gambar VARCHAR(255),
    stok INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel pesanan
CREATE TABLE pesanan (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    total_harga DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'menunggu_pembayaran', 'dibayar', 'diproses', 'selesai', 'dibatalkan') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Tabel detail_pesanan
CREATE TABLE detail_pesanan (
    id INT PRIMARY KEY AUTO_INCREMENT,
    pesanan_id INT NOT NULL,
    produk_id INT NOT NULL,
    jumlah INT NOT NULL,
    harga_satuan DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    catatan TEXT,
    FOREIGN KEY (pesanan_id) REFERENCES pesanan(id),
    FOREIGN KEY (produk_id) REFERENCES produk(id)
);

-- Tabel pembayaran
CREATE TABLE pembayaran (
    id INT PRIMARY KEY AUTO_INCREMENT,
    pesanan_id INT NOT NULL,
    jumlah DECIMAL(10,2) NOT NULL,
    metode_pembayaran ENUM('QRIS', 'transfer_bank') NOT NULL,
    bukti_pembayaran VARCHAR(255),
    nomor_rekening VARCHAR(50),
    bank_tujuan VARCHAR(50),
    nama_pengirim VARCHAR(100),
    status ENUM('pending', 'verified', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    verified_at TIMESTAMP NULL,
    FOREIGN KEY (pesanan_id) REFERENCES pesanan(id)
);

-- Insert admin default dengan email
INSERT INTO users (username, password, nama_lengkap, role, email) 
VALUES ('admin', 'admin', 'Administrator', 'admin', 'bimapopo345@gmail.com');

-- Insert beberapa produk contoh
INSERT INTO produk (nama_produk, deskripsi, harga, gambar, stok) VALUES
('Ayam Bakar', 'Ayam bakar bumbu special', 25000, 'ayam bakar.jpg', 50),
('Ayam Geprek', 'Ayam geprek pedas level 1-5', 20000, 'ayam geprek.jpg', 50),
('Lele Goreng', 'Lele goreng renyah', 15000, 'lele goreng.jpg', 50);
