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

$data_akun = select("SELECT * FROM login WHERE level = 'siswa'");
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Siswa - Perpustakaan</title>
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

        .btn-action {
            padding: 8px 15px;
            border-radius: 5px;
            font-weight: 500;
            transition: all 0.3s ease;
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
                    <a class="nav-link active" href="#siswa">
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
                    <h1 class="fw-bold">Manajemen Data Siswa</h1>
                    <p class="mb-0">Kelola data siswa perpustakaan</p>
                </div>

                <div class="custom-container" data-aos="fade-up" data-aos-delay="200" id="siswa">
                    <h3 class="mb-4">Daftar Siswa</h3>

                    <div class="search-container">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" class="search-input" id="searchInput" placeholder="Cari data siswa...">
                    </div>

                    <div class="table-responsive">
                        <table class="table custom-table" id="dataTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Username</th>
                                    <th>Level</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1;
                                foreach ($data_akun as $akun): ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= $akun['name']; ?></td>
                                        <td><?= $akun['username']; ?></td>
                                        <td><?= $akun['level']; ?></td>
                                        <td>
                                            <span class="status-badge <?= $akun['status'] == 'active' ? 'status-active' : 'status-inactive' ?>">
                                                <?= ucfirst($akun['status']); ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <div class="no-results" id="noResults">
                            <i class="fas fa-search fa-2x mb-3"></i>
                            <p>Tidak ada data siswa yang ditemukan</p>
                        </div>
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
