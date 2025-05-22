<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    echo "<script>alert('Login untuk bisa mengakses.');</script>";
}


include "config/controller.php";
$kategori_buku = select("SELECT * FROM kategori");
$buku_pupuler = select("SELECT * FROM buku");
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
    <link rel="stylesheet" href="assets/css/landing-home-siswa.css">
    <style>
        /* Preloader Styles */
        .preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #2c3e50, #3498db);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity 0.5s ease-out;
        }

        .preloader.fade-out {
            opacity: 0;
            pointer-events: none;
        }

        .book-loader {
            width: 120px;
            height: 120px;
            position: relative;
            perspective: 1000px;
        }

        .book {
            width: 100%;
            height: 100%;
            position: relative;
            transform-style: preserve-3d;
            animation: bookOpen 2s infinite ease-in-out;
        }

        .book-cover {
            position: absolute;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #3498db, #2980b9);
            border-radius: 5px;
            box-shadow: 0 0 20px rgba(0,0,0,0.3);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            font-size: 2rem;
            color: white;
            transform-origin: left;
            transition: transform 0.5s ease;
            padding: 10px;
            text-align: center;
            backface-visibility: hidden;
        }

        .book-cover i {
            margin-bottom: 5px;
            animation: iconPulse 2s infinite ease-in-out;
            color: white;
        }

        .book-cover .wait-text {
            font-size: 0.9rem;
            font-weight: 600;
            color: white;
            margin-top: 5px;
            animation: waitPulse 1.5s infinite ease-in-out;
        }

        .book-pages {
            position: absolute;
            width: 100%;
            height: 100%;
            background: #f8f9fa;
            border-radius: 5px;
            transform-origin: left;
            animation: pagesFlip 2s infinite ease-in-out;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }

        .book-spine {
            position: absolute;
            width: 10px;
            height: 100%;
            background: #2980b9;
            left: 0;
            transform: rotateY(90deg) translateZ(-5px);
        }

        @keyframes bookOpen {
            0% {
                transform: rotateY(0deg);
            }
            50% {
                transform: rotateY(-60deg);
            }
            100% {
                transform: rotateY(0deg);
            }
        }

        @keyframes pagesFlip {
            0% {
                transform: rotateY(0deg);
            }
            50% {
                transform: rotateY(-45deg);
            }
            100% {
                transform: rotateY(0deg);
            }
        }

        @keyframes iconPulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.2);
            }
            100% {
                transform: scale(1);
            }
        }

        @keyframes waitPulse {
            0% {
                opacity: 0.5;
            }
            50% {
                opacity: 1;
            }
            100% {
                opacity: 0.5;
            }
        }

        .loader-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 1.2rem;
            font-weight: 600;
            text-align: center;
            width: 100%;
            margin-top: 150px;
            animation: textFade 4s infinite ease-in-out;
        }

        @keyframes textFade {
            0% {
                opacity: 0.7;
            }
            50% {
                opacity: 1;
            }
            100% {
                opacity: 0.7;
            }
        }

        /* Smooth transitions for all elements */
        .navbar, .header, .panduan-container, .category-section, .footer {
            transition: all 0.5s ease-in-out;
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

        .btn-explore {
            transition: all 0.3s ease;
        }

        .btn-explore:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        /* Hide main content initially */
        body {
            opacity: 0;
            transition: opacity 0.5s ease-in;
        }

        body.loaded {
            opacity: 1;
        }

        /* Smooth scroll behavior */
        html {
            scroll-behavior: smooth;
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
    </style>
</head>

<body>
    <!-- Preloader -->
    <div class="preloader" id="preloader">
        <div class="book-loader">
            <div class="book">
                <div class="book-cover">
                    <i class="fas fa-book"></i>
                    <span class="wait-text">Tunggu...</span>
                </div>
                <div class="book-spine"></div>
                <div class="book-pages"></div>
            </div>
        </div>
        <div class="loader-text">
            <p>Perpustakaan SMKN 7 Samarinda</p>
            <small>Memuat...</small>
        </div>
    </div>

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
                        <a class="nav-link" href="home.php">
                            <i class="fas fa-home me-1"></i> Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="buku-home.php">
                            <i class="fas fa-book me-1"></i> Daftar Buku
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-sign-in-alt me-1"></i> Login
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="loginRegistrasi/loginUpdate.php">
                                <i class="fas fa-sign-in-alt me-2"></i>Login
                            </a></li>
                            <li><a class="dropdown-item" href="loginRegistrasi/registrasiUpdate.php">
                                <i class="fas fa-user-plus me-2"></i>Registrasi
                            </a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="header" data-aos="fade-up">
            <h2>"Sebuah buku adalah mimpi yang Anda pegang di tangan Anda."</h2>
            <p>"Temukan pengetahuan tanpa batas di perpustakaan kami"</p>
            <a href="buku-home.php" class="btn btn-explore">
                <i class="fas fa-book me-2"></i>Jelajahi Buku
            </a>
        </div>

        <div id="panduan" class="panduan-container" data-aos="fade-up">
            <div class="panduan-background"></div>
            <div class="panduan-text">
                <h2>Fitur <span>Perpustakaan Kami</span></h2>
                <ul>
                    <li><strong>Koleksi Buku Lengkap</strong> – Akses ribuan buku dari berbagai kategori</li>
                    <li><strong>Kategori Terorganisir</strong> – Temukan buku dengan mudah melalui kategori</li>
                    <li><strong>Peminjaman Online</strong> – Pinjam buku secara online</li>
                    <li><strong>Fitur Pencarian</strong> – Cari buku dengan cepat dan mudah</li>
                    <li><strong>Login</strong> – Agar dapat mengakses semua buku</li>
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
                            <div class="category-card" onclick="window.location.href='buku-home.php?category=<?= $kategori['id_kategori']; ?>'">
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
                            <div class="category-card" onclick="window.location.href='buku-home.php?category=<?= $kategori['id_kategori']; ?>'">
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
    </div>

    <footer class="footer">
        <div class="footer-content">
            <div class="footer-links">
                <a href="home.php">
                    <i class="fas fa-home me-1"></i> Beranda
                </a>
                <a href="buku-home.php">
                    <i class="fas fa-book me-1"></i> Daftar Buku
                </a>
                <a href="loginRegistrasi/loginUpdate.php">
                    <i class="fas fa-sign-in-alt me-1"></i> Login
                </a>
            </div>
            <div class="footer-copyright">
                <p class="mb-0">&copy; 2024 Perpustakaan SMKN 7 Samarinda. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

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
        // Preloader functionality
        window.addEventListener('load', function() {
            const preloader = document.getElementById('preloader');
            const body = document.body;
            
            // Add loaded class to body
            body.classList.add('loaded');
            
            // Fade out preloader after 3 seconds
            setTimeout(() => {
                preloader.classList.add('fade-out');
                // Remove preloader from DOM after fade out
                setTimeout(() => {
                    preloader.remove();
                }, 500);
            }, 3000);
        });

        AOS.init({
            duration: 1000,
            once: true,
            offset: 100
        });

        // Smooth scroll for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        function showAlert(event) {
            event.preventDefault();
            alert("Silakan login untuk mengakses fitur ini.");
            window.location.href = "loginRegistrasi/loginUpdate.php";
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

        // Category scroll animation
        document.addEventListener('DOMContentLoaded', function() {
            const scrollContainer = document.querySelector('.category-scroll');
            const scrollWidth = scrollContainer.scrollWidth;
            const containerWidth = scrollContainer.parentElement.offsetWidth;
            
            // Adjust animation duration based on content width
            const duration = (scrollWidth / containerWidth) * 15; // 15 seconds per container width
            scrollContainer.style.animationDuration = `${duration}s`;
        });
    </script>
</body>

</html>