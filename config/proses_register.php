<?php
session_start();

// Koneksi ke database
require_once '../config/koneksi.php';

// Ambil data dari form
$email = $_POST['email'];
$password = md5($_POST['password']);
$nama = $_POST['nama'];
$no_hp = $_POST['no_hp'];
$alamat = $_POST['alamat'];

// Cek apakah email sudah ada
$sql_check = "SELECT * FROM users WHERE username = ?";
$stmt_check = $koneksi->prepare($sql_check);
$stmt_check->bind_param("s", $email);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows > 0) {
    echo "<script>alert('Email sudah terdaftar!'); window.location='register.php';</script>";
    exit;
}

try {
    // Langkah 1: Insert ke tabel pelanggan
    $sql_pelanggan = "INSERT INTO pelanggan (nama, no_hp, alamat) VALUES (?, ?, ?)";
    $stmt_pelanggan = $koneksi->prepare($sql_pelanggan);
    $stmt_pelanggan->bind_param("sss", $nama, $no_hp, $alamat);
    
    if (!$stmt_pelanggan->execute()) {
        throw new Exception("Gagal menyimpan data pelanggan");
    }
    
    // Ambil ID pelanggan yang baru dibuat
    $id_pelanggan = $koneksi->insert_id;
    
    // Langkah 2: Insert ke tabel users
    $username = $email;
    $role = 'user';
    
    $sql_users = "INSERT INTO users (username, email, password, role, id_pelanggan) VALUES (?, ?, ?, ?, ?)";
    $stmt_users = $koneksi->prepare($sql_users);
    $stmt_users->bind_param("ssssi", $username, $email, $password, $role, $id_pelanggan);
    
    if (!$stmt_users->execute()) {
        throw new Exception("Gagal membuat akun user");
    }
    
    // Simpan data ke session
    $_SESSION['id_user'] = $koneksi->insert_id; // ID user yang baru dibuat
    $_SESSION['username'] = $username;
    $_SESSION['role'] = $role;
    $_SESSION['id_pelanggan'] = $id_pelanggan; // Penting! Ini yang dibutuhkan
    
    // Redirect ke dashboard
    echo "<script>alert('Registrasi berhasil!'); window.location='../views/login.php';</script>";
    
} catch (Exception $e) {
    echo "<script>alert('Registrasi gagal: " . addslashes($e->getMessage()) . "'); window.location='register.php';</script>";
}
?>
