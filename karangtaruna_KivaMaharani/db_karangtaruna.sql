-- db_karangtaruna.sql
CREATE DATABASE IF NOT EXISTS db_karangtaruna CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE db_karangtaruna;

-- users (admin & member)
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(150) NOT NULL,
  email VARCHAR(200) NOT NULL UNIQUE,
  phone VARCHAR(30),
  username VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin','member') NOT NULL DEFAULT 'member',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- jabatan
CREATE TABLE IF NOT EXISTS jabatan (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  deskripsi TEXT
);

-- anggota
CREATE TABLE IF NOT EXISTS anggota (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nik VARCHAR(50),
  nama VARCHAR(150) NOT NULL,
  ttl VARCHAR(150),
  jenis_kelamin VARCHAR(10),
  alamat TEXT,
  phone VARCHAR(30),
  jabatan VARCHAR(100),
  added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- kegiatan
CREATE TABLE IF NOT EXISTS kegiatan (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama_kegiatan VARCHAR(200) NOT NULL,
  tanggal_mulai DATE,
  tanggal_selesai DATE,
  tempat VARCHAR(200),
  deskripsi TEXT
);

-- keuangan
CREATE TABLE IF NOT EXISTS keuangan (
  id INT AUTO_INCREMENT PRIMARY KEY,
  tanggal DATETIME DEFAULT CURRENT_TIMESTAMP,
  tipe ENUM('masuk','keluar') NOT NULL,
  keterangan VARCHAR(255),
  jumlah DECIMAL(15,2) NOT NULL,
  created_by INT
);

-- catatan
CREATE TABLE IF NOT EXISTS catatan (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  judul VARCHAR(200),
  isi TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- sample data (2 data per tabel, plus admin user)
INSERT INTO jabatan (nama, deskripsi) VALUES ('Ketua','Ketua Karang Taruna'), ('Sekretaris','Sekretaris');
INSERT INTO anggota (nik,nama,ttl,jenis_kelamin,alamat,phone,jabatan) VALUES
('001','Andi Saputra','Semarang, 2002-05-10','L','Jl. Mawar 1','081234567890','Ketua'),
('002','Siti Aminah','Semarang, 2003-03-12','P','Jl. Melati 2','082345678901','Sekretaris');

INSERT INTO kegiatan (nama_kegiatan,tanggal_mulai,tanggal_selesai,tempat,deskripsi) VALUES
('Bakti Sosial','2025-10-01','2025-10-01','Balai RW','Bakti sosial bersih-bersih'),
('Pelatihan Kewirausahaan','2025-09-15','2025-09-15','Aula Desa','Pelatihan UMKM');

INSERT INTO keuangan (tipe,keterangan,jumlah,created_by) VALUES
('masuk','Iuran anggota',1000000,1),
('keluar','Pembelian alat',250000,1);

-- admin user (username: admin, password: admin123)
INSERT INTO users (full_name,email,phone,username,password,role) VALUES
('Admin Karang Taruna','admin@karang.local','081200000000','admin','$2y$10$0gk8Kq4Gzqz9o2gkXuWqSe3H9Yc7a9qC1Y.8rZHqA6zHqz1wB3FXS','admin');

-- sample member
INSERT INTO users (full_name,email,phone,username,password,role) VALUES
('User Member','member@karang.local','081299999999','member','$2y$10$0gk8Kq4Gzqz9o2gkXuWqSe3H9Yc7a9qC1Y.8rZHqA6zHqz1wB3FXS','member');
