<?php
session_start();

// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "frontend_db");

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data dari form
$email = $_POST['email'];
$password = md5($_POST['password']); // Hash MD5

// Cek apakah email sudah ada
$sql_check = "SELECT * FROM users WHERE email='$email'";
$result_check = $conn->query($sql_check);

if ($result_check->num_rows > 0) {
    echo "<script>alert('Email sudah terdaftar!'); window.location='../views/register.php';</script>";
    exit;
}

// Insert data user baru
// Pastikan tabel users memiliki kolom email, username, password, role
$sql_insert = "INSERT INTO users (email, username, password, role) VALUES ('$email', '$email', '$password', 'user')";

if ($conn->query($sql_insert) === TRUE) {
    echo "<script>alert('Registrasi berhasil! Silakan login.'); window.location='../views/login.php';</script>";
} else {
    echo "<script>alert('Registrasi gagal: " . $conn->error . "'); window.location='../views/register.php';</script>";
}

$conn->close();
?>