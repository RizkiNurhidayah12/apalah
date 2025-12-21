CREATE DATABASE IF NOT EXISTS frontend_db;
USE frontend_db;


CREATE TABLE menu (
  id_menu INT AUTO_INCREMENT PRIMARY KEY,
  nama_menu VARCHAR(100) NOT NULL,
  deskripsi TEXT,
  harga DECIMAL(10,2) NOT NULL,
  gambar VARCHAR(255)
);

CREATE TABLE produk (
  id_produk INT AUTO_INCREMENT PRIMARY KEY,
  nama_produk VARCHAR(100) NOT NULL,
  kategori VARCHAR(50) NOT NULL,
  harga DECIMAL(10,2) NOT NULL,
  stok INT DEFAULT 0,
  deskripsi TEXT,
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
    ON DELETE CASCADECREATE DATABASE IF NOT EXISTS frontend_db;
USE frontend_db;


CREATE TABLE menu (
  id_menu INT AUTO_INCREMENT PRIMARY KEY,
  kategori VARCHAR(50) NOT NULL,
  nama_menu VARCHAR(100) NOT NULL,
  deskripsi TEXT,
  harga DECIMAL(10,2) NOT NULL,
  gambar VARCHAR(255)
);


INSERT INTO menu (kategori, nama_menu, deskripsi, harga, gambar) VALUES
('Kopi', 'Espresso', 'Kopi murni dengan rasa kuat dan aroma pekat, disajikan dalam takaran kecil.', 15000.00, '../uploads/espresso.jpg');


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
  email VARCHAR(255) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin','customer') DEFAULT 'admin'
);

-- Penambahan kolom id_pelanggan ke tabel users
ALTER TABLE users ADD COLUMN id_pelanggan INT NULL AFTER role;

--Penambahan foreign key constraint
ALTER TABLE users ADD CONSTRAINT fk_users_pelanggan 
FOREIGN KEY (id_pelanggan) REFERENCES pelanggan(id_pelanggan) 
ON DELETE SET NULL;


-- Tambah akun admin contoh
INSERT INTO users (username, email , password, role) VALUES
('admin', 'admin@gmail.com', MD5('admin123'), 'admin'),
('customer', 'customer@gmail.com', MD5('cst123'), 'customer');
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