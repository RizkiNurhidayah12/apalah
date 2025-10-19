<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Akun - Poskoria Street Coffee</title>
    <link rel="stylesheet" href="../assets/css/js/gambar/input.css">
</head>
<body class="register-body">
    <div class="register-container">
        <div class="register-card">
            <h1>Buat Akun</h1>
            
            <!-- Google Register Button -->
            <button class="google-btn">
                <img src="https://placehold.co/20x20?text=G" alt="Google"> Mendaftar dengan Google
            </button>
            
            <!-- OR Divider -->
            <div class="divider">
                <span>OR</span>
            </div>
            
            <!-- Email and Password Form -->
            <form action="../config/proses_register.php" method="POST"> <!-- Perhatikan path ini -->
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Masukkan email disini" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Masukkan password disini" required>
                </div>
                
                <button type="submit" class="register-btn">Buat Akun</button>
                
                <div class="login-link">
                    Sudah mempunyai akun? <a href="login.php">Masuk</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>