<?php
include "../../config/controller.php";

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

//mengambil id buku
if (isset($_GET['id'])) {
    $id_buku = (int)$_GET['id'];
    $buku = select("SELECT * FROM buku WHERE id_buku=$id_buku")[0];
    $kategori = select("SELECT * FROM kategori");
    $rak = select("SELECT * FROM rak");
    
    if (isset($_POST['ubah'])) {
        if (ubah_dataBuku($_POST) > 0) {
            echo "<script>
                    alert('Data Buku Berhasil Diubah');
                    document.location.href='buku.php';
                    </script>";
        } else {
            echo "<script>
                    alert('Data Buku Gagal Diubah');
                    document.location.href='buku.php';
                    </script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Data Buku - Perpustakaan</title>
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

        .login-container {
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

        .btn-primary {
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
            border: none;
            padding: 12px 30px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        @media (max-width: 768px) {
            .login-container {
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
    <div class="login-container">
        <div class="row g-0">
            <!-- Left Section -->
            <div class="col-md-6 left-section">
                <img src="https://perpustakaan.poltekparmakassar.ac.id/storage/2020/07/trust-tru-katsande-BTAAcbO9Gco-unsplash-scaled.jpg" 
                     alt="Library" class="library-image">
                <div class="welcome-text">UBAH</div>
            </div>

            <!-- Right Section -->
            <div class="col-md-6 right-section">
                <div class="form-container">
                    <h1 class="form-title">Ubah Data Buku</h1>
                    <p class="form-subtitle">Silakan ubah data buku perpustakaan</p>

                    <form action="" method="POST">
                        <input type="hidden" name="id_buku" value="<?= $buku['id_buku']; ?>">
                        
                        <div class="mb-4">
                            <label for="judul_buku" class="form-label">Judul Buku</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="judul_buku" name="judul_buku" 
                                       placeholder="Masukkan judul buku" value="<?= $buku['judul_buku']; ?>" required>
                                <span class="input-group-text">
                                    <i class="fas fa-book"></i>
                                </span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="id_kategori" class="form-label">Kategori</label>
                            <div class="input-group">
                                <select class="form-control" id="id_kategori" name="id_kategori" required>
                                    <option value="">Pilih Kategori</option>
                                    <?php foreach ($kategori as $k): ?>
                                        <option value="<?= $k['id_kategori'] ?>" <?= ($k['id_kategori'] == $buku['id_kategori']) ? 'selected' : '' ?>>
                                            <?= $k['nama'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="input-group-text">
                                    <i class="fas fa-tags"></i>
                                </span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="id_rak" class="form-label">Rak</label>
                            <div class="input-group">
                                <select class="form-control" id="id_rak" name="id_rak" required>
                                    <option value="">Pilih Rak</option>
                                    <?php foreach ($rak as $r): ?>
                                        <option value="<?= $r['id_rak'] ?>" <?= ($r['id_rak'] == $buku['id_rak']) ? 'selected' : '' ?>>
                                            Rak <?= $r['no_rak'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="input-group-text">
                                    <i class="fas fa-shelf"></i>
                                </span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="pengarang" class="form-label">Pengarang</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="pengarang" name="pengarang" 
                                       placeholder="Masukkan nama pengarang" value="<?= $buku['pengarang']; ?>" required>
                                <span class="input-group-text">
                                    <i class="fas fa-user-edit"></i>
                                </span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="tahun_terbit" class="form-label">Tahun Terbit</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="tahun_terbit" name="tahun_terbit" 
                                       placeholder="Masukkan tahun terbit" value="<?= $buku['tahun_terbit']; ?>" required>
                                <span class="input-group-text">
                                    <i class="fas fa-calendar"></i>
                                </span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="stok" class="form-label">Stok</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="stok" name="stok" 
                                       placeholder="Masukkan jumlah stok" value="<?= $buku['stok']; ?>" required>
                                <span class="input-group-text">
                                    <i class="fas fa-boxes"></i>
                                </span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="gambar" class="form-label">Url Gambar</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="gambar" name="gambar" 
                                       placeholder="Masukkan url gambar" value="<?= $buku['gambar']; ?>" required>
                                <span class="input-group-text">
                                    <i class="fas fa-image"></i>
                                </span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <div class="input-group">
                                <textarea class="form-control" id="deskripsi" name="deskripsi" 
                                          placeholder="Masukkan deskripsi buku" rows="4" required><?= $buku['deskripsi']; ?></textarea>
                                <span class="input-group-text">
                                    <i class="fas fa-align-left"></i>
                                </span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Oleh</label>
                            <p class="mb-0">
                                <i class="fas fa-user me-2"></i><?php echo htmlspecialchars($_SESSION['username']); ?>
                            </p>
                            <input type="hidden" name="user" value="<?php echo htmlspecialchars($_SESSION['username']); ?>">
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <button type="submit" name="ubah" class="btn btn-primary text-white">
                                <i class="fas fa-save me-2"></i>Ubah Data
                            </button>
                            <a href="buku.php" class="text-primary text-decoration-none">
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
