
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login - Coffee Street</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center min-h-screen bg-stone-100">

  <div class="bg-white shadow-lg rounded-2xl w-full max-w-md p-8">
    <h2 class="text-3xl font-bold text-center text-stone-800 mb-6">☕ Poskoria Page</h2>
    <p class="text-center text-stone-500 mb-8">Silakan login untuk melanjutkan</p>

    <!-- Form Login -->
    <form action="proses_login.php" method="POST" class="space-y-6">
      <!-- Username -->
      <div>
        <label for="username" class="block text-sm font-medium text-stone-700">Username</label>
        <input type="text" id="username" name="username" required
          class="mt-1 w-full px-4 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
      </div>

      <!-- Password -->
      <div>
        <label for="password" class="block text-sm font-medium text-stone-700">Password</label>
        <input type="password" id="password" name="password" required
          class="mt-1 w-full px-4 py-2 border border-stone-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
      </div>

      <!-- Tombol Login -->
      <button type="submit"
        class="w-full bg-amber-500 text-black font-semibold py-2 px-4 rounded-lg hover:bg-amber-400 transition">
        Login
      </button>
    </form>

    <!-- Link tambahan -->
    <p class="text-center text-sm text-stone-500 mt-6">
      Belum punya akun? <a href="register.php" class="text-amber-600 hover:underline">Daftar</a>
    </p>
  </div>

</body>
</html>
