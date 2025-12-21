<?php
session_start();

// Jika sudah login, redirect ke dashboard
if (isset($_SESSION['username'])) {
    header("Location: dashboard.php");
    exit;
}

require_once '../config/koneksi.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = md5($_POST['password']);
    
    // Query untuk mendapatkan data user dan pelanggan
    $sql = "SELECT u.*, p.id_pelanggan, p.nama as nama_pelanggan 
            FROM users u 
            LEFT JOIN pelanggan p ON u.id_pelanggan = p.id_pelanggan 
            WHERE u.username = ? AND u.password = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Simpan data ke session
        $_SESSION['id_user'] = $row['id_user'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['role'] = $row['role'];
        
        // Simpan id_pelanggan jika ada
        if ($row['id_pelanggan']) {
            $_SESSION['id_pelanggan'] = $row['id_pelanggan'];
            $_SESSION['nama_pelanggan'] = $row['nama_pelanggan'];
        }
        
        // Set cookie untuk session persistence (opsional)
        if (!isset($_COOKIE['PHPSESSID'])) {
            session_regenerate_id(true);
        }
        
        // Redirect ke halaman sebelumnya atau dashboard
        if (isset($_GET['redirect']) && $_GET['redirect'] == 'detail_produk' && isset($_GET['id_menu'])) {
            header("Location: detail_produk.php?id_menu=" . intval($_GET['id_menu']));
        } else {
            header("Location: dashboard.php");
        }
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Poskoria Street Coffee</title>
    <link rel="stylesheet" href="../assets/css/js/gambar/input.css">
</head>
<body class="login-body">
    <div class="login-container">
        <div class="login-card">
            <h1>Login</h1>
            
            <?php if ($error): ?>
                <p style="color: red; margin-bottom: 20px;"><?= $error ?></p>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="username">Email</label>
                    <input type="text" id="username" name="username" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <button type="submit" class="login-btn">Login</button>
                
                <div class="register-link">
                    Belum punya akun? <a href="register.php">Daftar sekarang</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
