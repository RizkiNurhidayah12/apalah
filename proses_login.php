<?php
session_start();

// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "frontend_db");

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data dari form
$username = $_POST['username'];
$password = md5($_POST['password']); // Hash MD5

// Cek data user
$sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // Simpan session
    $_SESSION['id_user'] = $row['id_user'];
    $_SESSION['username'] = $row['username'];
    $_SESSION['role'] = $row['role'];

    // Redirect ke dashboard
    header("Location: dashboard.php");
    exit;
} else {
    echo "<script>alert('Login gagal! Username atau password salah'); window.location='login.php';</script>";
}

$conn->close();
?>
