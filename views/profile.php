<?php
session_start();
// Cek session login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

require_once '../config/koneksi.php';

// Ambil data pelanggan dari session
$id_pelanggan = $_SESSION['id_pelanggan'] ?? null;
$username = $_SESSION['username'] ?? 'Pengguna';

// Jika id_pelanggan tidak ada di session, coba dapatkan dari database
if (!$id_pelanggan && isset($_SESSION['id_user'])) {
    $id_user = $_SESSION['id_user'];
    $query = "SELECT id_pelanggan FROM users WHERE id_user = ?";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("i", $id_user);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $id_pelanggan = $user['id_pelanggan'];
        $_SESSION['id_pelanggan'] = $id_pelanggan; // Simpan ke session
    }
}

// Hanya query database jika id_pelanggan valid
if ($id_pelanggan) {
    $query = "SELECT * FROM pelanggan WHERE id_pelanggan = ?";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("i", $id_pelanggan);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $pelanggan = $result->fetch_assoc();
    } else {
        // Jika tidak ada data pelanggan, redirect ke dashboard
        header("Location: dashboard.php");
        exit;
    }
} else {
    // Jika tidak ada id_pelanggan sama sekali, redirect ke login
    header("Location: login.php");
    exit;
}

// Handle form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $nama_lengkap = $_POST['nama_lengkap'] ?? '';
    $nama_panggilan = $_POST['nama_panggilan'] ?? '';
    $alamat = $_POST['alamat'] ?? '';
    $no_hp = $_POST['no_hp'] ?? '';
    $email = $_POST['email'] ?? '';
    
    // Validasi input
    $errors = [];
    
    if (empty($nama_lengkap)) {
        $errors[] = "Nama lengkap tidak boleh kosong";
    }
    
    if (empty($no_hp)) {
        $errors[] = "Nomor HP tidak boleh kosong";
    }
    
    if (empty($alamat)) {
        $errors[] = "Alamat tidak boleh kosong";
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email tidak valid";
    }
    
    // Jika ada error, simpan ke session dan redirect kembali
    if (!empty($errors)) {
        $_SESSION['profile_errors'] = $errors;
        $_SESSION['profile_input'] = [
            'nama_lengkap' => $nama_lengkap,
            'nama_panggilan' => $nama_panggilan,
            'alamat' => $alamat,
            'no_hp' => $no_hp,
            'email' => $email
        ];
        header("Location: profile.php");
        exit;
    }
    
    try {
        // Update data pelanggan
        $query_pelanggan = "UPDATE pelanggan SET nama = ?, no_hp = ?, alamat = ? WHERE id_pelanggan = ?";
        $stmt_pelanggan = $koneksi->prepare($query_pelanggan);
        $stmt_pelanggan->bind_param("sssi", $nama_lengkap, $no_hp, $alamat, $id_pelanggan);
        
        if (!$stmt_pelanggan->execute()) {
            throw new Exception("Gagal memperbarui data pelanggan");
        }
        
        // Update data user (email/username)
        $query_user = "UPDATE users SET username = ?, email = ? WHERE id_pelanggan = ?";
        $stmt_user = $koneksi->prepare($query_user);
        $stmt_user->bind_param("ssi", $email, $email, $id_pelanggan);
        
        if (!$stmt_user->execute()) {
            throw new Exception("Gagal memperbarui data user");
        }
        
        // Commit transaction
        $koneksi->commit();
        
        // Update session
        $_SESSION['username'] = $email;
        
        // Set pesan sukses
        $_SESSION['profile_success'] = "Profil berhasil diperbarui!";
        
        header("Location: profile.php?success=updated");
        exit;
        
    } catch (Exception $e) {
        // Rollback jika terjadi error
        $koneksi->rollback();
        
        $_SESSION['profile_errors'] = [$e->getMessage()];
        $_SESSION['profile_input'] = [
            'nama_lengkap' => $nama_lengkap,
            'nama_panggilan' => $nama_panggilan,
            'alamat' => $alamat,
            'no_hp' => $no_hp,
            'email' => $email
        ];
        
        error_log("Error updating profile: " . $e->getMessage());
        header("Location: profile.php");
        exit;
    } finally {
        $koneksi->autocommit(TRUE);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - Poskoria Street Coffee</title>
    <link rel="stylesheet" href="../assets/css/js/gambar/input.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="detail-body">
    <!-- Header -->
    <header class="header" id="header">
        <!-- Kelompok kiri: Hamburger + Logo -->
        <div class="header-start" style="display: flex; align-items: center; gap: 12px;">
            <div class="hamburger-menu" id="hamburgerMenu">
                <div class="hamburger-line"></div>
                <div class="hamburger-line"></div>
                <div class="hamburger-line"></div>
            </div>
            <div>
                <img src="../assets/css/js/gambar/logo.png" alt="Poskoria Logo" class="logo">
            </div>
        </div>
        <!-- Navigasi Desktop -->
        <nav class="nav-menu">
            <ul>
                <li><a href="../views/dashboard.php">Beranda</a></li>
                <li><a href="semua_produk.php">Produk</a></li>
                <li><a href="../views/dashboard.php#tentang-kami">Tentang Kami</a></li>
                <li><a href="../views/dashboard.php#kontak">Kontak</a></li>
                <!-- Dropdown Lainnya -->
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle">Lainnya</a>
                    <div class="dropdown-menu">
                        <a href="../views/dashboard.php#testimoni">Testimoni</a>
                        <a href="../views/dashboard.php#faq">FAQ</a>
                        <a href="../views/dashboard.php#syarat-ketentuan">Syarat & Ketentuan</a>
                        <a href="../views/dashboard.php#syarat-ketentuan">Kebijakan Privasi</a>
                        <a href="../views/keranjang.php">Keranjang Belanja</a>
                        <a href="../views/logout.php">Logout</a>
                    </div>
                </li>
            </ul>
        </nav>
        <!-- Ikon Kanan -->
        <div class="header-icons">
            <a href="keranjang.php">
                <div class="cart-icon">🛒</div>
            </a>
            <div class="user-icon">👤</div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="profile-container">
        <!-- Header Profil -->
        <div class="profile-header">
            <div class="profile-banner">
                <img src="../assets/css/js/gambar/banner profil.png" alt="Banner Profil">
            </div>
            <div class="profile-avatar">
                <img src="https://placehold.co/150x150?text=<?= urlencode(substr($pelanggan['nama'] ?? 'U', 0, 1)) ?>" alt="Avatar">
            </div>
        </div>

        <!-- Informasi Profil -->
        <div class="profile-content">
            <div class="profile-info">
                <h1><?= htmlspecialchars($pelanggan['nama'] ?? 'Ujung') ?></h1>
                <p><?= htmlspecialchars($username) ?></p>
                
                <!-- Form Edit Profil -->
                <form method="POST" action="profile.php">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="id_pelanggan" value="<?= $pelanggan['id_pelanggan'] ?>">
                    
                    <!-- Nama Lengkap dan Nama Panggilan -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="nama_lengkap">Nama Lengkap</label>
                            <input type="text" id="nama_lengkap" name="nama_lengkap" 
                                   value="<?= htmlspecialchars($pelanggan['nama'] ?? '') ?>" 
                                   placeholder="Masukkan Nama Lengkap" required>
                        </div>
                        <div class="form-group">
                            <label for="nama_panggilan">Nama Panggilan</label>
                            <input type="text" id="nama_panggilan" name="nama_panggilan" 
                                   value="<?= htmlspecialchars($pelanggan['nama'] ?? '') ?>" 
                                   placeholder="Masukkan Nama Panggilan">
                        </div>
                    </div>
                    
                    <!-- Alamat -->
                    <div class="form-row">
                        <div class="form-group" style="width: 100%;">
                            <label for="alamat">Alamat</label>
                            <input type="text" id="alamat" name="alamat" 
                                   value="<?= htmlspecialchars($pelanggan['alamat'] ?? '') ?>" 
                                   placeholder="Masukkan Alamat" required>
                        </div>
                    </div>
                    
                    <!-- Nomor Telepon dan Email -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="no_hp">Nomor Telepon</label>
                            <input type="tel" id="no_hp" name="no_hp" 
                                   value="<?= htmlspecialchars($pelanggan['no_hp'] ?? '') ?>" 
                                   placeholder="Masukkan Nomor Telepon" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" 
                                   value="<?= htmlspecialchars($username) ?>" 
                                   placeholder="Masukkan Email" required>
                        </div>
                    </div>
                    
                    <!-- Tombol Aksi -->
                    <div class="profile-actions">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-logo">PO</div>
        <div class="footer-content">
            <p>© 2025 Poskoria Street Coffee. All rights reserved.</p>
            <div class="footer-contact">
                <span>📞 085691111136</span>
                <span>📸 @poskoria</span>
            </div>
        </div>
    </footer>

    <script>
        // dropdown mobile
        document.addEventListener('DOMContentLoaded', function() {
            const dropdownToggle = document.querySelector('.dropdown-toggle');
            const dropdownMenu = document.querySelector('.dropdown-menu');
            
            if (dropdownToggle && dropdownMenu) {
                dropdownToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Toggle dropdown
                    dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
                });
                
                // Tutup dropdown saat klik di luar
                document.addEventListener('click', function(e) {
                    if (!e.target.closest('.dropdown')) {
                        dropdownMenu.style.display = 'none';
                    }
                });
            }

            const hamburgerMenu = document.getElementById('hamburgerMenu');
            const mobileNav = document.getElementById('mobileNav');
            const closeMobileNav = document.getElementById('closeMobileNav');
            const overlay = document.getElementById('overlay');

            // Fungsi untuk menutup mobile menu
            function closeMobileMenu() {
                mobileNav.classList.remove('active');
                overlay.classList.remove('active');
                document.body.style.overflow = 'auto';
            }

            // Buka menu mobile
            hamburgerMenu.addEventListener('click', function() {
                mobileNav.classList.add('active');
                overlay.classList.add('active');
                document.body.style.overflow = 'hidden';
            });

            // Tutup menu mobile
            closeMobileNav.addEventListener('click', closeMobileMenu);
            overlay.addEventListener('click', closeMobileMenu);

            // Tutup mobile menu saat klik link navigasi
            const mobileLinks = document.querySelectorAll('.mobile-nav a');
            mobileLinks.forEach(link => {
                link.addEventListener('click', closeMobileMenu);
            });
            // Tampilkan pesan sukses/error dari session
            document.addEventListener('DOMContentLoaded', function() {
                const urlParams = new URLSearchParams(window.location.search);
                
                // Tampilkan pesan sukses
                if (urlParams.get('success') === 'updated') {
                    alert('Profil berhasil diperbarui!');
                    // Hapus parameter success dari URL tanpa reload
                    window.history.replaceState({}, document.title, window.location.pathname);
                }
                
                // Tampilkan pesan error
                <?php if (isset($_SESSION['profile_errors'])): ?>
                    const errors = <?= json_encode($_SESSION['profile_errors']) ?>;
                    alert(errors.join('\n'));
                    // Hapus pesan error dari session
                    <?php unset($_SESSION['profile_errors']); ?>
                <?php endif; ?>
            });
        });
    </script>
</body>
</html>