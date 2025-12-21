<?php
session_start();

// Cek session login dan role admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

require_once '../config/koneksi.php';

// Ambil ID produk dari URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: kelola_stok.php");
    exit;
}

$id_menu = intval($_GET['id']);

// Ambil data produk
$query_produk = "SELECT * FROM menu WHERE id_menu = ?";
$stmt_produk = $koneksi->prepare($query_produk);
$stmt_produk->bind_param("i", $id_menu);
$stmt_produk->execute();
$result_produk = $stmt_produk->get_result();

if ($result_produk->num_rows === 0) {
    header("Location: kelola_stok.php");
    exit;
}

$produk = $result_produk->fetch_assoc();

// Handle form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit') {
    $nama_menu = $_POST['nama_menu'] ?? '';
    $harga = $_POST['harga'] ?? 0;
    $deskripsi = $_POST['deskripsi'] ?? '';
    
    // Validasi input
    if (empty($nama_menu) || $harga <= 0) {
        $_SESSION['admin_message'] = [
            'type' => 'error',
            'text' => 'Nama produk dan harga wajib diisi!'
        ];
        header("Location: edit_produk.php?id=" . $id_menu);
        exit;
    }
    
    try {
        $query = "UPDATE menu SET nama_menu = '$nama_menu', harga = $harga, deskripsi = '$deskripsi' WHERE id_menu = $id_menu";
        $koneksi->query($query);
        
        $_SESSION['admin_message'] = [
            'type' => 'success',
            'text' => 'Produk berhasil diperbarui!'
        ];
        
        header("Location: kelola_stok.php");
        exit;
        
    } catch (Exception $e) {
        $_SESSION['admin_message'] = [
            'type' => 'error',
            'text' => 'Gagal memperbarui produk: ' . $e->getMessage()
        ];
        header("Location: edit_produk.php?id=" . $id_menu);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Poskoria - Edit Produk</title>
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
            <h1>Edit Produk</h1>
            
            <!-- Edit Produk Form -->
            <div class="edit-product-form">
                <form method="POST">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id_menu" value="<?= $produk['id_menu'] ?>">
                    
                    <div class="form-group">
                        <label for="nama_menu">Edit Nama Produk</label>
                        <input type="text" id="nama_menu" name="nama_menu" 
                               value="<?= htmlspecialchars($produk['nama_menu']) ?>" 
                               placeholder="Masukkan nama produk" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="harga">Edit Jumlah</label>
                        <input type="number" id="harga" name="harga" 
                               value="<?= $produk['harga'] ?>" 
                               placeholder="Masukkan jumlah" min="0" step="1000" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="deskripsi">Edit Deskripsi</label>
                        <textarea id="deskripsi" name="deskripsi" 
                                  placeholder="Masukkan deskripsi produk" 
                                  rows="4"><?= htmlspecialchars($produk['deskripsi']) ?></textarea>
                    </div>
                    
                    <button type="submit" class="btn-primary">Edit Produk</button>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
