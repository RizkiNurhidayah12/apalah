<?php
session_start();

// Cek session login
if (!isset($_SESSION['username']) || !isset($_SESSION['id_pelanggan'])) {
    header("Location: login.php");
    exit;
}

require_once '../config/koneksi.php';

$id_pelanggan = $_SESSION['id_pelanggan'];

// Ambil data pesanan aktif
$query_pesanan = "SELECT id_pesanan FROM pesanan WHERE id_pelanggan = ? AND status = 'pending' LIMIT 1";
$stmt = $koneksi->prepare($query_pesanan);
$stmt->bind_param("i", $id_pelanggan);
$stmt->execute();
$result_pesanan = $stmt->get_result();

if ($result_pesanan->num_rows === 0) {
    header("Location: keranjang.php");
    exit;
}

$pesanan = $result_pesanan->fetch_assoc();
$id_pesanan = $pesanan['id_pesanan'];

// Ambil detail pesanan
$detail_pesanan = [];
$total_harga = 0;

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

// Ambil data pelanggan
$query_pelanggan = "SELECT * FROM pelanggan WHERE id_pelanggan = ?";
$stmt_pelanggan = $koneksi->prepare($query_pelanggan);
$stmt_pelanggan->bind_param("i", $id_pelanggan);
$stmt_pelanggan->execute();
$result_pelanggan = $stmt_pelanggan->get_result();
$pelanggan = $result_pelanggan->num_rows > 0 ? $result_pelanggan->fetch_assoc() : [];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Poskoria Street Coffee</title>
    <link rel="stylesheet" href="../assets/css/js/gambar/input.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="dashboard-body">
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
                        <a href="#">Testimoni</a>
                        <a href="#">FAQ</a>
                        <a href="#">Syarat & Ketentuan</a>
                        <a href="#">Kebijakan Privasi</a>
                        <a href="../views/keranjang.php">Keranjang Belanja</a>
                        <a href="../views/logout.php">Logout</a>
                    </div>
                </li>
            </ul>
        </nav>
        
        <!-- Ikon Kanan -->
        <div class="header-icons">
            <a href="keranjang.php" class="cart-icon-link">
                <div class="cart-icon">🛒</div>
            </a>
            <div class="user-icon">👤</div>
        </div>
    </header>

    <main>
        <section class="checkout-section">
            <div class="checkout-container">
                <h2>Pembayaran</h2>
                
                <div class="checkout-steps">
                    <div class="step active">
                        <div class="step-number">1</div>
                        <div class="step-label">Keranjang</div>
                    </div>
                    <div class="step active">
                        <div class="step-number">2</div>
                        <div class="step-label">Pembayaran</div>
                    </div>
                    <div class="step">
                        <div class="step-number">3</div>
                        <div class="step-label">Selesai</div>
                    </div>
                </div>
                
                <div class="checkout-content">
                    <!-- Left Column: Order Summary -->
                    <div class="order-summary">
                        <h3>Ringkasan Pesanan</h3>
                        
                        <div class="order-items">
                            <?php foreach ($detail_pesanan as $item): ?>
                                <div class="order-item">
                                    <div class="item-image">
                                        <img src="<?= !empty($item['gambar']) ? htmlspecialchars($item['gambar']) : 'https://placehold.co/80x80' ?>" alt="<?= htmlspecialchars($item['nama_menu']) ?>">
                                    </div>
                                    <div class="item-details">
                                        <h4><?= htmlspecialchars($item['nama_menu']) ?></h4>
                                        <p><?= $item['jumlah'] ?> x Rp <?= number_format($item['harga'], 0, ',', '.') ?></p>
                                    </div>
                                    <div class="item-price">
                                        Rp <?= number_format($item['subtotal'], 0, ',', '.') ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="order-total">
                            <div class="total-row">
                                <span>Total Produk</span>
                                <span><?= count($detail_pesanan) ?></span>
                            </div>
                            <div class="total-row">
                                <span>Total</span>
                                <span class="total-amount">Rp <?= number_format($total_harga, 0, ',', '.') ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right Column: Payment Form -->
                    <div class="payment-form">
    <h3>Metode Pembayaran</h3>
    
    <!-- Metode Pembayaran Cards -->
    <div class="payment-methods-cards">
        <div class="payment-card selected" onclick="selectPaymentMethod('qris')">
            <div class="card-header">
                <div class="method-icon">📱</div>
                <div class="method-info">
                    <strong>QRIS</strong>
                    <p>Scan QR Code</p>
                </div>
            </div>
        </div>
        
        <div class="payment-card" onclick="selectPaymentMethod('cash')">
            <div class="card-header">
                <div class="method-icon">💵</div>
                <div class="method-info">
                    <strong>COD</strong>
                    <p>Bayar di Tempat</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- QRIS Payment Details -->
    <div id="qris-details" class="payment-details" style="display: block; margin-top: 20px; padding: 20px; background-color: #f8f9fa; border-radius: 10px;">
        <h4 class="payment-details-title">Scan QR Code untuk Pembayaran</h4>
        
        <div class="qris-content">
            <div class="qris-amount">
                <strong>Total Pembayaran: Rp <?= number_format($total_harga, 0, ',', '.') ?></strong>
            </div>
            
            <div class="qris-code">
                <!-- Foto QR Code Anda -->
                <img src="../assets/images/qris.png" 
                     alt="QRIS Code" 
                     id="qrisImage" 
                     style="width: 200px; height: 200px; margin: 20px auto; display: block; border: 2px solid #ddd; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
            </div>
            
            <div class="qris-instructions">
                <p><strong>Cara Bayar QRIS:</strong></p>
                <ol>
                    <li>Buka aplikasi e-wallet (GoPay, OVO, Dana, dll)</li>
                    <li>Pilih menu "Scan QR"</li>
                    <li>Scan QR Code di atas</li>
                    <li>Konfirmasi pembayaran</li>
                    <li>Kirim screenshot bukti pembayaran ke WhatsApp kami</li>
                </ol>
            </div>
        </div>
    </div>
    
    <!-- Cash Payment Details -->
    <div id="cash-details" class="payment-details" style="display: none; margin-top: 20px; padding: 20px; background-color: #f8f9fa; border-radius: 10px;">
        <h4 class="payment-details-title">Cash on Delivery</h4>
        <div class="cash-content">
            <p>Total yang harus dibayar :</p>
            <p><strong>Rp <?= number_format($total_harga, 0, ',', '.') ?></strong></p>
            <p>*Pastikan uang tunai sudah disiapkan</p>
        </div>
    </div>
    
    <form method="POST" action="proses_pembayaran.php">
        <input type="hidden" name="id_pesanan" value="<?= $id_pesanan ?>">
        <input type="hidden" name="metode_bayar" value="qris" id="metode_bayar_input">
        
        <div class="payment-notes">
            <p><strong>Catatan:</strong></p>
            <ul>
                <li>Kirim bukti pembayaran ke WhatsApp kami</li>
                <li>Pemesanan akan diproses setelah pembayaran dikonfirmasi</li>
            </ul>
        </div>
        
        <div class="checkout-actions">
            <a href="keranjang.php" class="btn-secondary">Kembali ke Keranjang</a>
            <button type="submit" class="btn-primary" id="checkoutButton">
                <i class="fas fa-qrcode"></i> Bayar dengan QRIS
            </button>
        </div>
    </form>
