<?php
include "../config/controller.php";
include "../middleware/cek-admin.php";

if (!isset($_SESSION['username'])) {
    header("Location: ../config/login.php");
    exit();
}

$username = $_SESSION['username'];
$user_data = select("SELECT * FROM login WHERE username = '$username'")[0];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_username = $_POST['username'];
    $name = $_POST['name'];
    
    // Check if new username already exists
    $check_username = select("SELECT * FROM login WHERE username = '$new_username' AND username != '$username'");
    if (empty($check_username)) {
        // Update data in database
        $update = mysqli_query($db, "UPDATE login SET 
            username = '$new_username',
            name = '$name'
            WHERE username = '$username'");
        
        if ($update) {
            $_SESSION['username'] = $new_username; // Update session username
            echo "<script>
                alert('Data berhasil diperbarui!');
                window.location.href = 'profil.php';
            </script>";
        } else {
            echo "<script>alert('Gagal memperbarui data!');</script>";
        }
    } else {
        echo "<script>alert('Username sudah digunakan!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - Perpustakaan SMKN 7 Samarinda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin/landing-admin.css">
    <style>
        .profile-container {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            margin: 2rem auto;
            max-width: 800px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        .profile-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        .profile-header {
            text-align: center;
            margin-bottom: 3rem;
            position: relative;
        }
        .profile-header::after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 50px;
            height: 3px;
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
            border-radius: 3px;
        }
        .profile-avatar {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 3.5rem;
            color: white;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        .profile-avatar:hover {
            transform: scale(1.05);
        }
        .form-label {
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .form-control {
            border-radius: 12px;
            padding: 0.85rem;
            border: 2px solid #eef2f7;
            transition: all 0.3s ease;
            font-size: 1rem;
        }
        .form-control:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.15);
        }
        .form-control-plaintext {
            padding: 0.85rem;
            background: #f8fafc;
            border-radius: 12px;
            margin: 0;
            font-size: 1rem;
            color: #2d3748;
            border: 2px solid #eef2f7;
        }
        .btn-update {
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
            color: white;
            padding: 14px 35px;
            border-radius: 30px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            font-size: 1rem;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .btn-update:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        }
        .admin-info {
            margin-top: 1.5rem;
        }
        .admin-info .badge {
            padding: 8px 15px;
            font-size: 0.9rem;
            border-radius: 20px;
            margin: 0 5px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .badge.bg-primary {
            background: linear-gradient(135deg, #4a90e2, #357abd) !important;
        }
        .badge.bg-secondary {
            background: linear-gradient(135deg, #6c757d, #495057) !important;
        }
        .row {
            margin-bottom: 1.5rem;
        }
        .profile-section {
            background: #f8fafc;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: 2px solid #eef2f7;
        }
        .profile-section:hover {
            border-color: var(--secondary-color);
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
                    <a class="nav-link" href="../landing/admin.php">
                        <i class="fas fa-home"></i> Beranda
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="buku/buku.php">
                        <i class="fas fa-book"></i> Buku
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="kategori/kategori-data.php">
                        <i class="fas fa-tags"></i> Kategori
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="siswa/siswa-data.php">
                        <i class="fas fa-users"></i> Siswa
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="semuaData/semua-data.php">
                        <i class="fas fa-database"></i> Semua Data
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="aktivitas/aktivitas-data.php">
                        <i class="fas fa-history"></i> Aktivitas
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle active" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-circle"></i> <?= $_SESSION['username'] ?? 'User' ?>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item active" href="profil.php"><i class="fas fa-user me-2"></i>Profil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#" onclick="confirmLogout()"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>

        <!-- Content utama -->
        <div class="content w-100">
            <div class="container">
                <div class="profile-container" data-aos="fade-up">
                    <div class="profile-header">
                        <div class="profile-avatar">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <h2>Profil Admin</h2>
                        <p class="text-muted">Kelola informasi profil Anda</p>
                        <div class="admin-info mt-3">
                            <span class="badge bg-primary me-2">
                                <i class="fas fa-user-shield me-1"></i>Administrator
                            </span>
                            <span class="badge bg-secondary">
                                <i class="fas fa-school me-1"></i>SMKN 7 SAMARINDA
                            </span>
                        </div>
                    </div>

                    <form method="POST" action="">
                        <div class="profile-section">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">Username</label>
                                    <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($username); ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Password</label>
                                    <p class="form-control-plaintext">••••••••</p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">Nama Lengkap</label>
                                    <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($user_data['name']); ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-update">
                                <i class="fas fa-save me-2"></i>Simpan Perubahan
                            </button>
                        </div>
                    </form>
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
            if (confirm("Apakah Anda yakin ingin logout?")) {
                window.location.href = "../config/logout.php";
            }
        }
    </script>
</body>
</html>



