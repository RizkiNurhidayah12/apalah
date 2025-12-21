<?php
session_start();

// Cek session login dan role admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

require_once '../config/koneksi.php';

// Ambil data laporan: SATU BARIS = SATU PRODUK dalam pesanan
$query_laporan = "
    SELECT 
        p.id_pesanan, 
        p.tanggal_pesanan, 
        p.status, 
        p.metode_bayar,
        pel.nama as nama_pelanggan,
        m.nama_menu as nama_produk,
        dp.jumlah,
        dp.subtotal
    FROM pesanan p
    JOIN pelanggan pel ON p.id_pelanggan = pel.id_pelanggan
    JOIN detail_pesanan dp ON p.id_pesanan = dp.id_pesanan
    JOIN menu m ON dp.id_menu = m.id_menu
    ORDER BY p.tanggal_pesanan DESC, p.id_pesanan DESC
";

$stmt_laporan = $koneksi->prepare($query_laporan);
$stmt_laporan->execute();
$result_laporan = $stmt_laporan->get_result();
$laporan_list = [];
while ($row = $result_laporan->fetch_assoc()) {
    $laporan_list[] = $row;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Poskoria - Laporan</title>
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
            <h1>Laporan</h1>
            
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr class="table-header">
                            <th>ID Pesanan</th>
                            <th>Nama Pelanggan</th>
                            <th>Nama Produk</th>
                            <th>Jumlah</th>
                            <th>Subtotal</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($laporan_list)): ?>
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada laporan</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($laporan_list as $laporan): ?>
                                <tr class="table-row">
                                    <td><?= $laporan['id_pesanan'] ?></td>
                                    <td><?= htmlspecialchars($laporan['nama_pelanggan']) ?></td>
                                    <td><?= htmlspecialchars($laporan['nama_produk']) ?></td>
                                    <td><?= $laporan['jumlah'] ?></td>
                                    <td>Rp <?= number_format($laporan['subtotal'], 0, ',', '.') ?></td>
                                    <td><?= date('d M Y H:i', strtotime($laporan['tanggal_pesanan'])) ?></td>
                                    <td>
                                        <?php 
                                        $status = $laporan['status'];
                                        $statusClass = $status === 'diproses' ? 'status-approved' : 
                                                      ($status === 'pending' ? 'status-pending' : 'status-rejected');
                                        $statusText = $status === 'diproses' ? 'Approved' : 
                                                     ($status === 'pending' ? 'Pending' : 'Rejected');
                                        ?>
                                        <span class="<?= $statusClass ?>"><?= $statusText ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>
