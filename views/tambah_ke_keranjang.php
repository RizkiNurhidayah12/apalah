<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Pastikan pengguna sudah login
if (!isset($_SESSION['id_pelanggan'])) {
    die("Error: Anda belum login! ID pelanggan tidak ditemukan.");
}

require_once '../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_menu = intval($_POST['id_menu']);
    $jumlah = isset($_POST['jumlah']) ? intval($_POST['jumlah']) : 1; // ← bisa ambil jumlah dari form
    $id_pelanggan = $_SESSION['id_pelanggan'];

    // Ambil harga produk
    $stmt = $koneksi->prepare("SELECT harga FROM menu WHERE id_menu = ?");
    $stmt->bind_param("i", $id_menu);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        die("Error: Produk tidak ditemukan!");
    }

    $row = $result->fetch_assoc();
    $harga = $row['harga'];
    $subtotal = $harga * $jumlah;

    // Cek apakah ada pesanan 'pending' untuk pelanggan ini
    $stmt = $koneksi->prepare("SELECT id_pesanan FROM pesanan WHERE id_pelanggan = ? AND status = 'pending' LIMIT 1");
    $stmt->bind_param("i", $id_pelanggan);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $id_pesanan = $result->fetch_assoc()['id_pesanan'];
    } else {
        // Buat pesanan baru
        $stmt = $koneksi->prepare("INSERT INTO pesanan (id_pelanggan, status, tanggal_pesanan) VALUES (?, 'pending', NOW())");
        $stmt->bind_param("i", $id_pelanggan);
        $stmt->execute();
        $id_pesanan = $koneksi->insert_id;
    }

    // Cek apakah produk sudah ada di detail pesanan (jika sudah, tambahkan jumlahnya)
    $stmt = $koneksi->prepare("SELECT id_detail, jumlah, subtotal FROM detail_pesanan WHERE id_pesanan = ? AND id_menu = ?");
    $stmt->bind_param("ii", $id_pesanan, $id_menu);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $detail = $result->fetch_assoc();
        $jumlah_baru = $detail['jumlah'] + $jumlah;
        $subtotal_baru = $harga * $jumlah_baru;

        $stmt = $koneksi->prepare("UPDATE detail_pesanan SET jumlah = ?, subtotal = ? WHERE id_detail = ?");
        $stmt->bind_param("idi", $jumlah_baru, $subtotal_baru, $detail['id_detail']);
        $stmt->execute();
    } else {
        // Tambahkan produk baru ke keranjang
        $stmt = $koneksi->prepare("INSERT INTO detail_pesanan (id_pesanan, id_menu, jumlah, subtotal) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiid", $id_pesanan, $id_menu, $jumlah, $subtotal);
        $stmt->execute();
    }

    // Redirect kembali ke keranjang atau produk
    header("Location: keranjang.php?pesan=berhasil");
    exit;
}
?>
