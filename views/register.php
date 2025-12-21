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
            <form action="../config/proses_register.php" method="POST">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Masukkan email disini" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Masukkan password disini" required>
                </div>
                
                <!-- Data Pelanggan -->
                <div class="form-group">
                    <label for="nama">Nama Lengkap</label>
                    <input type="text" id="nama" name="nama" placeholder="Masukkan nama lengkap Anda" required>
                </div>
                
                <div class="form-group">
                    <label for="no_hp">Nomor HP</label>
                    <input type="tel" id="no_hp" name="no_hp" placeholder="Masukkan nomor HP Anda" required>
                </div>
                
                <div class="form-group">
                    <label for="alamat">Alamat</label>
                    <textarea id="alamat" name="alamat" placeholder="Masukkan alamat lengkap Anda" rows="3" required></textarea>
                </div>
                
                <button type="submit" class="register-btn">Buat Akun</button>
                
                <div class="login-link">
                    Sudah mempunyai akun? <a href="login.php">Masuk</a>
                </div>
            </form>
        </div>
    </div>

    <style>
    /* Tambahkan styling untuk textarea */
    .form-group textarea {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 14px;
        transition: border-color 0.3s ease;
        resize: vertical;
        min-height: 80px;
    }
    
    .form-group textarea:focus {
        outline: none;
        border-color: #999;
        box-shadow: 0 0 0 2px rgba(153, 153, 153, 0.1);
    }
    
    /* Style untuk label dan input */
    .form-group {
        margin-bottom: 20px;
        text-align: left;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: 600;
        font-size: 14px;
        color: #333;
    }
    
    .form-group input {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 14px;
        transition: border-color 0.3s ease;
    }
    
    .form-group input:focus {
        outline: none;
        border-color: #999;
        box-shadow: 0 0 0 2px rgba(153, 153, 153, 0.1);
    }
    </style>
</body>
</html>

