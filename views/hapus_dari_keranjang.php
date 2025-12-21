<?php
session_start();

// Cek session login
if (!isset($_SESSION['username']) || !isset($_SESSION['id_pelanggan'])) {
    header("Location: login.php");
    exit;
}

require_once '../config/koneksi.php';

// Validasi data POST
if (!isset($_POST['id_detail']) || !is_numeric($_POST['id_detail'])) {
    $_SESSION['cart_message'] = [
        'type' => 'error',
        'text' => 'ID produk tidak valid'
    ];
    header("Location: keranjang.php");
    exit;
}

$id_detail = intval($_POST['id_detail']);
$id_pelanggan = $_SESSION['id_pelanggan'];

try {
    // Ambil data detail pesanan untuk memastikan kepemilikan
    $query_check = "SELECT dp.id_detail, dp.id_pesanan, p.id_pelanggan 
                    FROM detail_pesanan dp 
                    JOIN pesanan p ON dp.id_pesanan = p.id_pesanan 
                    WHERE dp.id_detail = ? AND p.id_pelanggan = ?";
    $stmt_check = $koneksi->prepare($query_check);
    $stmt_check->bind_param("ii", $id_detail, $id_pelanggan);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    
    if ($result_check->num_rows === 0) {
        throw new Exception("Produk tidak ditemukan atau bukan milik Anda");
    }
    
    // Hapus dari database
    $query_delete = "DELETE FROM detail_pesanan WHERE id_detail = ?";
    $stmt_delete = $koneksi->prepare($query_delete);
    $stmt_delete->bind_param("i", $id_detail);
    
    if (!$stmt_delete->execute()) {
        throw new Exception("Gagal menghapus produk dari keranjang");
    }
    
    // Periksa apakah masih ada produk di pesanan
    $query_check_empty = "SELECT COUNT(*) as count FROM detail_pesanan WHERE id_pesanan = ?";
    $stmt_check_empty = $koneksi->prepare($query_check_empty);
    $stmt_check_empty->bind_param("i", $result_check->fetch_assoc()['id_pesanan']);
    $stmt_check_empty->execute();
    $result_empty = $stmt_check_empty->get_result();
    $count = $result_empty->fetch_assoc()['count'];
    
    // Jika tidak ada produk lagi, hapus pesanan juga
    if ($count == 0) {
        $query_delete_pesanan = "DELETE FROM pesanan WHERE id_pesanan = ?";
        $stmt_delete_pesanan = $koneksi->prepare($query_delete_pesanan);
        $stmt_delete_pesanan->bind_param("i", $result_check->fetch_assoc()['id_pesanan']);
        $stmt_delete_pesanan->execute();
    }
    
    $_SESSION['cart_message'] = [
        'type' => 'success',
        'text' => 'Produk berhasil dihapus dari keranjang'
    ];
    
    header("Location: keranjang.php");
    exit;
    
} catch (Exception $e) {
    error_log("Error removing from cart: " . $e->getMessage());
    $_SESSION['cart_message'] = [
        'type' => 'error',
        'text' => 'Gagal menghapus produk: ' . $e->getMessage()
    ];
    header("Location: keranjang.php");
    exit;
}
?>