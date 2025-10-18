<?php
session_start();
$conn = new mysqli("localhost", "root", "", "frontend_db");

// Cek login dan role admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
  header("Location: login.php");
  exit;
}

// Tambah Produk
if (isset($_POST['tambah'])) {
  $nama = $_POST['nama_produk'];
  $kategori = $_POST['kategori'];
  $harga = $_POST['harga'];
  $stok = $_POST['stok'];
  $deskripsi = $_POST['deskripsi'];

  // Upload gambar
  $gambar = '';
  if (!empty($_FILES['gambar']['name'])) {
    $target = "uploads/" . basename($_FILES['gambar']['name']);
    move_uploaded_file($_FILES['gambar']['tmp_name'], $target);
    $gambar = $_FILES['gambar']['name'];
  }

  $conn->query("INSERT INTO produk (nama_produk, kategori, harga, stok, deskripsi, gambar)
                VALUES ('$nama','$kategori','$harga','$stok','$deskripsi','$gambar')");
  header("Location: admin_produk.php");
  exit;
}

// Hapus Produk
if (isset($_GET['hapus'])) {
  $id = $_GET['hapus'];
  $conn->query("DELETE FROM produk WHERE id_produk=$id");
  header("Location: admin_produk.php");
  exit;
}

// Edit Produk
if (isset($_POST['update'])) {
  $id = $_POST['id_produk'];
  $nama = $_POST['nama_produk'];
  $kategori = $_POST['kategori'];
  $harga = $_POST['harga'];
  $stok = $_POST['stok'];
  $deskripsi = $_POST['deskripsi'];

  // Cek apakah ada gambar baru
  if (!empty($_FILES['gambar']['name'])) {
    $target = "uploads/" . basename($_FILES['gambar']['name']);
    move_uploaded_file($_FILES['gambar']['tmp_name'], $target);
    $gambar = $_FILES['gambar']['name'];
    $conn->query("UPDATE produk SET nama_produk='$nama', kategori='$kategori', harga='$harga', stok='$stok', deskripsi='$deskripsi', gambar='$gambar' WHERE id_produk=$id");
  } else {
    $conn->query("UPDATE produk SET nama_produk='$nama', kategori='$kategori', harga='$harga', stok='$stok', deskripsi='$deskripsi' WHERE id_produk=$id");
  }
  header("Location: admin_produk.php");
  exit;
}

$produk = $conn->query("SELECT * FROM produk ORDER BY id_produk DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Poskoria's admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-stone-100 p-8">

  <h1 class="text-3xl font-bold mb-6 text-center text-brown-800">Poskoria - Kelola Produk</h1>

  <!-- Form Tambah Produk -->
  <div class="max-w-3xl mx-auto bg-white shadow-lg rounded-xl p-6 mb-10">
    <h2 class="text-xl font-semibold mb-4">Tambah Produk Baru</h2>
    <form method="POST" enctype="multipart/form-data" class="grid grid-cols-2 gap-4">
      <input type="text" name="nama_produk" placeholder="Nama Produk" class="border p-2 rounded" required>
      <input type="text" name="kategori" placeholder="Kategori (Kopi, Snack, dll)" class="border p-2 rounded" required>
      <input type="number" name="harga" placeholder="Harga" class="border p-2 rounded" required>
      <input type="number" name="stok" placeholder="Stok" class="border p-2 rounded" required>
      <textarea name="deskripsi" placeholder="Deskripsi" class="col-span-2 border p-2 rounded"></textarea>
      <input type="file" name="gambar" class="col-span-2 border p-2 rounded">
      <button type="submit" name="tambah" class="col-span-2 bg-green-600 text-white py-2 rounded hover:bg-green-700">Tambah Produk</button>
    </form>
  </div>

  <!-- Tabel Produk -->
  <div class="max-w-6xl mx-auto bg-white shadow-lg rounded-xl p-6">
    <h2 class="text-xl font-semibold mb-4">Daftar Produk</h2>
    <table class="min-w-full border-collapse border border-gray-300 text-sm">
      <thead class="bg-gray-200">
        <tr>
          <th class="border p-2">#</th>
          <th class="border p-2">Gambar</th>
          <th class="border p-2">Nama Produk</th>
          <th class="border p-2">Kategori</th>
          <th class="border p-2">Harga</th>
          <th class="border p-2">Stok</th>
          <th class="border p-2">Deskripsi</th>
          <th class="border p-2">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php $no = 1; while ($row = $produk->fetch_assoc()) { ?>
        <tr class="hover:bg-gray-100">
          <td class="border p-2 text-center"><?= $no++; ?></td>
          <td class="border p-2 text-center">
            <?php if ($row['gambar']) { ?>
              <img src="uploads/<?= $row['gambar']; ?>" class="h-16 w-16 object-cover mx-auto rounded">
            <?php } else { ?>
              <span class="text-gray-400 italic">-</span>
            <?php } ?>
          </td>
          <td class="border p-2"><?= $row['nama_produk']; ?></td>
          <td class="border p-2"><?= $row['kategori']; ?></td>
          <td class="border p-2 text-right">Rp<?= number_format($row['harga']); ?></td>
          <td class="border p-2 text-center"><?= $row['stok']; ?></td>
          <td class="border p-2"><?= $row['deskripsi']; ?></td>
          <td class="border p-2 text-center space-x-1">
            <button onclick="editProduk(<?= htmlspecialchars(json_encode($row)); ?>)" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">Edit</button>
            <a href="?hapus=<?= $row['id_produk']; ?>" onclick="return confirm('Yakin ingin hapus produk ini?')" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">Hapus</a>
          </td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>

  <!-- Modal Edit Produk -->
  <div id="modalEdit" class="hidden fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center">
    <div class="bg-white p-6 rounded-lg w-[400px]">
      <h3 class="text-lg font-semibold mb-4">Edit Produk</h3>
      <form method="POST" enctype="multipart/form-data" id="formEdit">
        <input type="hidden" name="id_produk" id="edit_id">
        <input type="text" name="nama_produk" id="edit_nama" class="border p-2 w-full rounded mb-2">
        <input type="text" name="kategori" id="edit_kategori" class="border p-2 w-full rounded mb-2">
        <input type="number" name="harga" id="edit_harga" class="border p-2 w-full rounded mb-2">
        <input type="number" name="stok" id="edit_stok" class="border p-2 w-full rounded mb-2">
        <textarea name="deskripsi" id="edit_deskripsi" class="border p-2 w-full rounded mb-2"></textarea>
        <input type="file" name="gambar" class="border p-2 w-full rounded mb-3">
        <div class="flex justify-end space-x-2">
          <button type="button" onclick="closeModal()" class="bg-gray-400 text-white px-4 py-1 rounded">Batal</button>
          <button type="submit" name="update" class="bg-blue-600 text-white px-4 py-1 rounded hover:bg-blue-700">Update</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    function editProduk(data) {
      document.getElementById('modalEdit').classList.remove('hidden');
      document.getElementById('edit_id').value = data.id_produk;
      document.getElementById('edit_nama').value = data.nama_produk;
      document.getElementById('edit_kategori').value = data.kategori;
      document.getElementById('edit_harga').value = data.harga;
      document.getElementById('edit_stok').value = data.stok;
      document.getElementById('edit_deskripsi').value = data.deskripsi;
    }
    function closeModal() {
      document.getElementById('modalEdit').classList.add('hidden');
    }
  </script>

</body>
</html>
