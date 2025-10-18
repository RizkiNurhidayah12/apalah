<html>
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>POSKORIA</title>
  <!-- Import TailwindCSS via CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-stone-100 text-stone-800 font-sans">

  <!-- Navbar -->
  <header class="bg-stone-900 text-white shadow-md fixed w-full top-0 z-50">
    <div class="container mx-auto flex justify-between items-center p-4">
      <img src="logo.png" alt="Logo" class="h-20 w-20">
      <h1 class="text-2xl font-bold">Poskoria Street Coffe</h1>
      <nav class="space-x-6">
        <a href="#menu" class="hover:text-amber-400">Food & Goods</a>
        <a href="#about" class="hover:text-amber-400">Tentang</a>
        <a href="#contact" class="hover:text-amber-400">Contact</a>
        <a href="profil_admin.php" class="mr-4 hover:underline">Profil</a>
        <a href="logout.php" class="bg-red-500 hover:bg-red-600 px-4 py-2 rounded-lg">Logout</a>
      </nav>
    </div>
  </header>

  <!-- Hero Section -->
  <section class="relative bg-[url('https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=1600&q=80')] bg-cover bg-center h-[90vh] flex items-center justify-center">
    <div class="bg-black/60 p-8 rounded-xl text-center text-white mt-16">
      <h2 class="text-4xl md:text-5xl font-bold mb-4">Nikmati Kopi Jalanan yang Berkelas</h2>
      <p class="mb-6 text-lg">Dari UMKM lokal untuk pecinta kopi sejati</p>
      <a href="#menu" class="bg-amber-500 text-black px-6 py-3 rounded-full font-semibold hover:bg-amber-400">Lihat Menu</a>
    </div>
  </section>

  <!-- Menu -->
   <section id="menu" class="py-20 container mx-auto px-4"></section>
    <h3 class="text-3xl font-bold text-center mb-12">Menu Andalan</h3>
    <div class="grid md:grid-cols-3 gap-8">
      <div class="bg-white shadow-lg rounded-2xl overflow-hidden hover:scale-105 transition">
        <img src="croissant.png" alt="Espresso" class="w-full h-56 object-cover">
        <div class="p-6">
          <h4 class="font-bold text-xl mb-2">Croissant Poskoria</h4>
          <p class="text-stone-600">Roti berlapis khas Perancis dengan tekstur renyah di luar dan lembut di dalam, pas untuk teman kopi</p>
          <p class="mt-4 font-semibold text-amber-600">Rp 6.000</p>
        </div>
      </div>
      <div class="bg-white shadow-lg rounded-2xl overflow-hidden hover:scale-105 transition">
        <img src="Kopi poskoria.png" alt="Latte" class="w-full h-56 object-cover">
        <div class="p-6">
          <h4 class="font-bold text-xl mb-2">Es Kopi Poskoria</h4>
          <p class="text-stone-600">Perpaduan kopi susu khas dengan rasa manis yang pas dan segar, disajikan dingin untuk menemani waktu santai.</p>
          <p class="mt-4 font-semibold text-amber-600">Rp 15.000</p>
        </div>
      </div>
      <div class="bg-white shadow-lg rounded-2xl overflow-hidden hover:scale-105 transition">
        <img src="croffle.jpeg" alt="Cappuccino" class="w-full h-56 object-cover">
        <div class="p-6">
          <h4 class="font-bold text-xl mb-2">Croffle</h4>
          <p class="text-stone-600">Kombinasi croissant dan waffle, menghasilkan camilan dengan tekstur unik: renyah, manis, dan buttery.</p>
          <p class="mt-4 font-semibold text-amber-600">Rp 6.000</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Tentang -->
  <section id="about" class="bg-amber-50 py-20 px-4">
    <div class="container mx-auto text-center max-w-2xl">
      <h3 class="text-3xl font-bold mb-6">Tentang Poskoria</h3>
      <p class="text-stone-700 leading-relaxed">
        Poskoria adalah UMKM kopi jalanan yang menghadirkan cita rasa premium dengan harga terjangkau. 
        Kami percaya bahwa setiap cangkir kopi mampu menciptakan cerita, menghubungkan orang, dan memberikan energi positif.
      </p>
    </div>
  </section>

  <!-- Kontak -->
  <section id="contact" class="py-20 container mx-auto text-center px-4">
    <h3 class="text-3xl font-bold mb-6">For Your Information </h3>
    <a href="https://www.google.com/maps/place/Jl. Bambu Kuning-Pemda No.37 2, RT.2/RW.4, Bojong Baru, Kecamatan Bojonggede, Kabupaten Bogor, Jawa Barat 16920" 
    class="bg-blue-600 text-white px-4 py-2 rounded-lg font-semibold shadow-md hover:bg-blue-700 transition">Location</a>
    <a href="https://wa.me/6285691111136" 
    class="bg-green-500 text-white px-6 py-3 rounded-full font-semibold hover:bg-green-600">WhatsApp</a>
    <a href="https://www.instagram.com/poskoria" 
    class="bg-gradient-to-r from-purple-500 via-pink-500 to-yellow-500 text-white px-3 py-2 rounded-lg">Instagram</a>
  </section>

  <!-- Footer -->
  <footer class="bg-stone-900 text-white text-center py-6">
    <p>&copy; 2025 Poskoria Street. All rights reserved.</p>
  </footer>

</body>
</html>