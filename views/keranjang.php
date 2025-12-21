<?php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Koneksi ke database
require_once '../config/koneksi.php';

// Ambil ID pelanggan dari session
$id_pelanggan = $_SESSION['id_pelanggan'] ?? 0;

// Ambil data pesanan aktif (status pending) untuk pelanggan ini
$query_pesanan = "SELECT id_pesanan FROM pesanan WHERE id_pelanggan = ? AND status = 'pending' LIMIT 1";
$stmt = $koneksi->prepare($query_pesanan);
$stmt->bind_param("i", $id_pelanggan);
$stmt->execute();
$result_pesanan = $stmt->get_result();
$pesanan = $result_pesanan->fetch_assoc();

$id_pesanan = $pesanan['id_pesanan'] ?? 0;

// Ambil detail pesanan jika ada pesanan aktif
$detail_pesanan = [];
$total_harga = 0;

if ($id_pesanan > 0) {
    $query_detail = "
        SELECT d.*, m.nama_menu, m.gambar, m.harga 
        FROM detail_pesanan d 
        JOIN menu m ON d.id_menu = m.id_menu 
        WHERE d.id_pesanan = ?";
    $stmt_detail = $koneksi->prepare($query_detail);
    $stmt_detail->bind_param("i", $id_pesanan);
    $stmt_detail->execute();
    $result_detail = $stmt_detail->get_result();

    while ($row = $result_detail->fetch_assoc()) {
        $detail_pesanan[] = $row;
        $total_harga += $row['subtotal'];
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja - Poskoria Street Coffee</title>
    <link rel="stylesheet" href="../assets/css/js/gambar/input.css">
</head>
<body class="dashboard-body">
    <!-- Header -->
    <header class="header" id="header">
        <!-- Hamburger + Logo -->
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
        
        <!-- Ikon Kanan -->
        <div class="header-icons">
            <a href="keranjang.php" class="cart-icon-link">
                <div class="cart-icon">🛒</div>
            </a>
            <a href="../views/profile.php">
                <div class="user-icon">👤</div>
            </a>
        </div>
    </header>
    <!-- Overlay -->
    <div class="overlay" id="overlay"></div>

    <?php
    // Tampilkan pesan dari session
    if (isset($_SESSION['cart_message'])) {
        $message = $_SESSION['cart_message'];
        unset($_SESSION['cart_message']); // Hapus setelah ditampilkan
        
        if ($message['type'] === 'success') {
            echo '<div class="alert-success"><i class="fas fa-check-circle"></i> ' . htmlspecialchars($message['text']) . '</div>';
        } elseif ($message['type'] === 'error') {
            echo '<div class="alert-error"><i class="fas fa-exclamation-circle"></i> ' . htmlspecialchars($message['text']) . '</div>';
        } elseif ($message['type'] === 'warning') {
            echo '<div class="alert-warning"><i class="fas fa-exclamation-triangle"></i> ' . htmlspecialchars($message['text']) . '</div>';
        }
    }
    ?>

    <main>
        <?php if (empty($detail_pesanan)): ?>
            <!-- Keranjang Kosong - Persis Seperti Gambar -->
            <section class="empty-cart-section">
                <div class="empty-cart-content">
                    <h2>Wah, keranjang kamu kosong nih, belanja dulu gih</h2>
                    <p>Mau beli apa hari ini?</p>
                    <p>Masukkin keranjang aja dulu daripada nanti lupa!</p>
                    <a href="semua_produk.php" class="btn-start-shopping">Mulai Belanja</a>
                </div>
            </section>
        <?php else: ?>
            <!-- Keranjang Berisi -->
            <section class="cart-section">
                <h2>Keranjang Belanja</h2>
                <div class="cart-items">
                    <?php foreach ($detail_pesanan as $item): ?>
                    <div class="cart-item" data-harga="<?= $item['harga'] ?>">
                        <div class="item-checkbox">
                            <input type="checkbox" name="selected_items[]" value="<?= $item['id_detail'] ?>" checked>
                        </div>
                        <div class="item-image">
                            <img src="<?= !empty($item['gambar']) ? htmlspecialchars($item['gambar']) : 'https://placehold.co/100x100' ?>" alt="<?= htmlspecialchars($item['nama_menu']) ?>">
                        </div>
                        <div class="item-info">
                            <h3><?= htmlspecialchars($item['nama_menu']) ?></h3>
                            <p><?= htmlspecialchars($item['deskripsi'] ?? '') ?></p>
                        </div>
                        <div class="item-quantity">
                            <button class="qty-btn minus">−</button>
                            <input type="number" value="<?= $item['jumlah'] ?>" min="1" max="99" readonly>
                            <button class="qty-btn plus">+</button>
                        </div>
                        <div class="item-price">
                            Rp <?= number_format($item['subtotal'], 0, ',', '.') ?>
                        </div>
                        <div class="item-actions">
                            <form method="POST" action="hapus_dari_keranjang.php">
                                <input type="hidden" name="id_detail" value="<?= $item['id_detail'] ?>">
                                <button type="submit" class="btn-remove" 
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus <?= htmlspecialchars($item['nama_menu']) ?> dari keranjang?')">
                                    Hapus Dari Keranjang
                                </button>
                            </form>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Total & Checkout -->
                <div class="cart-footer">
                    <div class="select-all">
                        <input type="checkbox" id="select-all" checked>
                        <label for="select-all">Pilih Semua Produk</label>
                    </div>
                    <div class="total-summary">
                        <span>Total Pembelian (<?= count($detail_pesanan) ?> Produk)</span>
                        <span>Rp <?= number_format($total_harga, 0, ',', '.') ?></span>
                    </div>
                    <div class="checkout-button">
                        <a href="checkout.php" class="btn-checkout">Checkout</a>
                    </div>
                </div>
            </section>
        <?php endif; ?>
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
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle select all
        const selectAll = document.getElementById('select-all');
        const checkboxes = document.querySelectorAll('.cart-item input[type="checkbox"]');
        
        if (selectAll && checkboxes.length > 0) {
            selectAll.addEventListener('change', function() {
                checkboxes.forEach(cb => cb.checked = this.checked);
            });
            
            checkboxes.forEach(cb => {
                cb.addEventListener('change', function() {
                    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
                    selectAll.checked = allChecked;
                });
            });
        }
        
        // Quantity buttons
        document.querySelectorAll('.qty-btn.minus').forEach(btn => {
            btn.addEventListener('click', function() {
                const input = this.nextElementSibling;
                let value = parseInt(input.value);
                if (value > 1) {
                    input.value = value - 1;
                    updateSubtotal(input);
                }
            });
        });
        
        document.querySelectorAll('.qty-btn.plus').forEach(btn => {
            btn.addEventListener('click', function() {
                const input = this.previousElementSibling;
                let value = parseInt(input.value);
                input.value = value + 1;
                updateSubtotal(input);
            });
        });
        
        // Function untuk update subtotal
        function updateSubtotal(input) {
            const itemRow = input.closest('.cart-item');
            const hargaSatuan = parseFloat(itemRow.dataset.harga) || 0;
            const jumlah = parseInt(input.value);
            const subtotal = hargaSatuan * jumlah;
            
            // Update subtotal di UI
            itemRow.querySelector('.item-price').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
            
            // Update total keseluruhan
            updateTotal();
        }
        
        // Function untuk update total keseluruhan
        function updateTotal() {
            let totalHarga = 0;
            document.querySelectorAll('.cart-item').forEach(item => {
                const harga = parseFloat(item.querySelector('.item-price').textContent.replace(/[^\d]/g, '')) || 0;
                totalHarga += harga;
            });
            
            // Update total di footer
            const totalEl = document.querySelector('.total-summary span:last-child');
            if (totalEl) {
                totalEl.textContent = 'Rp ' + totalHarga.toLocaleString('id-ID');
            }
            
            // Update jumlah item
            const itemCount = document.querySelectorAll('.cart-item').length;
            const itemCounter = document.querySelector('.total-summary span:first-child');
            if (itemCounter) {
                itemCounter.textContent = `Total Pembelian (${itemCount} Produk)`;
            }
        }
        
        // Inisialisasi harga saat pertama kali load
        document.querySelectorAll('.cart-item').forEach(item => {
            const hargaSatuan = parseFloat(item.dataset.harga) || 0;
            const jumlah = parseInt(item.querySelector('.item-quantity input').value);
            const subtotal = hargaSatuan * jumlah;
            item.querySelector('.item-price').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
        });
        
        // Update total saat halaman dimuat
        updateTotal();

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
    });
    </script>
</body>
</html>