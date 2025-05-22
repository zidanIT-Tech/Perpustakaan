<?php
include("../../config/controller.php");

session_start();

// Cek apakah sudah login
if (!isset($_SESSION['username'])) {
    header("Location: ../../loginRegistrasi/loginUpdate.php?error=Silakan login dulu.");
    exit();
}

// Cek level harus 'petugas'
if ($_SESSION['level'] !== 'petugas') {
    header("Location: ../../loginRegistrasi/loginUpdate.php?error=Akses ditolak!");
    exit();
}


$data_buku = select("SELECT buku.*, kategori.nama as nama_kategori 
                    FROM buku 
                    LEFT JOIN kategori ON buku.id_kategori = kategori.id_kategori");

$total_buku = select("SELECT COUNT(*) as total FROM buku")[0]['total'];
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Buku - Perpustakaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/admin/siswa-data.css">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --success-color: #2ecc71;
            --warning-color: #f39c12;
            --text-color: #2c3e50;
            --light-bg: #f8f9fa;
        }

        body {
            background-color: var(--light-bg);
            font-family: 'Poppins', sans-serif;
            color: var(--text-color);
        }

        .sidebar {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            width: 280px;
            min-height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .content {
            margin-left: 280px;
            transition: all 0.3s ease;
        }

        .sidebar-header {
            padding: 20px;
            background: rgba(0,0,0,0.1);
            text-align: center;
        }

        .profile-section {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .profile-image {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-bottom: 10px;
            border: 3px solid rgba(255,255,255,0.2);
        }

        .profile-name {
            color: white;
            font-size: 1.1rem;
            margin-bottom: 5px;
        }

        .profile-role {
            color: rgba(255,255,255,0.7);
            font-size: 0.9rem;
        }

        .dropdown-menu {
            background: var(--primary-color);
            border: none;
            border-radius: 8px;
            margin-top: 5px;
        }

        .dropdown-item {
            color: rgba(255,255,255,0.8);
            padding: 10px 20px;
            transition: all 0.3s ease;
        }

        .dropdown-item:hover {
            background: rgba(255,255,255,0.1);
            color: white;
        }

        .dropdown-divider {
            border-color: rgba(255,255,255,0.1);
        }

        .nav-link {
            padding: 12px 20px;
            color: rgba(255,255,255,0.8);
            transition: all 0.3s ease;
            border-radius: 5px;
            margin: 5px 0;
        }

        .nav-link:hover, .nav-link.active {
            background: rgba(255,255,255,0.1);
            color: white;
            transform: translateX(5px);
        }

        .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            padding: 30px 20px;
            border-radius: 12px;
            color: white;
            margin-bottom: 20px;
            text-align: center;
        }

        .custom-container {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            margin: 15px;
        }

        .total-count {
            background: var(--light-bg);
            padding: 6px 12px;
            border-radius: 6px;
            font-weight: 500;
            color: var(--primary-color);
            font-size: 0.9rem;
        }

        .btn-add {
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
            color: white;
            border: none;
            padding: 6px 15px;
            border-radius: 6px;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .btn-add:hover {
            transform: translateY(-2px);
            box-shadow: 0 3px 8px rgba(0,0,0,0.15);
            color: white;
        }

        .search-container {
            margin-bottom: 15px;
            position: relative;
            max-width: 300px;
        }

        .search-input {
            width: 100%;
            padding: 8px 15px;
            padding-left: 35px;
            border: 1px solid #eee;
            border-radius: 6px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.1);
            outline: none;
        }

        .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
            font-size: 0.9rem;
        }

        .custom-table {
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
        }

        .custom-table thead th {
            background-color: var(--primary-color);
            color: white;
            padding: 12px 15px;
            font-weight: 600;
            font-size: 0.9rem;
            white-space: nowrap;
        }

        .custom-table tbody td {
            padding: 10px 15px;
            vertical-align: middle;
            font-size: 0.9rem;
        }

        .btn-action {
            padding: 8px 15px;
            border-radius: 6px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .btn-edit {
            background-color: var(--warning-color);
            color: white;
            border: none;
        }

        .btn-edit:hover {
            background-color: #e67e22;
            color: white;
            transform: translateY(-2px);
        }

        .btn-delete {
            background-color: var(--accent-color);
            color: white;
            border: none;
        }

        .btn-delete:hover {
            background-color: #c0392b;
            color: white;
            transform: translateY(-2px);
        }

        .no-results {
            text-align: center;
            padding: 40px;
            color: #666;
            display: none;
        }

        .no-results i {
            color: #ddd;
        }

        .table-responsive {
            max-height: 600px;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: var(--secondary-color) #f0f0f0;
        }
        
        .table-responsive::-webkit-scrollbar {
            width: 8px;
        }
        
        .table-responsive::-webkit-scrollbar-track {
            background: #f0f0f0;
            border-radius: 4px;
        }
        
        .table-responsive::-webkit-scrollbar-thumb {
            background-color: var(--secondary-color);
            border-radius: 4px;
        }
        
        .table-responsive::-webkit-scrollbar-thumb:hover {
            background-color: var(--primary-color);
        }

        .custom-table {
            border-collapse: separate;
            border-spacing: 0;
        }

        .custom-table thead {
            position: sticky;
            top: 0;
            z-index: 1;
            background-color: var(--primary-color);
        }

        .custom-table thead th {
            position: sticky;
            top: 0;
            background-color: var(--primary-color);
            z-index: 1;
        }

        .custom-table tbody {
            position: relative;
        }

        .text-truncate {
            max-width: 120px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .deskripsi-truncate {
            max-width: 150px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .book-image {
            width: 60px;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .book-image:hover {
            transform: scale(1.1);
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

        @media (max-width: 768px) {
            .sidebar {
                width: 0;
                overflow: hidden;
            }
            .content {
                margin-left: 0;
            }
            .sidebar.active {
                width: 280px;
            }
            .menu-toggle {
                display: block;
            }
            .custom-container {
                margin: 10px;
                padding: 1rem;
            }
            .table-responsive {
                margin: 0 -10px;
            }
            .search-container {
                max-width: 100%;
            }
        }

        .menu-toggle {
            display: none;
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1001;
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .menu-toggle:hover {
            background: var(--secondary-color);
        }
    </style>
</head>

<body>
    <button class="menu-toggle" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar text-white" data-aos="fade-right" data-aos-duration="1500">
            <div class="sidebar-header">
                <h1 class="fw-bold mb-0">📚 Petugas</h1>
            </div>
            <ul class="nav flex-column mt-4">
                <li class="nav-item">
                    <a class="nav-link" href="../../landing/petugas.php">
                        <i class="fas fa-home"></i> Beranda
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="#buku">
                        <i class="fas fa-book"></i> Buku
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../siswa/siswa-data.php">
                        <i class="fas fa-users"></i> Siswa
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../aktivitas/aktivitas-data.php">
                        <i class="fas fa-history"></i> Aktivitas
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-circle"></i> <?= $_SESSION['username'] ?? 'User' ?>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="../profil.php"><i class="fas fa-user me-2"></i>Profil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#" onclick="confirmLogout()"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>

        <!-- Content utama -->
        <div class="content w-100">
            <div class="container-fluid p-4">
                <div class="header" data-aos="fade-up">
                    <h1 class="fw-bold">Manajemen Data Buku</h1>
                    <p class="mb-0">Kelola data buku perpustakaan</p>
                </div>

                <div class="custom-container" data-aos="fade-up" data-aos-delay="200">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="mb-0">Daftar Buku</h3>
                        <div class="d-flex align-items-center gap-3">
                            <span class="total-count">Total Buku: <?= $total_buku ?></span>
                            <a href="add-data-buku.php" class="btn btn-add">
                                <i class="fas fa-plus"></i> Tambah Buku
                            </a>
                        </div>
                    </div>

                    <div class="search-container">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" class="search-input" id="searchInput" placeholder="Cari data buku...">
                    </div>

                    <div class="table-responsive">
                        <table class="table custom-table" id="dataTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Judul Buku</th>
                                    <th>Kategori</th>
                                    <th>Pengarang</th>
                                    <th>Tahun Terbit</th>
                                    <th>Stok</th>
                                    <th>Gambar</th>
                                    <th>Deskripsi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1;
                                foreach ($data_buku as $buku): ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= $buku['judul_buku']; ?></td>
                                        <td><?= $buku['nama_kategori']; ?></td>
                                        <td><?= $buku['pengarang']; ?></td>
                                        <td><?= $buku['tahun_terbit']; ?></td>
                                        <td><?= $buku['stok']; ?></td>
                                        <td>
                                            <img src="<?= $buku['gambar']; ?>" 
                                                 alt="<?= $buku['judul_buku']; ?>" 
                                                 class="book-image"
                                                 onclick="openLightbox(this.src, this.alt)">
                                        </td>
                                        <td><?= mb_strimwidth($buku['deskripsi'], 0, 60, '...'); ?></td>
                                        <td>
                                            <div class="d-grid gap-2">
                                                <a href="edit-data-buku.php?id=<?= $buku['id_buku']; ?>" class="btn btn-action btn-edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="delete-data-buku.php?id=<?= $buku['id_buku']; ?>" class="btn btn-action btn-delete" onclick="return confirm('Yakin ingin menghapus buku ini?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <div class="no-results" id="noResults">
                            <i class="fas fa-search fa-2x mb-3"></i>
                            <p>Tidak ada data buku yang ditemukan</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lightbox -->
    <div class="lightbox" id="imageLightbox">
        <div class="lightbox-content">
            <span class="lightbox-close" onclick="closeLightbox()">
                <i class="fas fa-times"></i>
            </span>
            <img src="" alt="" id="lightboxImage">
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();

        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('active');
        }

        function confirmLogout() {
            if (confirm("Yakin ingin logout?")) {
                window.location.href = "../../config/logout.php";
            }
        }

        // Search functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const table = document.getElementById('dataTable');
            const noResults = document.getElementById('noResults');
            const rows = table.getElementsByTagName('tr');

            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                let hasResults = false;

                // Skip the header row (index 0)
                for (let i = 1; i < rows.length; i++) {
                    const row = rows[i];
                    const cells = row.getElementsByTagName('td');
                    let rowVisible = false;

                    // Check each cell in the row (except the last one which contains buttons)
                    for (let j = 0; j < cells.length - 1; j++) {
                        const cell = cells[j];
                        if (cell.textContent.toLowerCase().includes(searchTerm)) {
                            rowVisible = true;
                            hasResults = true;
                            break;
                        }
                    }

                    row.style.display = rowVisible ? '' : 'none';
                }

                // Show/hide no results message
                noResults.style.display = hasResults ? 'none' : 'block';
            });
        });

        // Lightbox functionality
        function openLightbox(imageSrc, imageAlt) {
            const lightbox = document.getElementById('imageLightbox');
            const lightboxImg = document.getElementById('lightboxImage');
            
            lightboxImg.src = imageSrc;
            lightboxImg.alt = imageAlt;
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
    </script>
</body>

</html>
