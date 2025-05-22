<?php
include("../../config/controller.php");
include("../../middleware/cek-admin.php");

$data_akun = select("SELECT * FROM login");
$total_data = select("SELECT COUNT(*) as total FROM login")[0]['total'];
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semua Data - Perpustakaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/admin/semua-data.css">
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
                    <a class="nav-link" href="../../landing/admin.php">
                        <i class="fas fa-home"></i> Beranda
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../buku/buku.php">
                        <i class="fas fa-book"></i> Buku
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../kategori/kategori-data.php">
                        <i class="fas fa-book"></i> Kategori
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../siswa/siswa-data.php">
                        <i class="fas fa-users"></i> Siswa
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../semuaData/semua-data.php">
                        <i class="fas fa-database"></i> Semua Data
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
                    <h1 class="fw-bold">Manajemen Semua Data</h1>
                    <p class="mb-0">Kelola semua data perpustakaan</p>
                </div>

                <div class="custom-container" data-aos="fade-up" data-aos-delay="200" id="sdata">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="mb-0">Daftar Semua Data</h3>
                        <div class="d-flex align-items-center gap-3">
                            <span class="total-count">Total Data: <?= $total_data ?></span>
                            <a href="add-data-semua-data.php" class="btn btn-add">
                                <i class="fas fa-user-plus"></i> Tambah Data
                            </a>
                        </div>
                    </div>

                    <div class="search-container">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" class="search-input" id="searchInput" placeholder="Cari data...">
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
                                            <a href="edit-data-semua-data.php?id=<?= $akun['id']; ?>" 
                                               class="btn btn-action btn-edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="../../hapusakun.php?id=<?= $akun['id']; ?>" 
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
                            <p>Tidak ada data yang ditemukan</p>
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
