<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semua Produk - Poskoria Street Coffee</title>
    <link rel="stylesheet" href="../assets/css/js/gambar/input.css">
    <!-- Tambahkan smooth scroll behavior -->
    <style>
        html {
            scroll-behavior: smooth;
        }
    </style>
</head>
<body class="dashboard-body">
    <!-- Header -->
<header class="header" id="header">
    <!-- Hamburger + Logo dikelompokkan dalam satu div -->
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

    <!-- Navigasi -->
    <nav class="nav-menu">
        <ul>
            <li><a href="../views/dashboard.php">Beranda</a></li>
            <li><a href="semua_produk.php" class="active">Produk</a></li>
            <li><a href="../views/dashboard.php#tentang-kami">Tentang Kami</a></li>
            <li><a href="../views/dashboard.php#kontak">Kontak</a></li>
            <li><a href="#">Lainnya</a></li>
        </ul>
    </nav>

    <!-- Ikon -->
    <div class="header-icons">
        <div class="cart-icon">🛒</div>
        <div class="user-icon">👤</div>
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
            <li><a href="#">Lainnya</a></li>
        </ul>
    </div>

    <div class="overlay" id="overlay"></div>

    <!-- Main Content -->
    <main>
        <!-- Kategori Produk Section -->
        <section id="produk" class="products-section">
            <h2>Kategori produk</h2>
            
            <!-- Kategori Filter -->
            <div class="category-filter">
                <button class="category-btn active" data-filter="all">Semua Produk</button>
                <button class="category-btn" data-filter="minuman">Minuman</button>
                <button class="category-btn" data-filter="makanan">Makanan</button>
                <button class="category-btn" data-filter="cemilan">Cemilan</button>
            </div>
            
            <!-- Products Grid -->
<div class="products-grid">
    <?php
    require_once '../config/koneksi.php';

    // Ambil semua data menu dari database
    $query = "SELECT * FROM menu ORDER BY kategori, nama_menu";
    $result = $koneksi->query($query);

    if ($result && $result->num_rows > 0):
        while ($row = $result->fetch_assoc()):
            // Bersihkan kategori untuk digunakan sebagai data-category (lowercase, tanpa spasi)
            $kategori_slug = strtolower(str_replace(' ', '', $row['kategori']));
    ?>
            <div class="product-card" data-category="<?= htmlspecialchars($kategori_slug) ?>">
                <img src="<?= !empty($row['gambar']) ? htmlspecialchars($row['gambar']) : 'https://placehold.co/300x300' ?>" 
                     alt="<?= htmlspecialchars($row['nama_menu']) ?>">
                <div class="product-info">
                    <span class="category"><?= htmlspecialchars(ucfirst($row['kategori'])) ?></span>
                    <h3><?= htmlspecialchars($row['nama_menu']) ?></h3>
                    <p><?= htmlspecialchars($row['deskripsi']) ?></p>
                    <div class="price-action">
                        <span class="price">Rp <?= number_format($row['harga'], 0, ',', '.') ?></span>
                        <div class="action-buttons">
                            <button class="heart-btn">♥</button>
                            <button class="buy-btn">Beli</button>
                        </div>
                    </div>
                </div>
            </div>
    <?php
        endwhile;
    else:
    ?>
        <p style="grid-column: 1 / -1; text-align: center; color: #666;">Belum ada produk tersedia.</p>
    <?php endif; ?>
</div>
        </section>
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
        // Smooth scroll untuk navigasi internal (jika ada anchor links)
        document.addEventListener('DOMContentLoaded', function() {
            // Hamburger menu functionality
            const hamburgerMenu = document.getElementById('hamburgerMenu');
            const mobileNav = document.getElementById('mobileNav');
            const closeMobileNav = document.getElementById('closeMobileNav');
            const overlay = document.getElementById('overlay');
            
            if (hamburgerMenu && mobileNav && closeMobileNav && overlay) {
                hamburgerMenu.addEventListener('click', function() {
                    mobileNav.classList.add('active');
                    overlay.classList.add('active');
                    document.body.style.overflow = 'hidden';
                });
                
                closeMobileNav.addEventListener('click', function() {
                    mobileNav.classList.remove('active');
                    overlay.classList.remove('active');
                    document.body.style.overflow = 'auto';
                });
                
                overlay.addEventListener('click', function() {
                    mobileNav.classList.remove('active');
                    overlay.classList.remove('active');
                    document.body.style.overflow = 'auto';
                });
            }
            
            // Product filter functionality
            const filterButtons = document.querySelectorAll('.category-btn');
            const productCards = document.querySelectorAll('.product-card');
            
            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Update active button
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');
                    
                    const filter = this.getAttribute('data-filter');
                    
                    productCards.forEach(card => {
                        if (filter === 'all' || card.getAttribute('data-category') === filter) {
                            card.style.display = 'block';
                        } else {
                            card.style.display = 'none';
                        }
                    });
                });
            });
        });
    </script>
</body>
</html>