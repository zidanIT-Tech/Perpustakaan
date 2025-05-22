<?php
include("../config/controller.php");

session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../loginRegistrasi/loginUpdate.php?error=Silakan login dulu.");
    exit();
}

if ($_SESSION['level'] !== 'admin') {
    header("Location: ../loginRegistrasi/loginUpdate.php?error=Akses ditolak!");
    exit();
}

$data_akun = select("SELECT * FROM login WHERE level='petugas'");
$kategori_buku = select("SELECT * FROM kategori");
$total_siswa = select("SELECT COUNT(*) as total FROM login WHERE level='siswa'")[0]['total'];
$total_kategori = select("SELECT COUNT(*) as total FROM kategori")[0]['total'];
$total_petugas = select("SELECT COUNT(*) as total FROM login WHERE level='petugas'")[0]['total'];
$total_buku = select("SELECT COUNT(*) as total FROM buku")[0]['total'];
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Perpustakaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin/landing-admin.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
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

        .chart-container {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
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

        .stats-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-5px);
        }

        .stats-icon {
            font-size: 2rem;
            color: var(--secondary-color);
            margin-bottom: 10px;
        }

        .stats-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 5px;
        }

        .stats-label {
            color: #666;
            font-size: 1rem;
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
                <h1 class="fw-bold mb-0">📚 Admin</h1>
            </div>
            <ul class="nav flex-column mt-4">
                <li class="nav-item">
                    <a class="nav-link active" href="admin.php">
                        <i class="fas fa-home"></i> Beranda
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../admin/buku/buku.php">
                        <i class="fas fa-book"></i> Buku
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../admin/kategori/kategori-data.php">
                        <i class="fas fa-tags"></i> Kategori
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../admin/siswa/siswa-data.php">
                        <i class="fas fa-users"></i> Siswa
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../admin/semuaData/semua-data.php">
                        <i class="fas fa-database"></i> Semua Data
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../admin/aktivitas/aktivitas-data.php">
                        <i class="fas fa-history"></i> Aktivitas
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-circle"></i> <?= $_SESSION['username'] ?? 'User' ?>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="../admin/profil.php"><i class="fas fa-user me-2"></i>Profil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#" onclick="confirmLogout()"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>

        <!-- Content utama -->
        <div class="content w-100">
            <div class="header" data-aos="fade-up">
                <h1 class="fw-bold">Dashboard Admin</h1>
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

            <!-- Data Siswa Section -->
            <div class="custom-container" data-aos="fade-up" data-aos-delay="400">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="mb-0">Daftar Petugas</h3>
                    <div class="d-flex align-items-center gap-3">
                        <span class="total-count">Total Petugas: <?= $total_petugas ?></span>
                        <a href="../admin/petugas/add-data-petugas.php" class="btn btn-add">
                            <i class="fas fa-user-plus"></i> Tambah Petugas
                        </a>
                    </div>
                </div>

                <div class="search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" id="searchInput" placeholder="Cari petugas...">
                </div>

                <div class="table-responsive">
                    <table class="table custom-table" id="dataTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Username</th>
                                <th>Password</th>
                                <th>Level</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1;
                            foreach ($data_akun as $akun): ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= $akun['name']; ?></td>
                                    <td><?= $akun['username']; ?></td>
                                    <td><?= $akun['password']; ?></td>
                                    <td><?= $akun['level']; ?></td>
                                    <td>
                                        <span class="status-badge <?= $akun['status'] == 'active' ? 'status-active' : 'status-inactive' ?>">
                                            <?= ucfirst($akun['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="../admin/petugas/edit-data-petugas.php?id=<?= $akun['id']; ?>"
                                            class="btn btn-action btn-edit">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <a href="../admin/petugas/delete-data-petugas.php?id=<?= $akun['id']; ?>"
                                            class="btn btn-action btn-delete"
                                            onclick="return confirm('Yakin ingin menghapus akun ini?')">
                                            <i class="fas fa-trash"></i>
                                        </a>

                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="no-results" id="noResults">
                        <i class="fas fa-search fa-2x mb-3"></i>
                        <p>Tidak ada petugas yang ditemukan</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <button class="dark-mode-toggle" id="toggleDarkMode" onclick="toggleDarkMode()" style="display: none;">
        <i class="fas fa-moon"></i>
    </button>

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

        function toggleDarkMode() {
            document.body.classList.toggle("dark-mode");
            const icon = document.querySelector("#toggleDarkMode i");
            if (document.body.classList.contains("dark-mode")) {
                icon.classList.remove("fa-moon");
                icon.classList.add("fa-sun");
            } else {
                icon.classList.remove("fa-sun");
                icon.classList.add("fa-moon");
            }
        }

        function hapusPesan(id) {
            if (confirm("Yakin ingin menghapus pesan ini?")) {
                window.location.href = "../admin/pesan/delete-pesan.php?id=" + id;
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
    </script>
</body>

</html>