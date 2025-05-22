<?php
include "../../config/controller.php";
include("../../middleware/cek-admin.php");

if (isset($_POST['regist'])) {
    if (create_akun($_POST) > 0) {
        echo "<script>
            alert('Data Berhasil Ditambahkan');
            document.location.href='semua-data.php';
            </script>";
    } else {
        echo "<script>
            alert('Data Gagal Ditambahkan');
            document.location.href='semua-data.php';
            </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Semua Data - Perpustakaan</title>
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
            --dark-bg: #121212;
            --dark-text: #ffffff;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light-bg);
            color: var(--text-color);
            transition: all 0.3s ease;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .register-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
            width: 100%;
            max-width: 1200px;
            margin: 20px auto;
        }

        .left-section {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .left-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('https://perpustakaan.poltekparmakassar.ac.id/storage/2020/07/trust-tru-katsande-BTAAcbO9Gco-unsplash-scaled.jpg') center/cover;
            opacity: 0.1;
        }

        .library-image {
            width: 100%;
            max-width: 400px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            transition: transform 0.3s ease;
        }

        .library-image:hover {
            transform: scale(1.05);
        }

        .welcome-text {
            font-size: 4rem;
            font-weight: 700;
            color: rgba(255,255,255,0.1);
            position: absolute;
            bottom: 20px;
            left: 20px;
            z-index: 1;
        }

        .right-section {
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-container {
            max-width: 400px;
            width: 100%;
            margin: 0 auto;
        }

        .form-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: var(--primary-color);
        }

        .form-subtitle {
            font-size: 1.2rem;
            color: var(--secondary-color);
            margin-bottom: 2rem;
        }

        .form-control {
            border-radius: 10px;
            padding: 12px 20px;
            border: 2px solid #e0e0e0;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }

        .input-group-text {
            background-color: transparent;
            border: 2px solid #e0e0e0;
            border-left: none;
            border-radius: 0 10px 10px 0;
        }

        .input-group .form-control {
            border-right: none;
            border-radius: 10px 0 0 10px;
        }

        .btn-register {
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
            border: none;
            padding: 12px 30px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            color: white;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        @media (max-width: 768px) {
            .register-container {
                margin: 10px;
            }

            .left-section {
                display: none;
            }

            .right-section {
                padding: 20px;
            }

            .form-title {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="row g-0">
            <!-- Left Section -->
            <div class="col-md-6 left-section">
                <img src="https://perpustakaan.poltekparmakassar.ac.id/storage/2020/07/trust-tru-katsande-BTAAcbO9Gco-unsplash-scaled.jpg" 
                     alt="Library" class="library-image">
                <div class="welcome-text">REGISTRASI</div>
            </div>

            <!-- Right Section -->
            <div class="col-md-6 right-section">
                <div class="form-container">
                    <h1 class="form-title">Buat Akun Baru</h1>
                    <p class="form-subtitle">Daftarkan diri Anda untuk mengakses perpustakaan</p>

                    <form action="" method="POST">
                        <div class="mb-4">
                            <label for="name" class="form-label">Nama Lengkap</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="name" name="name" 
                                       placeholder="Masukkan nama lengkap" required>
                                <span class="input-group-text">
                                    <i class="fas fa-user"></i>
                                </span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="username" class="form-label">Username</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="username" name="username" 
                                       placeholder="Masukkan username" required>
                                <span class="input-group-text">
                                    <i class="fas fa-user-circle"></i>
                                </span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password" name="password" 
                                       placeholder="Masukkan password" required>
                                <span class="input-group-text">
                                    <i class="fas fa-lock"></i>
                                </span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="level" class="form-label">Level</label>
                            <div class="input-group">
                                <select class="form-control" id="level" name="level" required>
                                    <option value="">Pilih Level</option>
                                    <option value="siswa">siswa</option>
                                    <option value="admin">admin</option>
                                    <option value="petugas">petugas</option>
                                </select>
                                <span class="input-group-text">
                                    <i class="fas fa-user"></i>
                                </span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="status" class="form-label">Status</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="status" name="status" 
                                       placeholder="Masukkan Status" required>
                                <span class="input-group-text">
                                    <i class="fas fa-hashtag"></i>
                                </span>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <button type="submit" name="regist" class="btn btn-register">
                                <i class="fas fa-user-plus me-2"></i>Registrasi
                            </button>
                            <a href="semua-data.php" class="text-primary text-decoration-none">
                                <i class="fas fa-arrow-left me-1"></i>Kembali
                            </a>
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
    </script>
</body>
</html>