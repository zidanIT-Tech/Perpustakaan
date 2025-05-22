<?php
include("../config/controller.php");

session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../loginRegistrasi/loginUpdate.php?error=Silakan login dulu.");
    exit();
}

if ($_SESSION['level'] !== 'petugas') {
    header("Location: ../loginRegistrasi/loginUpdate.php?error=Akses ditolak!");
    exit();
}

$data_akun = select("SELECT * FROM login");
$total_siswa = select("SELECT COUNT(*) as total FROM login WHERE level='siswa'")[0]['total'];
$total_kategori = select("SELECT COUNT(*) as total FROM kategori")[0]['total'];
$kategori_buku = select("SELECT * FROM kategori");
$total_buku = select("SELECT COUNT(*) as total FROM buku")[0]['total'];

global $db;
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Petugas - Perpustakaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            padding: 2rem;
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

        .nav-link:hover {
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
            padding: 40px;
            border-radius: 15px;
            color: white;
            margin-bottom: 30px;
            text-align: center;
            box-shadow: var(--card-shadow);
        }

        .stats-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            height: 100%;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--hover-shadow);
        }

        .stats-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
            color: var(--secondary-color);
            background: rgba(52, 152, 219, 0.1);
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
        }

        .stats-number {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 5px;
            color: var(--primary-color);
        }

        .stats-label {
            color: #666;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .custom-container {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }

        .custom-container h3 {
            font-size: 1.2rem;
            margin-bottom: 1rem;
        }

        .custom-table {
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            margin-bottom: 0;
        }

        .custom-table thead th {
            background-color: var(--primary-color);
            color: white;
            padding: 10px 15px;
            font-weight: 600;
            font-size: 0.9rem;
            border: none;
        }

        .custom-table tbody td {
            padding: 8px 15px;
            vertical-align: middle;
            font-size: 0.9rem;
            border-bottom: 1px solid #f0f0f0;
        }

        .custom-table tbody tr:last-child td {
            border-bottom: none;
        }

        .custom-table tbody tr:hover {
            background-color: rgba(52, 152, 219, 0.05);
            transition: all 0.2s ease;
        }

        .search-container {
            position: relative;
            margin-bottom: 15px;
            max-width: 250px;
        }

        .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
            font-size: 0.9rem;
        }

        .search-input {
            width: 100%;
            padding: 8px 12px 8px 35px;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            font-size: 0.9rem;
            transition: all 0.2s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.1);
        }

        .no-results {
            text-align: center;
            padding: 30px;
            color: #666;
            display: none;
            font-size: 0.9rem;
        }

        .no-results i {
            color: #ccc;
            margin-bottom: 10px;
            font-size: 1.5rem;
        }

        .btn-action {
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: 500;
            transition: all 0.2s ease;
            font-size: 0.8rem;
        }

        .btn-edit {
            background-color: var(--warning-color);
            color: white;
            border: none;
        }

        .btn-delete {
            background-color: var(--accent-color);
            color: white;
            border: none;
        }

        .btn-delete:hover {
            background-color: #c0392b;
            transform: translateY(-1px);
        }

        .btn-delete i {
            margin-right: 4px;
            font-size: 0.8rem;
        }

        .btn-add {
            background-color: var(--secondary-color);
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .btn-add:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .status-badge {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .status-active {
            background-color: var(--success-color);
            color: white;
        }

        .status-inactive {
            background-color: var(--accent-color);
            color: white;
        }

        .dark-mode-toggle {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .dark-mode-toggle:hover {
            transform: translateY(-3px);
            box-shadow: var(--hover-shadow);
        }

        body.dark-mode {
            background-color: var(--dark-bg);
            color: var(--dark-text);
        }

        .dark-mode .custom-container,
        .dark-mode .stats-card {
            background: #1e1e1e;
            color: var(--dark-text);
        }

        .dark-mode .stats-number {
            color: var(--dark-text);
        }

        .dark-mode .stats-label {
            color: #aaa;
        }

        .dark-mode .custom-table tbody td {
            border-bottom-color: #333;
            color: var(--dark-text);
        }

        .dark-mode .custom-table tbody tr:hover {
            background-color: rgba(52, 152, 219, 0.1);
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

            .dashboard-card {
                margin-bottom: 20px;
            }
        }

        .table-responsive {
            max-height: 300px;
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

        .chart-container {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: var(--card-shadow);
            margin-bottom: 30px;
            height: 400px;
        }

        .chart-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 20px;
            text-align: center;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 30px;
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
                    <a class="nav-link active" href="petugas.php">
                        <i class="fas fa-home"></i> Beranda
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../petugas/buku/buku.php">
                        <i class="fas fa-book"></i> Buku
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../petugas/siswa/siswa-data.php">
                        <i class="fas fa-users"></i> Siswa
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../petugas/aktivitas/aktivitas-data.php">
                        <i class="fas fa-history"></i> Aktivitas
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-circle"></i> <?= $_SESSION['username'] ?? 'User' ?>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="../petugas/profil.php"><i class="fas fa-user me-2"></i>Profil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#" onclick="confirmLogout()"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>

        <!-- Content utama -->
        <div class="content w-100">
            <div class="header" data-aos="fade-up">
                <h1 class="fw-bold">Dashboard Petugas</h1>
                <p class="mb-0">Selamat datang di dashboard perpustakaan</p>
            </div>

            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="chart-container" data-aos="fade-up">
                        <h3 class="chart-title">Statistik Perpustakaan</h3>
                        <canvas id="statsChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="stats-grid">
                <div class="stats-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="stats-icon">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="stats-number"><?= $total_buku ?></div>
                    <div class="stats-label">Total Buku</div>
                </div>
                <div class="stats-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="stats-icon">
                        <i class="fas fa-book-reader"></i>
                    </div>
                    <div class="stats-number">0</div>
                    <div class="stats-label">Total Buku Dipinjam</div>
                </div>
                <div class="stats-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="stats-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stats-number"><?= $total_siswa ?></div>
                    <div class="stats-label">Total Siswa</div>
                </div>
                <div class="stats-card" data-aos="fade-up" data-aos-delay="400">
                    <div class="stats-icon">
                        <i class="fas fa-tags"></i>
                    </div>
                    <div class="stats-number"><?= $total_kategori ?></div>
                    <div class="stats-label">Total Kategori</div>
                </div>
            </div>

            <!-- Two Column Layout -->
            <div class="row">
                <!-- Kategori Table -->
                <div class="col-md-4 mb-4">
                    <div class="custom-container" data-aos="fade-up" data-aos-delay="400">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3 class="mb-0">Daftar Kategori</h3>
                        </div>
                        <div class="search-container">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" class="search-input" id="searchKategori" placeholder="Cari kategori...">
                        </div>
                        <div class="table-responsive">
                            <table class="table custom-table" id="kategoriTable">
                                <thead>
                                    <tr>
                                        <th style="width: 15%">No</th>
                                        <th>Nama Kategori</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1;
                                    foreach ($kategori_buku as $kategori): ?>
                                        <tr>
                                            <td class="text-center"><?= $no++; ?></td>
                                            <td><?= $kategori['nama']; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <div class="no-results" id="noResultsKategori">
                                <i class="fas fa-search"></i>
                                <p>Tidak ada kategori yang ditemukan</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <button class="dark-mode-toggle" id="toggleDarkMode" onclick="toggleDarkMode()" style="display: none;">
            <i class="fas fa-moon"></i>
        </button>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();

        // Chart initialization
        const ctx = document.getElementById('statsChart').getContext('2d');
        const statsChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Total Buku', 'Buku Dipinjam', 'Total Siswa', 'Total Kategori'],
                datasets: [{
                    label: 'Statistik Perpustakaan',
                    data: [<?= $total_buku ?>, 0, <?= $total_siswa ?>, <?= $total_kategori ?>],
                    backgroundColor: [
                        'rgba(52, 152, 219, 0.8)',
                        'rgba(231, 76, 60, 0.8)',
                        'rgba(46, 204, 113, 0.8)',
                        'rgba(155, 89, 182, 0.8)'
                    ],
                    borderColor: [
                        'rgba(52, 152, 219, 1)',
                        'rgba(231, 76, 60, 1)',
                        'rgba(46, 204, 113, 1)',
                        'rgba(155, 89, 182, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.dataset.label}: ${context.raw}`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });

        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('active');
        }

        function confirmLogout() {
            if (confirm("Yakin ingin logout?")) {
                window.location.href = "../config/logout.php";
            }
        }

        // Search functionality for kategori
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchKategori');
            const table = document.getElementById('kategoriTable');
            const noResults = document.getElementById('noResultsKategori');
            const rows = table.getElementsByTagName('tr');

            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                let hasResults = false;

                // Skip the header row (index 0)
                for (let i = 1; i < rows.length; i++) {
                    const row = rows[i];
                    const cells = row.getElementsByTagName('td');
                    let rowVisible = false;

                    // Check each cell in the row
                    for (let j = 0; j < cells.length; j++) {
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
    </script>
</body>

</html>