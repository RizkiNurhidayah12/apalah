<?php
session_start();
$conn = new mysqli("localhost", "root", "", "frontend_db");

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];
$query = $conn->query("SELECT * FROM users WHERE username='$username'");
$user = $query->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Profil Pengguna - Coffee Street</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-stone-100 min-h-screen">

  <!-- Navbar -->
  <nav class="bg-brown-800 text-white px-6 py-4 flex justify-between items-center">
    <h1 class="text-xl font-bold">☕ Coffee Street</h1>
    <div>
      <a href="dashboard.php" class="mr-4 hover:underline">Dashboard</a>
      <a href="logout.php" class="bg-red-500 px-4 py-2 rounded-lg hover:bg-red-600">Logout</a>
    </div>
  </nav>

  <!-- Profil Card -->
  <div class="max-w-lg mx-auto bg-white shadow-lg rounded-xl mt-10 p-8">
    <h2 class="text-2xl font-semibold mb-6 text-center">👤 Profil Pengguna</h2>

    <form action="update_profile.php" method="POST" class="space-y-5">
      <div>
        <label class="block text-sm font-medium mb-1">Username</label>
        <input type="text" name="username" value="<?= $user['username']; ?>" readonly
          class="w-full border border-gray-300 rounded-lg p-2 bg-gray-100">
      </div>

      <div>
        <label class="block text-sm font-medium mb-1">Peran</label>
        <input type="text" name="role" value="<?= $user['role']; ?>" readonly
          class="w-full border border-gray-300 rounded-lg p-2 bg-gray-100">
      </div>

      <div>
        <label class="block text-sm font-medium mb-1">Ubah Password</label>
        <input type="password" name="new_password" placeholder="Masukkan password baru"
          class="w-full border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-brown-600">
      </div>

      <button type="submit"
        class="w-full bg-brown-700 text-white py-2 rounded-lg hover:bg-brown-800 transition">
        Simpan Perubahan
      </button>
    </form>
  </div>

</body>
</html>
