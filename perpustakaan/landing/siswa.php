<?php
include "../config/controller.php";
include "../middleware/cek-siswa.php";

if (!isset($_SESSION['username'])) {
    header("Location: ../config/login.php");
    exit();
}

$kategori_buku = select("SELECT * FROM kategori");

// Get user's full name
$username = $_SESSION['username'];
$user_data = select("SELECT name FROM login WHERE username = '$username'")[0];
$full_name = $user_data['name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perpustakaan - SMKN 7 Samarinda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/landing-home-siswa.css">
    <style>
        @media (max-width: 768px) {
            .popular-card {
                margin-bottom: 1rem;
            }
        }

        /* Category Scroll Styles */
        .category-container {
            position: relative;
            overflow: hidden;
            padding: 20px 0;
        }

        .category-scroll {
            display: flex;
            gap: 15px;
            animation: scroll 30s linear infinite;
            width: max-content;
        }

        .category-scroll:hover {
            animation-play-state: paused;
        }

        @keyframes scroll {
            0% {
                transform: translateX(0);
            }
            100% {
                transform: translateX(-50%);
            }
        }

        .category-scroll::after {
            content: "";
            display: block;
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 100%;
            background: linear-gradient(to right, rgba(255,255,255,0), rgba(255,255,255,0.8));
            pointer-events: none;
        }

        .category-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .category-card .card-title {
            margin-top: 10px;
            font-size: 0.9rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 100%;
            text-align: center;
        }

        .category-card .card-body {
            padding: 15px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .category-card .category-icon {
            font-size: 1.5rem;
            margin-bottom: 5px;
        }

        /* Floating Contact Tool Styles */
        .floating-tool {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1000;
        }

        .tool-button {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
        }

        .tool-button:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.3);
        }

        .tool-content {
            position: absolute;
            bottom: 80px;
            right: 0;
            background: white;
            border-radius: 15px;
            padding: 20px;
            width: 300px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.15);
            display: none;
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.3s ease;
        }

        .tool-content.active {
            display: block;
            opacity: 1;
            transform: translateY(0);
        }

        .tool-content::after {
            content: '';
            position: absolute;
            bottom: -10px;
            right: 25px;
            width: 20px;
            height: 20px;
            background: white;
            transform: rotate(45deg);
            box-shadow: 5px 5px 10px rgba(0,0,0,0.05);
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }

        .contact-item:last-child {
            border-bottom: none;
        }

        .contact-icon {
            width: 40px;
            height: 40px;
            background: var(--light-bg);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--secondary-color);
            font-size: 18px;
        }

        .contact-info {
            flex: 1;
        }

        .contact-info h6 {
            margin: 0;
            color: var(--primary-color);
            font-weight: 600;
        }

        .contact-info p {
            margin: 0;
            color: #666;
            font-size: 0.9rem;
        }

        .social-links {
            display: flex;
            gap: 10px;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }

        .social-link {
            width: 35px;
            height: 35px;
            background: var(--light-bg);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--secondary-color);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .social-link:hover {
            background: var(--secondary-color);
            color: white;
            transform: translateY(-3px);
        }

        @media (max-width: 768px) {
            .floating-tool {
                bottom: 20px;
                right: 20px;
            }

            .tool-button {
                width: 50px;
                height: 50px;
                font-size: 20px;
            }

            .tool-content {
                width: 280px;
                bottom: 70px;
                right: 0;
            }
        }

        /* Popular Books Card Styles */
        .popular-card {
            height: 100%;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .popular-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .popular-card img {
            height: 200px;
            object-fit: cover;
            width: 100%;
        }

        .popular-card .card-body {
            padding: 1rem;
            display: flex;
            flex-direction: column;
        }

        .popular-card .card-title {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            height: 2.5em;
            line-height: 1.25;
        }

        .popular-card .card-text {
            font-size: 0.875rem;
            color: #666;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            height: 2.5em;
            line-height: 1.25;
            margin-bottom: 0.5rem;
        }

        .popular-card .book-meta {
            font-size: 0.75rem;
            margin-bottom: 0.5rem;
        }

        .popular-card .book-meta small {
            display: block;
            margin-bottom: 0.25rem;
        }

        .popular-card .btn-primary {
            margin-top: auto;
            font-size: 0.875rem;
            padding: 0.375rem 0.75rem;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-book-reader me-2"></i>Perpustakaan
                <small class="clock ms-2">
                    <i class="fas fa-clock me-1"></i>
                    <span id="current-time"></span>
                </small>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="siswa.php">
                            <i class="fas fa-home me-1"></i> Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../buku/semua-daftar-buku.php">
                            <i class="fas fa-book me-1"></i> Daftar Buku
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user me-1"></i><?php echo htmlspecialchars($username); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="../siswa/profil/profil.php">
                                    <i class="fas fa-user-circle me-2"></i>Profil
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="#" onclick="confirmLogout()">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="header" data-aos="fade-up">
            <h2>Selamat Datang, <?php echo htmlspecialchars($full_name); ?>!</h2>
            <p>"Temukan pengetahuan tanpa batas di perpustakaan kami"</p>
            <a href="../buku/semua-daftar-buku.php" class="btn btn-explore">
                <i class="fas fa-book-open me-2"></i>Jelajahi Buku
            </a>
        </div>

        <div id="panduan" class="panduan-container" data-aos="fade-up">
            <div class="panduan-background"></div>
            <div class="panduan-text">
                <h2>Fitur <span>Perpustakaan Kami</span></h2>
                <ul>
                    <li><strong>Koleksi Buku Lengkap</strong> – Akses buku dari berbagai kategori</li>
                    <li><strong>Kategori Terorganisir</strong> – Temukan buku dengan mudah melalui kategori</li>
                    <li><strong>Buku Populer</strong> – Lihat buku-buku yang paling diminati</li>
                    <li><strong>Peminjaman Online</strong> – Pinjam buku secara online</li>
                    <li><strong>Riwayat Peminjaman</strong> – Pantau status peminjaman buku Anda</li>
                </ul>
            </div>
        </div>

        <div id="kategori" class="category-section" data-aos="fade-up">
            <div class="container-fluid px-4">
                <h3 class="text-center fw-bold section-title">Kategori Buku</h3>
                <div class="category-container">
                    <div class="category-scroll">
                        <?php 
                        $category_icons = [
                            'Novel' => 'fa-book',
                            'Pendidikan' => 'fa-graduation-cap',
                            'Teknologi' => 'fa-laptop-code',
                            'Sejarah' => 'fa-landmark',
                            'Sains' => 'fa-flask',
                            'Seni' => 'fa-palette',
                            'Bisnis' => 'fa-chart-line',
                            'Kesehatan' => 'fa-heartbeat',
                            'Agama' => 'fa-pray',
                            'Olahraga' => 'fa-running',
                            'Masakan' => 'fa-utensils',
                            'Travel' => 'fa-plane'
                        ];
                        
                        // First set of categories
                        foreach ($kategori_buku as $kategori): 
                            $icon = isset($category_icons[$kategori['nama']]) ? $category_icons[$kategori['nama']] : 'fa-book';
                        ?>
                            <div class="category-card" onclick="window.location.href='../buku/semua-daftar-buku.php?category=<?= $kategori['id_kategori']; ?>'">
                                <div class="card-body">
                                    <i class="fas <?= $icon ?> category-icon"></i>
                                    <h5 class="card-title"><?= $kategori['nama']; ?></h5>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <!-- Duplicate set for infinite scroll -->
                        <?php foreach ($kategori_buku as $kategori): 
                            $icon = isset($category_icons[$kategori['nama']]) ? $category_icons[$kategori['nama']] : 'fa-book';
                        ?>
                            <div class="category-card" onclick="window.location.href='../buku/semua-daftar-buku.php?category=<?= $kategori['id_kategori']; ?>'">
                                <div class="card-body">
                                    <i class="fas <?= $icon ?> category-icon"></i>
                                    <h5 class="card-title"><?= $kategori['nama']; ?></h5>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <div id="populer" class="popular-section" data-aos="fade-up">
            <div class="container-fluid px-4">
                <h3 class="text-center fw-bold section-title">Buku Populer</h3>
                <div class="popular-container">
                    <div class="row g-4">
                        <?php 
                        // Get popular books from database
                        $popular_books = select("SELECT buku.*, kategori.nama as nama_kategori 
                                              FROM buku 
                                              LEFT JOIN kategori ON buku.id_kategori = kategori.id_kategori 
                                              ORDER BY stok DESC LIMIT 8");
                        
                        foreach ($popular_books as $book): ?>
                            <div class="col-md-3">
                                <div class="popular-card">
                                    <img src="<?= htmlspecialchars($book['gambar']); ?>" alt="<?= htmlspecialchars($book['judul_buku']); ?>">
                                    <div class="card-body">
                                        <h5 class="card-title"><?= htmlspecialchars($book['judul_buku']); ?></h5>
                                        <p class="card-text"><?= htmlspecialchars($book['deskripsi']); ?></p>
                                        <div class="book-meta">
                                            <small class="text-muted">
                                                <i class="fas fa-user-edit me-1"></i>
                                                <?= htmlspecialchars($book['pengarang']); ?>
                                            </small>
                                            <small class="text-muted">
                                                <i class="fas fa-tag me-1"></i>
                                                <?= htmlspecialchars($book['nama_kategori']); ?>
                                            </small>
                                        </div>
                                        <a href="../siswa/buku/lihat-buku-populer.php?id=<?= $book['id_buku']; ?>" class="btn btn-primary">
                                            <i class="fas fa-eye me-1"></i>Lihat
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="footer-content">
            <div class="footer-links">
                <a href="siswa.php">
                    <i class="fas fa-home me-1"></i> Beranda
                </a>
                <a href="../buku/semua-daftar-buku.php">
                    <i class="fas fa-book me-1"></i> Daftar Buku
                </a>
                <a href="#" onclick="confirmLogout()">
                    <i class="fas fa-sign-out-alt me-1"></i> Logout
                </a>
            </div>
            <div class="footer-copyright">
                <p class="mb-0">&copy; 2024 Perpustakaan SMKN 7 Samarinda. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <button class="dark-mode-toggle" id="toggleDarkMode" onclick="toggleDarkMode()" style="display: none;">
        <i class="fas fa-moon"></i>
    </button>

    <!-- Floating Contact Tool -->
    <div class="floating-tool">
        <button class="tool-button" onclick="toggleContactTool()">
            <i class="fas fa-info-circle"></i>
        </button>
        <div class="tool-content" id="contactTool">
            <div class="contact-item">
                <div class="contact-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="contact-info">
                    <h6>Email</h6>
                    <p>Smkn7@gmail.com</p>
                </div>
            </div>
            <div class="contact-item">
                <div class="contact-icon">
                    <i class="fas fa-phone"></i>
                </div>
                <div class="contact-info">
                    <h6>Telepon</h6>
                    <p>+62 81234567890</p>
                </div>
            </div>
            <div class="contact-item">
                <div class="contact-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <div class="contact-info">
                    <h6>Alamat</h6>
                    <p>Jl. Aminah SYukur No. 123, Samarinda</p>
                </div>
            </div>
            <div class="social-links">
                <a href="#" class="social-link">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="#" class="social-link">
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="#" class="social-link">
                    <i class="fab fa-twitter"></i>
                </a>
                <a href="#" class="social-link">
                    <i class="fab fa-youtube"></i>
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();

        // Category scroll animation
        document.addEventListener('DOMContentLoaded', function() {
            const scrollContainer = document.querySelector('.category-scroll');
            const scrollWidth = scrollContainer.scrollWidth;
            const containerWidth = scrollContainer.parentElement.offsetWidth;
            
            // Adjust animation duration based on content width
            const duration = (scrollWidth / containerWidth) * 15; // 15 seconds per container width
            scrollContainer.style.animationDuration = `${duration}s`;
        });

        function confirmLogout() {
            if (confirm("Apakah Anda yakin ingin logout?")) {
                window.location.href = "../config/logout.php";
            }
        }

        function showAlert(event) {
            event.preventDefault();
            alert("Fitur ini sedang dalam pengembangan.");
        }

        // Real-time clock function
        function updateClock() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            });
            document.getElementById('current-time').textContent = timeString;
        }

        // Update clock every second
        setInterval(updateClock, 1000);
        updateClock(); // Initial call

        // Contact Tool Toggle
        function toggleContactTool() {
            const toolContent = document.getElementById('contactTool');
            toolContent.classList.toggle('active');
        }

        // Close tool when clicking outside
        document.addEventListener('click', function(event) {
            const tool = document.querySelector('.floating-tool');
            const toolContent = document.getElementById('contactTool');
            
            if (!tool.contains(event.target) && toolContent.classList.contains('active')) {
                toolContent.classList.remove('active');
            }
        });
    </script>
</body>
</html>