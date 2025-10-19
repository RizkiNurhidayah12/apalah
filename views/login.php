<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Poskoria Street Coffee</title>
    <link rel="stylesheet" href="../assets/css/js/gambar/input.css">
</head>
<body class="login-body">
    <div class="login-container">
        <div class="login-card">
            <h1>Poskoria Street Coffee</h1>
            <p>Silahkan login untuk melanjutkan</p>
            
            <form action="../config/proses_login.php" method="POST">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Masukkan username disini">
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Masukkan password disini">
                </div>
                
                <button type="submit" class="login-btn">Login</button>
                
                <div class="register-link">
                    Belum punya akun? <a href="register.php">Daftar</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>