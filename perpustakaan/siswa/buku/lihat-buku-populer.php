<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../../config/login.php");
    exit();
}

include("../../config/controller.php");

// Get book ID from URL parameter
$id_buku = isset($_GET['id']) ? $_GET['id'] : null;

if (!$id_buku) {
    header("Location: ../../landing/siswa.php");
    exit();
}

// Get book details
$book = select("SELECT buku.*, kategori.nama as nama_kategori, rak.no_rak 
               FROM buku 
               LEFT JOIN kategori ON buku.id_kategori = kategori.id_kategori 
               LEFT JOIN rak ON buku.id_rak = rak.id_rak
               WHERE buku.id_buku = $id_buku")[0] ?? null;

if (!$book) {
    header("Location: ../../landing/siswa.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($book['judul_buku']); ?> - Perpustakaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --success-color: #2ecc71;
            --warning-color: #f39c12;
            --text-color: #2c3e50;
            --light-bg: #f8f9fa;
            --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        body {
            background-color: var(--light-bg);
            font-family: 'Poppins', sans-serif;
            color: var(--text-color);
        }

        .navbar {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            padding: 1rem 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            font-size: 1.8rem;
            font-weight: 700;
            color: white !important;
        }

        .back-button {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .back-button:hover {
            color: rgba(255,255,255,0.9);
            transform: translateX(-5px);
        }

        .book-detail-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 15px;
        }

        .book-detail-card {
            background: white;
            border-radius: 15px;
            box-shadow: var(--card-shadow);
            overflow: hidden;
        }

        .book-cover {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border: 8px solid white;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.15);
            transition: all 0.3s ease;
        }

        .book-cover:hover {
            transform: scale(1.02);
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
        }

        .book-info {
            padding: 2rem;
        }

        .book-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .book-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--light-bg);
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.9rem;
        }

        .meta-item i {
            color: var(--secondary-color);
        }

        .book-description {
            color: #444;
            line-height: 1.6;
            margin-bottom: 2rem;
        }

        .book-category {
            display: inline-block;
            background: var(--secondary-color);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
        }

        .btn-borrow {
            background-color: var(--secondary-color);
            color: white;
            padding: 0.8rem 2rem;
            border-radius: 8px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-borrow:hover {
            background-color: var(--primary-color);
            color: white;
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .book-cover {
                height: 300px;
            }

            .book-title {
                font-size: 1.5rem;
            }

            .book-meta {
                gap: 0.5rem;
            }

            .meta-item {
                font-size: 0.8rem;
                padding: 0.4rem 0.8rem;
            }
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
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-book-reader me-2"></i>Perpustakaan
            </a>
            <a href="../../landing/siswa.php#populer" class="back-button">
                <i class="fas fa-arrow-left"></i>
                Kembali
            </a>
        </div>
    </nav>

    <div class="book-detail-container">
        <div class="book-detail-card" data-aos="fade-up">
            <div class="row g-0">
                <div class="col-md-4">
                    <img src="<?= htmlspecialchars($book['gambar']); ?>" alt="<?= htmlspecialchars($book['judul_buku']); ?>" class="book-cover">
                </div>
                <div class="col-md-8">
                    <div class="book-info">
                        <h1 class="book-title"><?= htmlspecialchars($book['judul_buku']); ?></h1>
                        
                        <div class="book-meta">
                            <div class="meta-item">
                                <i class="fas fa-user-edit"></i>
                                <span>Pengarang: <?= htmlspecialchars($book['pengarang']); ?></span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-calendar"></i>
                                <span>Tahun Terbit: <?= htmlspecialchars($book['tahun_terbit']); ?></span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-boxes"></i>
                                <span>Stok: <?= htmlspecialchars($book['stok']); ?></span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-shelf"></i>
                                <span>Rak: <?= htmlspecialchars($book['no_rak']); ?></span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-hashtag"></i>
                                <span>ID: <?= htmlspecialchars($book['id_buku']); ?></span>
                            </div>
                        </div>

                        <div class="book-category">
                            <i class="fas fa-tag me-1"></i>
                            <?= htmlspecialchars($book['nama_kategori']); ?>
                        </div>

                        <div class="book-description">
                            <h5 class="mb-3">Deskripsi Buku:</h5>
                            <p><?= nl2br(htmlspecialchars($book['deskripsi'])); ?></p>
                        </div>

                        <div class="action-buttons">
                            <a href="#" class="btn-borrow" onclick="borrowBook(<?= $book['id_buku']; ?>)">
                                <i class="fas fa-book-reader"></i>
                                Pinjam Buku
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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

        function borrowBook(id) {
            alert("Fitur peminjaman buku akan segera tersedia!");
        }

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
