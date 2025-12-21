<?php
session_start();

// Cek session login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

require_once '../config/koneksi.php';

// Validasi parameter ID
if (!isset($_GET['id_menu']) || !is_numeric($_GET['id_menu'])) {
    header("Location: semua_produk.php");
    exit;
}

$id_menu = intval($_GET['id_menu']);

try {
    // Ambil data produk dari database dengan prepared statement
    $query = "SELECT id_menu, nama_menu, harga, deskripsi, gambar FROM menu WHERE id_menu = ?";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("i", $id_menu);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        throw new Exception("Produk tidak ditemukan");
    }
    
    $produk = $result->fetch_assoc();
    
} catch (Exception $e) {
    error_log("Error loading product: " . $e->getMessage());
    header("Location: semua_produk.php?error=product_not_found");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($produk['nama_menu']) ?> - Poskoria Street Coffee</title>
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
            <div >
                <img src="../assets/css/js/gambar/logo.png" alt="Poskoria Logo" class="logo">
            </div>
        </div>
        
        <!-- Navigasi Desktop -->
        <nav class="nav-menu">
            <ul>
                <li><a href="dashboard.php">Beranda</a></li>
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
                        <a href="../views/logout.php">Logout</a>
                    </div>
                </li>
            </ul>
        </nav>

        <!-- Ikon Kanan -->
        <div class="header-icons">
            <a href="../views/keranjang.php">
                <div class="cart-icon">🛒</div>
            </a>
            <a href="../views/profile.php">
            <div class="user-icon">👤</div>
            </a>
        </div>
    </header>
        <!-- Mobile Navigation Menu -->
        <div class="mobile-nav" id="mobileNav">
            <span class="mobile-nav-close" id="closeMobileNav">&times;</span>
            <ul>
                <li><a href="../views/dashboard.php">Beranda</a></li>
                <li><a href="semua_produk.php" class="active">Produk</a></li>
                <li><a href="../views/dashboard.php#tentang-kami">Tentang Kami</a></li>
                <li><a href="../views/dashboard.php#kontak">Kontak</a></li>
                <li><a href="../views/dashboard.php#testimoni">Testimoni</a></li>
                <li><a href="../views/dashboard.php#faq">FAQ</a></li>
                <li><a href="../views/dashboard.php#syarat-ketentuan">Syarat & Ketentuan</a></li>
                <li><a href="../views/dashboard.php#syarat-ketentuan">Kebijakan Privasi</a></li>
                <li><a href="../views/logout.php">Logout</a></li>
            </ul>
        </div>

        <!-- Overlay -->
        <div class="overlay" id="overlay"></div>

    <!-- Main Content -->
    <main class="product-detail-container">
        <div class="product-detail-card">
            <!-- Product Image -->
            <div class="product-image-section">
                <div class="product-image-container">
                    <img 
                        src="<?= htmlspecialchars(!empty($produk['gambar']) ? $produk['gambar'] : 'https://placehold.co/400x500') ?>" 
                        alt="<?= htmlspecialchars($produk['nama_menu']) ?>"
                        class="product-image"
                        loading="lazy"
                    >
                </div>
            </div>
            
            <!-- Product Info -->
            <div class="product-info-section">
                <div class="product-header">
                    <h1 class="product-title"><?= htmlspecialchars($produk['nama_menu']) ?></h1>
                    <div class="product-price">Rp <?= number_format($produk['harga'], 0, ',', '.') ?></div>
                    <div class="product-category">
                        <?= htmlspecialchars(explode(' ', $produk['nama_menu'])[0]) ?>
                    </div>
                </div>
                
                <div class="product-description">
                    <p><?= nl2br(htmlspecialchars($produk['deskripsi'])) ?></p>
                </div>
                
                <!-- Action Buttons -->
                <form method="POST" action="tambah_ke_keranjang.php" class="product-actions">
                    <input type="hidden" name="id_menu" value="<?= $produk['id_menu'] ?>">
                    
                    <div class="quantity-selector">
                        <button type="button" class="quantity-btn minus" aria-label="Kurangi jumlah" onclick="updateQuantity(-1)">
                            <i class="fas fa-minus"></i>
                        </button>
                        <input 
                            type="number" 
                            id="jumlah" 
                            name="jumlah" 
                            value="1" 
                            min="1" 
                            max="99"
                            class="quantity-input"
                            oninput="validateQuantity(this)"
                        >
                        <button type="button" class="quantity-btn plus" aria-label="Tambah jumlah" onclick="updateQuantity(1)">
                            <i class="fas fa-plus"></i>
                        </button>
                        <button type="button" class="favorite-btn" aria-label="Tambah ke favorit" onclick="toggleFavorite(this)">
                            <i class="far fa-heart"></i>
                        </button>
                    </div>
                    
                    <div class="action-buttons">
                        <button type="submit" class="btn btn-primary btn-add-to-cart">
                            <i class="fas fa-shopping-cart"></i>
                            Masukkan Keranjang
                        </button>
                        <button type="button" class="btn btn-secondary btn-buy-now" onclick="buyNow()">
                            <i class="fas fa-bolt"></i>
                            Beli Sekarang
                        </button>
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
        // Quantity management
        function updateQuantity(delta) {
            const input = document.getElementById('jumlah');
            let value = parseInt(input.value) + delta;
            if (value < 1) value = 1;
            if (value > 99) value = 99;
            input.value = value;
        }
        
        function validateQuantity(input) {
            let value = parseInt(input.value);
            if (isNaN(value) || value < 1) value = 1;
            if (value > 99) value = 99;
            input.value = value;
        }
        
        // Favorite toggle
        function toggleFavorite(button) {
            const icon = button.querySelector('i');
            if (icon.classList.contains('far')) {
                icon.classList.remove('far');
                icon.classList.add('fas');
                icon.style.color = '#8b5a2b';
                // Di sini bisa ditambahkan AJAX call untuk menyimpan ke database
            } else {
                icon.classList.remove('fas');
                icon.classList.add('far');
                icon.style.color = '';
            }
        }
        
        // Buy now function
        function buyNow() {
            // Di sini bisa ditambahkan logika untuk beli langsung
            alert('Fitur Beli Sekarang akan segera hadir!');
        }
        
        // Mobile menu toggle (jika diperlukan)
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-focus quantity input
            document.getElementById('jumlah').focus();
        });

        document.addEventListener('DOMContentLoaded', function() {
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
        });
    </script>
</body>
</html>