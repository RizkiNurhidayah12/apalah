<?php
session_start();

// Cek session login dan role admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

require_once '../config/koneksi.php';

// Ambil daftar produk
$query_produk = "SELECT * FROM menu ORDER BY id_menu DESC";
$stmt_produk = $koneksi->prepare($query_produk);
$stmt_produk->execute();
$result_produk = $stmt_produk->get_result();
$produk_list = [];
while ($row = $result_produk->fetch_assoc()) {
    $produk_list[] = $row;
}

// Handle form tambah produk
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'tambah') {
    $nama_menu = $_POST['nama_menu'] ?? '';
    $harga = $_POST['harga'] ?? 0;
    $deskripsi = $_POST['deskripsi'] ?? '';

    // Proses upload gambar
    $gambar_path = null;
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../uploads/';
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $max_size = 5 * 1024 * 1024; // 5MB
        
        // Cek ekstensi file
        $file_type = $_FILES['gambar']['type'];
        $file_size = $_FILES['gambar']['size'];
        $file_name = $_FILES['gambar']['name'];

        if (!in_array($file_type, $allowed_types)) {
            $_SESSION['admin_message'] = [
                'type' => 'error',
                'text' => 'Tipe file tidak didukung. Hanya JPG, PNG, atau GIF yang diperbolehkan.'
            ];
            header("Location: kelola_stok.php");
            exit;
        }

        if ($file_size > $max_size) {
            $_SESSION['admin_message'] = [
                'type' => 'error',
                'text' => 'Ukuran file terlalu besar. Maksimal 5MB.'
            ];
            header("Location: kelola_stok.php");
            exit;
        }

        // Buat nama file unik
        $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
        $unique_name = uniqid() . '.' . $file_extension;
        $target_path = $upload_dir . $unique_name;

        // Pindahkan file
        if (!move_uploaded_file($_FILES['gambar']['tmp_name'], $target_path)) {
            $_SESSION['admin_message'] = [
                'type' => 'error',
                'text' => 'Gagal mengupload gambar.'
            ];
            header("Location: kelola_stok.php");
            exit;
        }

        $gambar_path = $target_path;
    }

    // Validasi input
    if (empty($nama_menu) || $harga <= 0) {
        $_SESSION['admin_message'] = [
            'type' => 'error',
            'text' => 'Nama produk dan harga wajib diisi!'
        ];
        header("Location: kelola_stok.php");
        exit;
    }

    try {
        // Insert produk baru
        $stmt_insert = $koneksi->prepare("INSERT INTO menu (nama_menu, harga, deskripsi, gambar) VALUES (?, ?, ?, ?)");
        $stmt_insert->bind_param("sdss", $nama_menu, $harga, $deskripsi, $gambar_path);
        $stmt_insert->execute();

        $_SESSION['admin_message'] = [
            'type' => 'success',
            'text' => 'Produk berhasil ditambahkan!'
        ];

        header("Location: kelola_stok.php");
        exit;

    } catch (Exception $e) {
        $_SESSION['admin_message'] = [
            'type' => 'error',
            'text' => 'Gagal menambahkan produk: ' . $e->getMessage()
        ];
        header("Location: kelola_stok.php");
        exit;
    }
}