</div>
                </div>
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
        // Function untuk memilih metode pembayaran
        function selectPaymentMethod(method) {
            // Reset semua card
            const cards = document.querySelectorAll('.payment-card');
            cards.forEach(card => {
                card.classList.remove('selected');
            });
            
            // Tandai card yang dipilih
            if (method === 'transfer') {
                document.getElementById('metode_bayar_input').value = 'transfer';
                document.querySelector('.payment-card:nth-child(1)').classList.add('selected');
            } else if (method === 'qris') {
                document.getElementById('metode_bayar_input').value = 'qris';
                document.querySelector('.payment-card:nth-child(2)').classList.add('selected');
            } else if (method === 'cash') {
                document.getElementById('metode_bayar_input').value = 'cash';
                document.querySelector('.payment-card:nth-child(3)').classList.add('selected');
            }
            
            // Tampilkan detail yang sesuai
            showPaymentDetails(method);
        }

        // Function untuk memilih metode pembayaran
        function selectPaymentMethod(method) {
            // Reset semua card
            const cards = document.querySelectorAll('.payment-card');
            cards.forEach(card => {
                card.classList.remove('selected');
            });
            
            // Tandai card yang dipilih
            if (method === 'qris') {
                document.getElementById('metode_bayar_input').value = 'qris';
                document.querySelector('.payment-card:nth-child(1)').classList.add('selected');
            } else if (method === 'cash') {
                document.getElementById('metode_bayar_input').value = 'cash';
                document.querySelector('.payment-card:nth-child(2)').classList.add('selected');
            }
            
            // Tampilkan detail yang sesuai
            showPaymentDetails(method);
        }

        // Function untuk menampilkan detail pembayaran
        function showPaymentDetails(method) {
            // Sembunyikan semua detail
            document.getElementById('qris-details').style.display = 'none';
            document.getElementById('cash-details').style.display = 'none';
            
            // Tampilkan detail yang dipilih
            if (method === 'qris') {
                document.getElementById('qris-details').style.display = 'block';
            } else if (method === 'cash') {
                document.getElementById('cash-details').style.display = 'block';
            }
            
            // Update tombol submit
            const button = document.getElementById('checkoutButton');
            if (method === 'qris') {
                button.innerHTML = '<i class="fas fa-qrcode"></i> Bayar dengan QRIS';
            } else if (method === 'cash') {
                button.innerHTML = '<i class="fas fa-money-bill-wave"></i> Bayar Cash';
            }
        }

        // Inisialisasi default
        document.addEventListener('DOMContentLoaded', function() {
            // Set default selection to QRIS
            document.querySelector('.payment-card:nth-child(1)').click();
        });
    </script>
</body>
</html>
