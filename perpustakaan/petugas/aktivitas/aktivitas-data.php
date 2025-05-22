<?php
include("../../config/controller.php");

session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../../loginRegistrasi/loginUpdate.php?error=Silakan login dulu.");
    exit();
}

if ($_SESSION['level'] !== 'petugas') {
    header("Location: ../../loginRegistrasi/loginUpdate.php?error=Akses ditolak!");
    exit();
}

// Get all activities from the combined log_aktivitas table
$log_aktivitas = select("SELECT * FROM log_aktivitas ORDER BY waktu DESC LIMIT 10");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aktivitas Data - Perpustakaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../../../assets/css/admin/kategori-data.css">
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
        .custom-container {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            margin: 20px;
        }
        .header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            padding: 40px 20px;
            border-radius: 15px;
            color: white;
            margin-bottom: 30px;
            text-align: center;
        }
        .custom-table {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .custom-table thead th {
            background-color: var(--primary-color);
            color: white;
            padding: 15px;
            font-weight: 600;
        }
        .custom-table tbody td {
            padding: 15px;
            vertical-align: middle;
        }
        .search-container {
            margin-bottom: 20px;
            position: relative;
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
        .no-results {
            text-align: center;
            padding: 20px;
            color: #666;
            display: none;
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
        }
        .activity-timeline {
            position: relative;
            padding: 20px 0;
        }
        
        .activity-timeline::-webkit-scrollbar {
            width: 8px;
        }
        
        .activity-timeline::-webkit-scrollbar-track {
            background: #f0f0f0;
            border-radius: 4px;
        }
        
        .activity-timeline::-webkit-scrollbar-thumb {
            background-color: var(--secondary-color);
            border-radius: 4px;
        }
        
        .activity-timeline::-webkit-scrollbar-thumb:hover {
            background-color: var(--primary-color);
        }
        .activity-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
            display: flex;
            align-items: flex-start;
            transition: transform 0.3s ease;
        }
        .activity-card:hover {
            transform: translateY(-5px);
        }
        .activity-icon {
            background: #f8f9fa;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 20px;
            flex-shrink: 0;
        }
        .activity-icon i {
            font-size: 1.5rem;
        }
        .activity-content {
            flex-grow: 1;
        }
        .activity-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        .activity-title {
            margin: 0;
            font-size: 1.1rem;
            color: var(--primary-color);
        }
        .activity-time {
            color: #666;
            font-size: 0.9rem;
        }
        .activity-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 10px;
            margin-bottom: 15px;
        }
        .activity-info {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .activity-label {
            color: #666;
            font-size: 0.9rem;
        }
        .activity-value {
            color: var(--text-color);
            font-weight: 500;
        }
        .activity-actions {
            display: flex;
            justify-content: flex-end;
        }
        .btn-action {
            padding: 8px 15px;
            border-radius: 6px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        .btn-delete {
            background-color: var(--accent-color);
            color: white;
            border: none;
        }
        .btn-delete:hover {
            background-color: #c0392b;
            color: white;
        }
        .no-activity {
            text-align: center;
            padding: 40px;
            color: #666;
        }
        .no-activity i {
            color: #ddd;
        }
        @media (max-width: 768px) {
            .activity-details {
                grid-template-columns: 1fr;
            }
            .activity-header {
                flex-direction: column;
                align-items: flex-start;
            }
            .activity-time {
                margin-top: 5px;
            }
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
                    <a class="nav-link" href="../buku/buku.php">
                        <i class="fas fa-book"></i> Buku
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../siswa/siswa-data.php">
                        <i class="fas fa-users"></i> Siswa
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="#aktivitas">
                        <i class="fas fa-history"></i> Aktivitas
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-circle"></i> <?= $_SESSION['username'] ?? 'User' ?>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="../../profil.php"><i class="fas fa-user me-2"></i>Profil</a></li>
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
                    <h1 class="fw-bold">Aktivitas Data</h1>
                    <p class="mb-0">Riwayat aktivitas perpustakaan</p>
                </div>
                <div class="custom-container" data-aos="fade-up" data-aos-delay="200" id="aktivitas">
                    <h3 class="mb-4">Daftar Aktivitas</h3>
                    <div class="search-container">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" class="search-input" id="searchInput" placeholder="Cari aktivitas...">
                    </div>
                    <div class="activity-timeline">
                        <?php if (count($log_aktivitas) > 0): foreach ($log_aktivitas as $log): ?>
                            <div class="activity-card" data-aos="fade-up">
                                <div class="activity-icon">
                                    <?php
                                    $icon = 'fa-book';
                                    $color = 'var(--primary-color)';
                                    if ($log['aksi'] == 'Tambah') {
                                        $icon = 'fa-plus-circle';
                                        $color = 'var(--success-color)';
                                    } elseif ($log['aksi'] == 'Edit') {
                                        $icon = 'fa-edit';
                                        $color = 'var(--warning-color)';
                                    } elseif ($log['aksi'] == 'Hapus') {
                                        $icon = 'fa-trash';
                                        $color = 'var(--accent-color)';
                                    }
                                    ?>
                                    <i class="fas <?= $icon ?>" style="color: <?= $color ?>"></i>
                                </div>
                                <div class="activity-content">
                                    <div class="activity-header">
                                        <h5 class="activity-title"><?= htmlspecialchars($log['nama_data']) ?></h5>
                                        <span class="activity-time"><?= date('d M Y H:i', strtotime($log['waktu'])) ?></span>
                                    </div>
                                    <div class="activity-details">
                                        <div class="activity-info">
                                            <span class="activity-label">Tipe:</span>
                                            <span class="activity-value"><?= ucfirst($log['tipe_data']) ?></span>
                                        </div>
                                        <div class="activity-info">
                                            <span class="activity-label">Aksi:</span>
                                            <span class="activity-value"><?= htmlspecialchars($log['aksi']) ?></span>
                                        </div>
                                        <div class="activity-info">
                                            <span class="activity-label">User:</span>
                                            <span class="activity-value"><?= htmlspecialchars($log['user_aksi']) ?></span>
                                        </div>
                                    </div>
                                    <div class="activity-actions">
                                        <a href="delete-aktivitas.php?id=<?= $log['id_log']; ?>&type=<?= $log['tipe_data'] ?>" 
                                           class="btn btn-action btn-delete" 
                                           onclick="return confirm('Yakin ingin menghapus aktivitas ini?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; else: ?>
                            <div class="no-activity">
                                <i class="fas fa-history fa-3x mb-3"></i>
                                <p>Tidak ada aktivitas</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
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
            const activityCards = document.querySelectorAll('.activity-card');
            const noResults = document.createElement('div');
            noResults.className = 'no-activity';
            noResults.innerHTML = '<i class="fas fa-search fa-3x mb-3"></i><p>Tidak ada aktivitas ditemukan</p>';
            
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                let hasResults = false;
                
                activityCards.forEach(card => {
                    const content = card.textContent.toLowerCase();
                    if (content.includes(searchTerm)) {
                        card.style.display = '';
                        hasResults = true;
                    } else {
                        card.style.display = 'none';
                    }
                });
                
                const timeline = document.querySelector('.activity-timeline');
                const existingNoResults = timeline.querySelector('.no-activity');
                
                if (!hasResults) {
                    if (!existingNoResults) {
                        timeline.appendChild(noResults);
                    }
                } else if (existingNoResults) {
                    existingNoResults.remove();
                }
            });
        });
    </script>
</body>
</html>
