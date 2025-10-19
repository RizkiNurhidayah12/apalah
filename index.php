<a href="produk_detail.php?id=<?= $row['id_produk']; ?>" 
   class="bg-brown-700 text-white px-4 py-2 rounded hover:bg-brown-800">
   Lihat Detail
</a>
<?php
session_start();
$page = $_GET['page'] ?? 'home';

// daftar halaman valid
$validPages = ['dashboard', 'produk', 'keranjang', 'checkout', 'login'];

// cek whitelist
if (in_array($page, $validPages)) {
    switch ($page) {
        case 'dashboard':
            include '../views/dashboard.php';
            break;
        case 'produk':
            include 'controllers/produkController.php';
            break;
        case 'keranjang':
            include '../views/keranjang.php';
            break;
        case 'checkout':
            include '../views/checkout.php';
            break;
        case 'login':
            include '../views/login.php';
            break;
    }
} else {
    include 'views/dashboard.php';
}