// Handle form edit produk
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit') {
    $id_menu = intval($_POST['id_menu']);
    $nama_menu = $_POST['nama_menu'] ?? '';
    $harga = $_POST['harga'] ?? 0;
    $deskripsi = $_POST['deskripsi'] ?? '';

    // Validasi input
    if (empty($nama_menu) || $harga <= 0) {
        $_SESSION['admin_message'] = [
            'type' => 'error',
            'text' => 'Nama produk dan harga wajib diisi!'
        ];
        header("Location: kelola_stok.php");
        exit;
    }

    // Proses upload gambar jika ada
    $gambar_path = null;
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../uploads/';
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $max_size = 5 * 1024 * 1024; // 5MB

        $file_type = $_FILES['gambar']['type'];
        $file_size = $_FILES['gambar']['size'];
        $file_name = $_FILES['gambar']['name'];

        if (!in_array($file_type, $allowed_types)) {
            $_SESSION['admin_message'] = [
                'type' => 'error',
                'text' => 'Tipe file tidak didukung. Hanya JPG, PNG, atau GIF yang diperbolehkan.'
            ];
            header("Location: kelola_stok.php");
            exit;
        }

        if ($file_size > $max_size) {
            $_SESSION['admin_message'] = [
                'type' => 'error',
                'text' => 'Ukuran file terlalu besar. Maksimal 5MB.'
            ];
            header("Location: kelola_stok.php");
            exit;
        }

        $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
        $unique_name = uniqid() . '.' . $file_extension;
        $target_path = $upload_dir . $unique_name;

        if (!move_uploaded_file($_FILES['gambar']['tmp_name'], $target_path)) {
            $_SESSION['admin_message'] = [
                'type' => 'error',
                'text' => 'Gagal mengupload gambar.'
            ];
            header("Location: kelola_stok.php");
            exit;
        }

        $gambar_path = $target_path;
    }

    try {
        // Update produk
        if ($gambar_path) {
            $stmt_update = $koneksi->prepare("UPDATE menu SET nama_menu = ?, harga = ?, deskripsi = ?, gambar = ? WHERE id_menu = ?");
            $stmt_update->bind_param("sissi", $nama_menu, $harga, $deskripsi, $gambar_path, $id_menu);
        } else {
            $stmt_update = $koneksi->prepare("UPDATE menu SET nama_menu = ?, harga = ?, deskripsi = ? WHERE id_menu = ?");
            $stmt_update->bind_param("sisi", $nama_menu, $harga, $deskripsi, $id_menu);
        }
        $stmt_update->execute();

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
        header("Location: kelola_stok.php");
        exit;
    }
}

// Handle hapus produk
if (isset($_GET['action']) && $_GET['action'] === 'hapus' && isset($_GET['id'])) {
    $id_menu = intval($_GET['id']);

    try {
        // Hapus produk
        $stmt_delete = $koneksi->prepare("DELETE FROM menu WHERE id_menu = ?");
        $stmt_delete->bind_param("i", $id_menu);
        $stmt_delete->execute();

        $_SESSION['admin_message'] = [
            'type' => 'success',
            'text' => 'Produk berhasil dihapus!'
        ];

        header("Location: kelola_stok.php");
        exit;

    } catch (Exception $e) {
        $_SESSION['admin_message'] = [
            'type' => 'error',
            'text' => 'Gagal menghapus produk: ' . $e->getMessage()
        ];
        header("Location: kelola_stok.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Poskoria - Kelola Stok</title>
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
            <h1>Kelola Stok</h1>

            <!-- Tambah Produk Form -->
            <div class="add-product-form">
                <h2>Tambah Produk</h2>
                
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="tambah">
                    
                    <div class="form-group">
                        <label for="nama_menu">Nama Produk</label>
                        <input type="text" id="nama_menu" name="nama_menu" placeholder="Masukkan nama produk" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="harga">Harga</label>
                        <input type="number" id="harga" name="harga" placeholder="Masukkan harga" min="0" step="1000" required>
                    </div>

                    <div class="form-group">
                        <label for="gambar">Gambar</label>
                        <input type="file" id="gambar" name="gambar" accept="image/*" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="deskripsi">Deskripsi</label>
                        <textarea id="deskripsi" name="deskripsi" placeholder="Masukkan deskripsi produk" rows="4"></textarea>
                    </div>
                    
                    <button type="submit" class="btn-primary">Tambah Produk</button>
                </form>
            </div>

            <!-- Daftar Produk -->
            <div class="product-list-section">
                <h2>Daftar Produk</h2>

                <table class="product-table">
                    <thead>
                        <tr class="table-header">
                            <th>Id</th>
                            <th>Nama Produk</th>
                            <th>Deskripsi</th>
                            <th>Harga</th>
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
                                    <td>Rp <?= number_format($produk['harga'], 0, ',', '.') ?></td>
                                    <td>
                                        <?php if (!empty($produk['gambar'])): ?>
                                            <img src="<?= htmlspecialchars($produk['gambar']) ?>" alt="<?= htmlspecialchars($produk['nama_menu']) ?>" class="product-image-admin">
                                        <?php else: ?>
                                            <span>-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="edit_produk.php?id=<?= $produk['id_menu'] ?>" class="btn-edit">Edit</a>
                                        <a href="kelola_stok.php?action=hapus&id=<?= $produk['id_menu'] ?>"
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
    </main>
</body>
</html>
