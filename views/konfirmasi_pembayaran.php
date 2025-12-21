<?php
session_start();

// Cek session login
if (!isset($_SESSION['username']) || !isset($_SESSION['id_pelanggan'])) {
    header("Location: login.php");
    exit;
}

require_once '../config/koneksi.php';

if (!isset($_GET['id_pesanan']) || !is_numeric($_GET['id_pesanan'])) {
    header("Location: dashboard.php");
    exit;
}

$id_pesanan = intval($_GET['id_pesanan']);
$id_pelanggan = $_SESSION['id_pelanggan'];

// Verifikasi kepemilikan pesanan
$query_check = "SELECT * FROM pesanan WHERE id_pesanan = ? AND id_pelanggan = ?";
$stmt_check = $koneksi->prepare($query_check);
$stmt_check->bind_param("ii", $id_pesanan, $id_pelanggan);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows === 0) {
    header("Location: dashboard.php");
    exit;
}

$pesanan = $result_check->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pembayaran - Poskoria Street Coffee</title>
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
        <section class="confirmation-section">
            <div class="confirmation-container">
                <div class="confirmation-content">
                    <div class="confirmation-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h2>Pesanan Berhasil!</h2>
                    <p>Pesanan Anda sedang diproses. Silakan lakukan pembayaran dan kirim bukti pembayaran ke WhatsApp kami.</p>
                    
                    <div class="order-details">
                        <h3>Detail Pesanan</h3>
                        <div class="detail-row">
                            <span>Nomor Pesanan</span>
                            <span><?= $pesanan['id_pesanan'] ?></span>
                        </div>
                        <div class="detail-row">
                            <span>Tanggal Pesan</span>
                            <span><?= date('d M Y H:i', strtotime($pesanan['tanggal_pesanan'])) ?></span>
                        </div>
                        <div class="detail-row">
                            <span>Status</span>
                            <span class="status-diproses">Diproses</span>
                        </div>
                    </div>
                    
                    <div class="payment-instructions">
                        <h3>Instruksi Pembayaran</h3>
                        <div class="instruction-item">
                            <div class="instruction-number">1</div>
                            <div class="instruction-text">
                                <strong>Kirim bukti pembayaran</strong> ke WhatsApp kami: <a href="https://wa.me/6285691111136">085691111136</a>
                            </div>
                        </div>
                        <div class="instruction-item">
                            <div class="instruction-number">2</div>
                            <div class="instruction-text">
                                <strong>Tunggu konfirmasi</strong> dari kami melalui WhatsApp
                            </div>
                        </div>
                    </div>
                    
                    <div class="confirmation-actions">
                        <a href="dashboard.php" class="btn-primary">Kembali ke Beranda</a>
                        <a href="keranjang.php" class="btn-secondary">Lihat Pesanan</a>
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

    <style>
        /* Confirmation Page Styles */
        .confirmation-section {
            padding: 80px 30px 50px;
            background-color: #fff;
        }

        .confirmation-container {
            max-width: 600px;
            margin: 0 auto;
            text-align: center;
        }

        .confirmation-content {
            background: white;
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
            padding: 50px 30px;
        }

        .confirmation-icon {
            width: 80px;
            height: 80px;
            background-color: rgba(40, 167, 69, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
        }

        .confirmation-icon i {
            font-size: 40px;
            color: #28a745;
        }

        .confirmation-content h2 {
            font-size: 32px;
            color: #1a5276;
            margin-bottom: 20px;
            font-weight: 700;
        }

        .confirmation-content p {
            font-size: 18px;
            color: #666;
            margin-bottom: 40px;
            line-height: 1.6;
        }

        .order-details,
        .payment-instructions {
            text-align: left;
            background-color: #f5ebd7;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 30px;
        }

        .order-details h3,
        .payment-instructions h3 {
            font-size: 20px;
            margin-bottom: 20px;
            color: #333;
            font-weight: 700;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.5);
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-row span:first-child {
            font-weight: 600;
            color: #333;
        }

        .detail-row span:last-child {
            color: #666;
        }

        .status-diproses {
            color: #e74c3c;
            font-weight: 600;
        }

        .instruction-item {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.5);
        }

        .instruction-item:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .instruction-number {
            width: 30px;
            height: 30px;
            background-color: #8b5a2b;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            flex-shrink: 0;
        }

        .instruction-text {
            flex: 1;
            text-align: left;
        }

        .instruction-text strong {
            display: block;
            color: #333;
            margin-bottom: 5px;
        }

        .instruction-text a {
            color: #8b5a2b;
            text-decoration: none;
            font-weight: 600;
        }

        .instruction-text a:hover {
            text-decoration: underline;
        }

        .confirmation-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        .confirmation-actions .btn-primary,
        .confirmation-actions .btn-secondary {
            padding: 14px 25px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 8px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .confirmation-actions .btn-primary {
            background-color: #8b5a2b;
            color: white;
        }

        .confirmation-actions .btn-primary:hover {
            background-color: #7a4e25;
            transform: translateY(-2px);
        }

        .confirmation-actions .btn-secondary {
            background-color: #f8f9fa;
            color: #8b5a2b;
            border: 2px solid #8b5a2b;
        }

        .confirmation-actions .btn-secondary:hover {
            background-color: #8b5a2b;
            color: white;
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .confirmation-content {
                padding: 40px 20px;
            }
            
            .confirmation-actions {
                flex-direction: column;
            }
            
            .confirmation-actions .btn-primary,
            .confirmation-actions .btn-secondary {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .confirmation-section {
                padding: 50px 15px 30px;
            }
            
            .confirmation-content {
                padding: 30px 20px;
            }
            
            .confirmation-content h2 {
                font-size: 26px;
            }
            
            .confirmation-content p {
                font-size: 16px;
            }
        }
    </style>
</body>
</html>
