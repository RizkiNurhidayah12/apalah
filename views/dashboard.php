<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Poskoria Street Coffee</title>
    <link rel="stylesheet" href="../assets/css/js/gambar/input.css">
</head>
<body class="dashboard-body">
    <!-- Header -->
<header class="header" id="header">
    <!-- Kelompok kiri: Hamburger + Logo -->
    <div class="header-start" style="display: flex; align-items: center; gap: 12px;">
        <div class="hamburger-menu" id="hamburgerMenu">
            <div class="hamburger-line"></div>
            <div class="hamburger-line"></div>
            <div class="hamburger-line"></div>
        </div>
        <div >
            <img src="../assets/css/js/gambar/logo.png" alt="Poskoria Logo" class="logo">
        </div>
    </div>
    
    <!-- Navigasi Desktop -->
    <nav class="nav-menu">
        <ul>
            <li><a href="../views/dashboard.php">Beranda</a></li>
            <li><a href="../views/semua_produk.php" class="active">Produk</a></li>
            <li><a href="../views/dashboard.php#tentang-kami">Tentang Kami</a></li>
            <li><a href="../views/dashboard.php#kontak">Kontak</a></li>
            <li><a href="#">Lainnya</a></li>
        </ul>
    </nav>
    
    <!-- Ikon Kanan -->
    <div class="header-icons">
        <a href="../views/keranjang.php">
            <div class="cart-icon">🛒</div>
        </a>
        <div class="user-icon">👤</div>
    </div>
</header>

    <!-- Mobile Navigation Menu -->
    <div class="mobile-nav" id="mobileNav">
        <span class="mobile-nav-close" id="closeMobileNav">&times;</span>
        <ul>
            <li><a href="#">Beranda</a></li>
            <li><a href="semua_produk.php">Produk</a></li>
            <li><a href="#tentang-kami">Tentang Kami</a></li>
            <li><a href="#kontak">Kontak</a></li>
            <li><a href="#">Lainnya</a></li>
        </ul>
    </div>

    <!-- Overlay -->
    <div class="overlay" id="overlay"></div>

    <main>
        <section class="hero">
            <div class="hero-content">
                <h1>Es Kopi Poskoria – Segarnya Bikin Ketagihan!</h1>
                <p>Dibuat dari biji kopi pilihan dengan racikan spesial yang bikin hari-harimu lebih semangat</p>
            </div>
        </section>

        <section class="menu-section">
            <h2>Menu Favorit</h2>
            <div class="menu-grid">
                <div class="menu-item">
                    <img src="../uploads/kopi poskoria.png" alt="Es Kopi Poskoria">
                    <div class="menu-info">
                        <span class="category">Minuman</span>
                        <h3>Es Kopi Poskoria</h3>
                        <p>Es kopi signature dengan racikan spesial Poskoria, segar dan bikin ketagihan!</p>
                        <div class="price-action">
                            <span class="price">Rp 15.000</span>
                            <div class="action-buttons">
                                <button class="heart-btn">♥</button>
                                <button class="buy-btn">Beli</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="menu-item">
                    <img src="../uploads/nasi pus.png" alt="Nasi Pus">
                    <div class="menu-info">
                        <span class="category">Makanan</span>
                        <h3>Nasi Pus</h3>
                        <p>Nasi porsi kecil dengan lauk di dalam nya. Dijamin bikin ketagihan!</p>
                        <div class="price-action">
                            <span class="price">Rp 5.000</span>
                            <div class="action-buttons">
                                <button class="heart-btn">♥</button>
                                <button class="buy-btn">Beli</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="tentang-kami" class="about-section">
            <h2 href="#tentang-kami">Tentang Poskoria Street Coffee</h2>
            <p>Street ini berdiri sejak 2018, Konsepnya tempat nongkrong santai dengan menu kopi & cemilan . yang dimana tempat street kopi rasanya ga kalah jauh sama kopi kopi di tempat CoffeShop , tapi rasa dan nuansanya harus tetep street .</p>
        </section>

        <section id="kontak" class="contact-section">
            <h2>Kontak Kami</h2>
            <div class="contact-info">
                <div class="contact-item">
                    <span>📞</span>
                    <span>085691111136</span>
                </div>
                <div class="contact-item">
                    <span>📸</span>
                    <span>@poskoria</span>
                </div>
                <div class="contact-item">
                    <span>📍</span>
                    <span>Jl. Merdeka Raya, Abadijaya, Kec. Cipayung, Kota Depok, Jawa Barat 16417</span>
                </div>
            </div>
            <div class="map">
                <img src="" alt="Map Location">
            </div>
        </section>

        <section class="testimonials-section">
            <h2>Testimoni Pelanggan</h2>
            <div class="testimonials-grid">
                <div class="testimonial-card">
                    <div class="customer-info">
                        <img src="" alt="Joko">
                        <div>
                            <h3>Joko</h3>
                            <div class="rating">⭐⭐⭐⭐☆</div>
                        </div>
                    </div>
                    <p>"Es Kopi Poskoria nya pas rasanya, nggak terlalu pahit dan harganya cocok untuk kantong mahasiswa."</p>
                </div>
                <div class="testimonial-card">
                    <div class="customer-info">
                        <img src="" alt="Riska">
                        <div>
                            <h3>Riska</h3>
                            <div class="rating">⭐⭐⭐⭐⭐</div>
                        </div>
                    </div>
                    <p>"Kopi Nubruk nya kuat dan harga terjangkau, cocok buat nongkrong santai seharian."</p>
                </div>
                <div class="testimonial-card">
                    <div class="customer-info">
                        <img src="" alt="Saroni">
                        <div>
                            <h3>Saroni</h3>
                            <div class="rating">⭐⭐⭐⭐☆</div>
                        </div>
                    </div>
                    <p>"Green Tea Latte di sini enak banget, matchanya terasa autentik dan nggak terlalu manis."</p>
                </div>
            </div>
        </section>

        <section class="faq-section">
            <h2>Daftar Pertanyaan Umum</h2>
            <div class="faq-item">
                <div class="faq-question">
                    Bagaimana Cara Memesan Atau Membeli Produk?
                    <span class="arrow">▼</span>
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question">
                    Pembayaran lewat apa saja?
                    <span class="arrow">▼</span>
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question">
                    Apakah Buka Setiap Hari?
                    <span class="arrow">▼</span>
                </div>
            </div>
        </section>

        <section class="terms-section">
            <h2>Syarat dan Ketentuan Pengguna Website</h2>
            <p>Dengan menggunakan website ini, Anda setuju bahwa pemesanan yang telah dikonfirmasi bersifat mengikat dan tidak dapat dibatalkan. Harga dan ketersediaan menu yang berlaku adalah yang tertera di website pada saat pemesanan. Seluruh konten (gambar, teks, logo) di website ini adalah milik Poskoria Street Coffee dan dilindungi hak cipta. Kami berhak mengubah syarat ini sewaktu-waktu.</p>
            
            <h2>Kebijakan Privasi</h2>
            <p>Kami hanya mengumpulkan data pribadi Anda (seperti nama, nomor telepon, dan alamat) untuk keperluan memproses pesanan dan mengirimkan notifikasi. Data Anda akan kami lindungi dan tidak akan kami jual atau bagikan ke pihak ketiga manapun. Dengan menggunakan layanan kami, Anda menyetujui pengumpulan dan penggunaan data untuk tujuan tersebut.</p>
        </section>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-logo">PO</div>
        <div class="footer-content">
            <p>© 2025 Poskoria Street Coffee. All rights reserved.</p>
            <div class="footer-contact">
                <span>📞 085691111136</span>
                <span>📸 @poskoria</span>
            </div>
        </div>
    </footer>

    <!-- JavaScript untuk Hamburger Menu -->
    <script>
