<?php
include "../../config/controller.php";
include "../../middleware/cek-siswa.php";

if (!isset($_SESSION['username'])) {
    header("Location: ../../config/login.php");
    exit();
}

$username = $_SESSION['username'];
$user_data = select("SELECT * FROM login WHERE username = '$username'")[0];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_username = $_POST['username'];
    
    // Check if new username already exists
    $check_username = select("SELECT * FROM login WHERE username = '$new_username' AND username != '$username'");
    if (empty($check_username)) {
        // Update data in database
        $update = mysqli_query($db, "UPDATE login SET 
            username = '$new_username'
            WHERE username = '$username'");
        
        if ($update) {
            $_SESSION['username'] = $new_username; // Update session username
            echo "<script>alert('Data berhasil diperbarui!'); window.location.href='profil.php';</script>";
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
    <link rel="stylesheet" href="../../assets/css/landing-home-siswa.css">
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

        .profile-container {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            margin: 2rem auto;
            max-width: 800px;
            box-shadow: var(--card-shadow);
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
            background: var(--light-bg);
            border-radius: 12px;
            margin: 0;
            font-size: 1rem;
            color: var(--text-color);
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

        .student-info {
            margin-top: 1.5rem;
        }

        .student-info .badge {
            padding: 8px 15px;
            font-size: 0.9rem;
            border-radius: 20px;
            margin: 0 5px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .badge.bg-primary {
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color)) !important;
        }

        .badge.bg-secondary {
            background: linear-gradient(135deg, #6c757d, #495057) !important;
        }

        .row {
            margin-bottom: 1.5rem;
        }

        .profile-section {
            background: var(--light-bg);
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: 2px solid #eef2f7;
        }

        .profile-section:hover {
            border-color: var(--secondary-color);
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

        .nav-link {
            color: rgba(255,255,255,0.9) !important;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            color: white !important;
            transform: translateY(-2px);
        }

        .nav-link.active {
            color: white !important;
            font-weight: 600;
        }

        .dropdown-menu {
            border: none;
            box-shadow: var(--card-shadow);
            border-radius: 12px;
        }

        .dropdown-item {
            padding: 0.7rem 1.5rem;
            transition: all 0.3s ease;
        }

        .dropdown-item:hover {
            background: var(--light-bg);
            color: var(--primary-color);
        }

        .dropdown-item.active {
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
            color: white;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="../../landing/siswa.php">
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
                        <a class="nav-link" href="../../landing/siswa.php">
                            <i class="fas fa-home me-1"></i> Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../../buku/semua-daftar-buku.php">
                            <i class="fas fa-book me-1"></i> Daftar Buku
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle active" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user me-1"></i><?php echo htmlspecialchars($username); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item active" href="profil.php">
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
        <div class="profile-container" data-aos="fade-up">
            <div class="profile-header">
                <div class="profile-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <h2>Profil Saya</h2>
                <p class="text-muted">Kelola informasi profil Anda</p>
                <div class="student-info mt-3">
                    <span class="badge bg-primary me-2">
                        <i class="fas fa-user-graduate me-1"></i>Siswa
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
                            <p class="form-control-plaintext"><?php echo htmlspecialchars($user_data['name']); ?></p>
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
                window.location.href = "../../config/logout.php";
            }
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
    </script>
</body>
</html>
