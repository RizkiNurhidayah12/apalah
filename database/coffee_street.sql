CREATE DATABASE IF NOT EXISTS frontend_db;
USE frontend_db;


CREATE TABLE menu (
  id_menu INT AUTO_INCREMENT PRIMARY KEY,
  nama_menu VARCHAR(100) NOT NULL,
  deskripsi TEXT,
  harga DECIMAL(10,2) NOT NULL,
  gambar VARCHAR(255)
);


CREATE TABLE pelanggan (
  id_pelanggan INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  no_hp VARCHAR(20) NOT NULL,
  alamat TEXT
);


CREATE TABLE pesanan (
  id_pesanan INT AUTO_INCREMENT PRIMARY KEY,
  id_pelanggan INT NOT NULL,
  tanggal_pesanan DATETIME DEFAULT CURRENT_TIMESTAMP,
  status ENUM('pending','proses','selesai') DEFAULT 'pending',
  FOREIGN KEY (id_pelanggan) REFERENCES pelanggan(id_pelanggan)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);


CREATE TABLE detail_pesanan (
  id_detail INT AUTO_INCREMENT PRIMARY KEY,
  id_pesanan INT NOT NULL,
  id_menu INT NOT NULL,
  jumlah INT NOT NULL,
  subtotal DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (id_pesanan) REFERENCES pesanan(id_pesanan)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  FOREIGN KEY (id_menu) REFERENCES menu(id_menu)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

CREATE TABLE users (
  id_user INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin','customer') DEFAULT 'admin'
);

-- Tambah akun admin contoh
INSERT INTO users (username, password, role) VALUES
('admin', MD5('admin123'), 'admin'),
('customer', MD5('cst123'), 'customer');