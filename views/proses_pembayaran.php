<?php
session_start();

// Cek session login
if (!isset($_SESSION['username']) || !isset($_SESSION['id_pelanggan'])) {
    header("Location: login.php");
    exit;
}

require_once '../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_pesanan = intval($_POST['id_pesanan']);
    $nama = $_POST['nama'] ?? '';
    $no_hp = $_POST['no_hp'] ?? '';
    $alamat = $_POST['alamat'] ?? '';
    $metode_bayar = $_POST['metode_bayar'] ?? 'qris';
    
    // Validasi metode pembayaran hanya QRIS atau Cash
    $valid_metode = ['qris', 'cash'];
    if (!in_array($metode_bayar, $valid_metode)) {
        $metode_bayar = 'qris';
    }
    
    $id_pelanggan = $_SESSION['id_pelanggan'];
    
    try {
        // Update data pelanggan jika diperlukan
        $query_update_pelanggan = "UPDATE pelanggan SET nama = ?, no_hp = ?, alamat = ? WHERE id_pelanggan = ?";
        $stmt_update = $koneksi->prepare($query_update_pelanggan);
        $stmt_update->bind_param("sssi", $nama, $no_hp, $alamat, $id_pelanggan);
        $stmt_update->execute();
        
        // Update status pesanan dan metode pembayaran
        $query_update_pesanan = "UPDATE pesanan SET status = 'diproses', metode_bayar = ? WHERE id_pesanan = ?";
        $stmt_update_pesanan = $koneksi->prepare($query_update_pesanan);
        $stmt_update_pesanan->bind_param("si", $metode_bayar, $id_pesanan);
        $stmt_update_pesanan->execute();
        
        // Redirect ke halaman konfirmasi
        header("Location: konfirmasi_pembayaran.php?id_pesanan=" . $id_pesanan);
        exit;
        
    } catch (Exception $e) {
        error_log("Error processing payment: " . $e->getMessage());
        $_SESSION['cart_message'] = [
            'type' => 'error',
            'text' => 'Gagal memproses pembayaran: ' . $e->getMessage()
        ];
        header("Location: checkout.php");
        exit;
    }
}
?>
