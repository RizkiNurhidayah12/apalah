<?php
session_start();

// Cek session login dan role admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

require_once '../config/koneksi.php';

// Ambil data statistik
$query_total_produk = "SELECT COUNT(*) as total FROM menu";
$stmt_total = $koneksi->prepare($query_total_produk);
$stmt_total->execute();
$result_total = $stmt_total->get_result();
$total_produk = $result_total->fetch_assoc()['total'];

// Hitung permintaan menunggu (pesanan dengan status 'pending')
$query_permintaan_menunggu = "SELECT COUNT(*) as menunggu FROM pesanan WHERE status = 'pending'";
$stmt_menunggu = $koneksi->prepare($query_permintaan_menunggu);
$stmt_menunggu->execute();
$result_menunggu = $stmt_menunggu->get_result();
$permintaan_menunggu = $result_menunggu->fetch_assoc()['menunggu'];

// Hitung permintaan disetujui (pesanan dengan status 'diproses')
$query_permintaan_disetujui = "SELECT COUNT(*) as disetujui FROM pesanan WHERE status = 'diproses'";
$stmt_disetujui = $koneksi->prepare($query_permintaan_disetujui);
$stmt_disetujui->execute();
$result_disetujui = $stmt_disetujui->get_result();
$permintaan_disetujui = $result_disetujui->fetch_assoc()['disetujui'];

// Ambil daftar produk
$query_produk = "SELECT * FROM menu ORDER BY id_menu DESC LIMIT 5";
$stmt_produk = $koneksi->prepare($query_produk);
$stmt_produk->execute();
$result_produk = $stmt_produk->get_result();
$produk_list = [];
while ($row = $result_produk->fetch_assoc()) {
    $produk_list[] = $row;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Poskoria - Dashboard</title>
    <link rel="stylesheet" href="../assets/css/js/gambar/input.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="admin-body">
    <!-- Sidebar -->
    <div class="admin-sidebar">
        <div class="sidebar-header">
            <h2>Admin Poskoria</h2>
        </div>
        
        <nav class="sidebar-nav">
            <ul>
                <li><a href="admin.php"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="kelola_stok.php"><i class="fas fa-boxes"></i> Kelola Stok</a></li>
                <li><a href="laporan.php"><i class="fas fa-book"></i> Laporan</a></li>
            </ul>
        </nav>
        
        <div class="sidebar-footer">
            <p>Selamat Datang, Admin!</p>
            <a href="logout.php" class="logout-link">Logout</a>
        </div>
    </div>

    <!-- Main Content -->
    <main class="admin-main">
        <div class="admin-container">
            <h1>Selamat datang, admin!</h1>
            
            <!-- Statistik Cards -->
            <div class="stats-cards">
                <div class="stat-card">
                    <h3>Total Produk</h3>
                    <div class="stat-value"><?= $total_produk ?></div>
                </div>
                
                <div class="stat-card">
                    <h3>Permintaan Menunggu</h3>
                    <div class="stat-value"><?= $permintaan_menunggu ?></div>
                </div>
                
                <div class="stat-card">
                    <h3>Permintaan Disetujui</h3>
                    <div class="stat-value"><?= $permintaan_disetujui ?></div>
                </div>
            </div>
            
            <!-- Daftar Produk -->
            <div class="product-list-section">
                <h2>Daftar Produk</h2>
                
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr class="table-header">
                                <th>Id</th>
                                <th>Nama Produk</th>
                                <th>Deskripsi</th>
                                <th>Stok</th>
                                <th>Gambar</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($produk_list)): ?>
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada produk</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($produk_list as $produk): ?>
                                    <tr class="table-row">
                                        <td><?= $produk['id_menu'] ?></td>
                                        <td><?= htmlspecialchars($produk['nama_menu']) ?></td>
                                        <td><?= htmlspecialchars(substr($produk['deskripsi'], 0, 50)) ?>...</td>
                                        <td><?= $produk['harga'] ?></td>
                                        <td>
                                            <?php if (!empty($produk['gambar'])): ?>
                                                <img src="<?= htmlspecialchars($produk['gambar']) ?>" alt="<?= htmlspecialchars($produk['nama_menu']) ?>" class="product-image-admin">
                                            <?php else: ?>
                                                <span>-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="edit_produk.php?id=<?= $produk['id_menu'] ?>" class="btn-edit">Edit</a>
                                            <a href="hapus_produk.php?id=<?= $produk['id_menu'] ?>" 
                                               class="btn-delete" 
                                               onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">
                                                Hapus
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
