<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../config/login.php");
    exit();
}

include("../config/controller.php");
$kategori_buku = select("SELECT * FROM kategori");
$books = select("SELECT buku.*, kategori.nama as nama_kategori, rak.no_rak 
                    FROM buku 
                    LEFT JOIN kategori ON buku.id_kategori = kategori.id_kategori
                    LEFT JOIN rak ON buku.id_rak = rak.id_rak");

// Get selected category from URL parameter
$selected_category = isset($_GET['category']) ? $_GET['category'] : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Buku - Perpustakaan</title>
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
            --hover-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        }

        body {
            background-color: var(--light-bg);
            font-family: 'Poppins', sans-serif;
            color: var(--text-color);
            transition: all 0.3s ease;
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
            display: flex;
            align-items: center;
        }

        .clock {
            font-size: 0.75rem;
            font-weight: 400;
            color: rgba(255, 255, 255, 0.9);
            background: rgba(255, 255, 255, 0.1);
            padding: 0.15rem 0.4rem;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            margin-left: 0.4rem;
        }

        .clock i {
            font-size: 0.7rem;
            margin-right: 0.2rem;
        }

        #current-time {
            font-family: 'Courier New', monospace;
            letter-spacing: 0.3px;
        }

        .nav-link {
            color: rgba(255,255,255,0.8) !important;
            font-weight: 500;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
            border-radius: 5px;
        }

        .nav-link:hover {
            color: white !important;
            background: rgba(255,255,255,0.1);
            transform: translateY(-2px);
        }

        .header {
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), 
                        url('https://perpustakaan.poltekparmakassar.ac.id/storage/2020/07/trust-tru-katsande-BTAAcbO9Gco-unsplash-scaled.jpg');
            background-size: cover;
            background-position: center;
            padding: 80px 20px;
            border-radius: 20px;
            color: white;
            text-align: center;
            margin: 2rem 0;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .search-container {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--card-shadow);
        }

        .search-input {
            width: 100%;
            padding: 12px 20px;
            padding-left: 40px;
            border: 2px solid #eee;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
            outline: none;
        }

        .search-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
        }

        .book-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }

        .row.g-4 {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-start;
            gap: 1.2rem !important;
        }

        .book-item {
            flex: 0 0 calc(25% - 1.2rem);
            max-width: calc(25% - 1.2rem);
            margin-bottom: 1.5rem;
        }

        @media (max-width: 992px) {
            .book-item {
                flex: 0 0 calc(33.333% - 1.2rem);
                max-width: calc(33.333% - 1.2rem);
            }
        }
        @media (max-width: 768px) {
            .book-item {
                flex: 0 0 calc(50% - 1.2rem);
                max-width: calc(50% - 1.2rem);
            }
        }
        @media (max-width: 576px) {
            .book-item {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }

        .book-card {
            display: flex;
            flex-direction: column;
            align-items: stretch;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
            height: 100%;
            min-width: 0;
            transition: box-shadow 0.3s, transform 0.3s;
        }

        .book-card:hover {
            box-shadow: var(--hover-shadow);
            transform: translateY(-4px);
        }

        .book-card img {
            width: 100%;
            height: 160px;
            object-fit: cover;
            border-radius: 12px 12px 0 0;
            flex-shrink: 0;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .book-card img:hover {
            transform: scale(1.05);
        }

        .book-card .card-body {
            padding: 0.9rem 1rem 1rem 1rem;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .book-card .card-title {
            font-weight: 700;
            font-size: 1rem;
            margin-bottom: 0.4rem;
            color: var(--primary-color);
            line-height: 1.2;
        }

        .book-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .book-meta .meta-item {
            display: flex;
            align-items: center;
            font-size: 0.8rem;
            color: #666;
            background: #f8f9fa;
            padding: 0.2rem 0.5rem;
            border-radius: 4px;
            gap: 0.3rem;
        }

        .book-meta .meta-item i {
            color: var(--secondary-color);
            font-size: 0.9rem;
        }

        .book-card .card-text {
            color: #444;
            font-size: 0.88rem;
            margin-bottom: 0.5rem;
            flex-grow: 0;
            line-height: 1.4;
        }

        .book-tags {
            margin-top: 0.4rem;
            display: flex;
            flex-wrap: wrap;
            gap: 0.3rem;
        }

        .book-tag {
            display: inline-block;
            background: #f1f1f1;
            color: #555;
            border-radius: 6px;
            padding: 0.15rem 0.6rem;
            font-size: 0.75rem;
        }

        .btn-group {
            margin-top: 0.5rem;
            display: flex;
            gap: 0.5rem;
        }

        .btn-borrow, .btn-view {
            flex: 1;
            text-align: center;
            padding: 0.4rem 0.8rem;
            font-size: 0.8rem;
            border-radius: 6px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-borrow {
            background-color: var(--secondary-color);
            color: white;
        }

        .btn-borrow:hover {
            background-color: var(--primary-color);
            color: white;
            transform: translateY(-2px);
        }

        .btn-view {
            background-color: var(--warning-color);
            color: white;
        }

        .btn-view:hover {
            background-color: #e67e22;
            color: white;
            transform: translateY(-2px);
        }

        .filter-sidebar {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            margin-bottom: 2rem;
        }

        .filter-sidebar h3 {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--primary-color);
        }

        .category-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .category-item {
            margin-bottom: 0.5rem;
        }

        .category-checkbox {
            display: flex;
            align-items: center;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .category-checkbox:hover {
            background-color: rgba(52, 152, 219, 0.1);
        }

        .category-checkbox input[type="checkbox"] {
            margin-right: 0.5rem;
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .category-checkbox label {
            margin: 0;
            cursor: pointer;
            font-size: 0.9rem;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
        }

        .col-md-3 {
            flex: 0 0 25%;
            max-width: 25%;
            padding-right: 15px;
        }

        .col-md-9 {
            flex: 0 0 75%;
            max-width: 75%;
            padding-left: 15px;
        }

        @media (max-width: 768px) {
            .col-md-3, .col-md-9 {
                flex: 0 0 100%;
                max-width: 100%;
                padding: 0;
            }
        }

        .footer {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 2rem 0;
            margin-top: 4rem;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin-bottom: 1.5rem;
        }

        .footer-links a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .footer-links a:hover {
            color: white;
            transform: translateY(-2px);
        }

        .footer-copyright {
            text-align: center;
            color: rgba(255,255,255,0.6);
            font-size: 0.9rem;
        }

        .no-results {
            text-align: center;
            padding: 2rem;
            display: none;
        }

        .no-results i {
            font-size: 3rem;
            color: var(--secondary-color);
            margin-bottom: 1rem;
        }

        .no-results p {
            font-size: 1.2rem;
            color: var(--text-color);
            margin: 0;
        }

        /* Remove Popular Books Styles */
        @media (max-width: 768px) {
            .col-md-3, .col-md-9 {
                flex: 0 0 100%;
                max-width: 100%;
                padding: 0;
            }
        }

        /* Lightbox Styles */
        .lightbox {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            z-index: 1000;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .lightbox.active {
            display: flex;
            opacity: 1;
            align-items: center;
            justify-content: center;
        }

        .lightbox-content {
            position: relative;
            max-width: 90%;
            max-height: 90vh;
            margin: auto;
        }

        .lightbox-content img {
            max-width: 100%;
            max-height: 90vh;
            object-fit: contain;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0,0,0,0.3);
        }

        .lightbox-close {
            position: absolute;
            top: -40px;
            right: 0;
            color: white;
            font-size: 2rem;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .lightbox-close:hover {
            transform: rotate(90deg);
        }

        .lightbox-caption {
            position: absolute;
            bottom: -40px;
            left: 0;
            color: white;
            font-size: 1.1rem;
            text-align: center;
            width: 100%;
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
                        <a class="nav-link" href="../landing/siswa.php">
                            <i class="fas fa-home me-1"></i> Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="semua-daftar-buku.php">
                            <i class="fas fa-book me-1"></i> Daftar Buku
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user me-1"></i><?php echo htmlspecialchars($_SESSION['username']); ?>
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
            <h1>Daftar Buku</h1>
            <p>Temukan dan pinjam buku yang Anda inginkan</p>
        </div>

        <div class="row">
            <!-- Filter Sidebar -->
            <div class="col-md-3">
                <div class="filter-sidebar" data-aos="fade-right">
                    <h3>Filter Kategori</h3>
                    <ul class="category-list">
                        <?php foreach ($kategori_buku as $kategori): ?>
                            <li class="category-item">
                                <div class="category-checkbox">
                                    <input type="checkbox" id="kategori-<?= $kategori['id_kategori']; ?>" 
                                           value="<?= $kategori['id_kategori']; ?>" class="category-filter"
                                           <?= ($selected_category == $kategori['id_kategori']) ? 'checked' : ''; ?>>
                                    <label for="kategori-<?= $kategori['id_kategori']; ?>"><?= $kategori['nama']; ?></label>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9">
                <div class="search-container" data-aos="fade-up">
                    <div class="position-relative">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" class="search-input" id="searchInput" placeholder="Cari buku...">
                    </div>
                </div>

                <div class="book-container">
                    <div class="row g-4" id="bookContainer">
                        <?php if (empty($books)): ?>
                            <div class="col-12 text-center">
                                <div class="no-books-message" style="padding: 2rem; background: white; border-radius: 10px; box-shadow: var(--card-shadow);">
                                    <i class="fas fa-book" style="font-size: 3rem; color: var(--secondary-color); margin-bottom: 1rem;"></i>
                                    <h3 style="color: var(--primary-color); margin-bottom: 0.5rem;">Belum Ada Buku Tersedia</h3>
                                    <p style="color: var(--text-color);">Silakan kembali lagi nanti untuk melihat daftar buku yang tersedia.</p>
                                </div>
                            </div>
                        <?php else: ?>
                            <?php 
                            // Group books by rak number
                            $grouped_books = [];
                            foreach ($books as $book) {
                                $rak_number = $book['no_rak'];
                                if (!isset($grouped_books[$rak_number])) {
                                    $grouped_books[$rak_number] = [];
                                }
                                $grouped_books[$rak_number][] = $book;
                            }

                            // Sort rak numbers
                            ksort($grouped_books);

                            // Display books grouped by rak
                            foreach ($grouped_books as $rak_number => $rak_books): 
                            ?>
                                <div class="col-12">
                                    <h4 class="mb-3">Rak <?= $rak_number; ?></h4>
                                </div>
                                <?php foreach ($rak_books as $book): ?>
                                    <div class="book-item" data-kategori="<?= $book['id_kategori']; ?>">
                                        <div class="book-card">
                                            <img src="<?= $book['gambar']; ?>" alt="<?= $book['judul_buku']; ?>" onclick="openLightbox(this.src, this.alt)">
                                            <div class="card-body">
                                                <div>
                                                    <div class="card-title"><?= $book['judul_buku']; ?></div>
                                                    <div class="book-meta">
                                                        <div class="meta-item">
                                                            <i class="fas fa-user-edit"></i>
                                                            <span><?= $book['pengarang']; ?></span>
                                                        </div>
                                                        <div class="meta-item">
                                                            <i class="fas fa-calendar"></i>
                                                            <span><?= $book['tahun_terbit']; ?></span>
                                                        </div>
                                                        <div class="meta-item">
                                                            <i class="fas fa-boxes"></i>
                                                            <span><?= $book['stok']; ?></span>
                                                        </div>
                                                        <div class="meta-item">
                                                            <i class="fas fa-shelf"></i>
                                                            <span>Rak: <?= $book['no_rak']; ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="card-text">
                                                        <?= mb_strimwidth($book['deskripsi'], 0, 70, '...'); ?>
                                                    </div>
                                                </div>
                                                <div class="book-tags">
                                                    <span class="book-tag"><?= $book['nama_kategori']; ?></span>
                                                </div>
                                                <div class="btn-group w-100">
                                                    <a href="#" class="btn btn-borrow" onclick="borrowBook(<?= $book['id_buku']; ?>)">
                                                        <i class="fas fa-book-reader me-1"></i> Pinjam
                                                    </a>
                                                    <a href="#" class="btn btn-view" onclick="viewBook(<?= $book['id_buku']; ?>)">
                                                        <i class="fas fa-eye me-1"></i> Lihat
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <div class="no-results" id="noResults">
                        <i class="fas fa-search"></i>
                        <p>Maaf, buku yang Anda cari tidak ada</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="footer-content">
            <div class="footer-links">
                <a href="../landing/siswa.php">
                    <i class="fas fa-home me-1"></i> Beranda
                </a>
                <a href="semua-daftar-buku.php">
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

    <!-- Lightbox -->
    <div class="lightbox" id="imageLightbox">
        <div class="lightbox-content">
            <span class="lightbox-close" onclick="closeLightbox()">
                <i class="fas fa-times"></i>
            </span>
            <img src="" alt="" id="lightboxImage">
            <div class="lightbox-caption" id="lightboxCaption"></div>
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

        function confirmLogout() {
            if (confirm("Apakah Anda yakin ingin logout?")) {
                window.location.href = "../config/logout.php";
            }
        }

        function borrowBook(id) {
            alert("Fitur peminjaman buku akan segera tersedia!");
        }

        function viewBook(id) {
            window.location.href = `lihat-buku.php?id=${id}`;
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

        // Search and filter functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const categoryFilters = document.querySelectorAll('.category-filter');
            const bookItems = document.querySelectorAll('.book-item');
            const noResults = document.getElementById('noResults');

            // If there's a selected category from URL, trigger filter immediately
            const urlParams = new URLSearchParams(window.location.search);
            const selectedCategory = urlParams.get('category');
            if (selectedCategory) {
                filterBooks();
            }

            function filterBooks() {
                const searchTerm = searchInput.value.toLowerCase();
                const selectedCategories = Array.from(categoryFilters)
                    .filter(checkbox => checkbox.checked)
                    .map(checkbox => checkbox.value);
                
                let found = false;
                
                bookItems.forEach(item => {
                    const title = item.querySelector('.card-title').textContent.toLowerCase();
                    const author = item.querySelector('.meta-item span').textContent.toLowerCase();
                    const description = item.querySelector('.card-text').textContent.toLowerCase();
                    const bookCategory = item.getAttribute('data-kategori');
                    
                    const matchesSearch = title.includes(searchTerm) || 
                                        author.includes(searchTerm) || 
                                        description.includes(searchTerm);
                    const matchesCategory = selectedCategories.length === 0 || 
                                          selectedCategories.includes(bookCategory);
                    
                    if (matchesSearch && matchesCategory) {
                        item.style.display = '';
                        found = true;
                    } else {
                        item.style.display = 'none';
                    }
                });

                // Show/hide no results message
                if (!found) {
                    noResults.style.display = 'block';
                    noResults.innerHTML = `
                        <i class="fas fa-search"></i>
                        <p>${searchTerm ? 'Buku yang Anda cari tidak ditemukan' : 'Tidak ada buku dalam kategori yang dipilih'}</p>
                    `;
                } else {
                    noResults.style.display = 'none';
                }
            }

            // Add event listeners
            searchInput.addEventListener('input', filterBooks);
            categoryFilters.forEach(checkbox => {
                checkbox.addEventListener('change', filterBooks);
            });

            // Add "Select All" and "Clear All" buttons
            const filterSidebar = document.querySelector('.filter-sidebar');
            const buttonGroup = document.createElement('div');
            buttonGroup.className = 'd-flex gap-2 mt-3';
            buttonGroup.innerHTML = `
                <button class="btn btn-sm btn-primary" id="selectAll">
                    <i class="fas fa-check-square me-1"></i>Pilih Semua
                </button>
                <button class="btn btn-sm btn-secondary" id="clearAll">
                    <i class="fas fa-times-circle me-1"></i>Hapus Semua
                </button>
            `;
            filterSidebar.appendChild(buttonGroup);

            // Add event listeners for the new buttons
            document.getElementById('selectAll').addEventListener('click', function() {
                categoryFilters.forEach(checkbox => checkbox.checked = true);
                filterBooks();
            });

            document.getElementById('clearAll').addEventListener('click', function() {
                categoryFilters.forEach(checkbox => checkbox.checked = false);
                filterBooks();
            });
        });

        // Lightbox functionality
        function openLightbox(imageSrc, imageAlt) {
            const lightbox = document.getElementById('imageLightbox');
            const lightboxImg = document.getElementById('lightboxImage');
            const lightboxCaption = document.getElementById('lightboxCaption');
            
            lightboxImg.src = imageSrc;
            lightboxCaption.textContent = imageAlt;
            lightbox.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeLightbox() {
            const lightbox = document.getElementById('imageLightbox');
            lightbox.classList.remove('active');
            document.body.style.overflow = '';
        }

        // Close lightbox when clicking outside the image
        document.getElementById('imageLightbox').addEventListener('click', function(e) {
            if (e.target === this) {
                closeLightbox();
            }
        });

        // Close lightbox with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeLightbox();
            }
        });

        // Add click event to all book images
        document.addEventListener('DOMContentLoaded', function() {
            const bookImages = document.querySelectorAll('.book-card img');
            bookImages.forEach(img => {
                img.addEventListener('click', function() {
                    openLightbox(this.src, this.alt);
                });
            });
        });

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
