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
    header("Location: admin.php");
    exit;
}

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
    
    header("Location: admin.php");
    exit;
    
} catch (Exception $e) {
    $_SESSION['admin_message'] = [
        'type' => 'error',
        'text' => 'Gagal menghapus produk: ' . $e->getMessage()
    ];
    header("Location: admin.php");
    exit;
}
?>