document.addEventListener('DOMContentLoaded', function() {
  const hamburgerMenu = document.getElementById('hamburgerMenu');
  const mobileNav = document.getElementById('mobileNav');
  const closeMobileNav = document.getElementById('closeMobileNav');
  const overlay = document.getElementById('overlay');

  // Fungsi untuk menutup mobile menu
  function closeMobileMenu() {
    mobileNav.classList.remove('active');
    overlay.classList.remove('active');
    document.body.style.overflow = 'auto';
  }

  // Buka menu mobile
  hamburgerMenu.addEventListener('click', function() {
    mobileNav.classList.add('active');
    overlay.classList.add('active');
    document.body.style.overflow = 'hidden';
  });

  // Tutup menu mobile
  closeMobileNav.addEventListener('click', closeMobileMenu);
  overlay.addEventListener('click', closeMobileMenu);

  // Tutup mobile menu saat klik link navigasi
  const mobileLinks = document.querySelectorAll('.mobile-nav a');
  mobileLinks.forEach(link => {
    link.addEventListener('click', closeMobileMenu);
  });

  // Scroll halus ke section
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
      const targetId = this.getAttribute('href');
      const targetElement = document.querySelector(targetId);

      if (targetElement && targetId !== "#") {
        e.preventDefault();

        window.scrollTo({
          top: targetElement.offsetTop - 80, // sesuaikan tinggi header
          behavior: 'smooth'
        });

        // Tutup menu otomatis jika dari mobile
        mobileNav.classList.remove('active');
        overlay.classList.remove('active');
        document.body.style.overflow = 'auto';
      }
    });
  });
});
</script>
</body>
</html>