<?php
session_start();
include 'koneksi.php'; // File koneksi database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validasi input tidak boleh kosong
    if (empty($username) || empty($password)) {
        header("Location: ../loginRegistrasi/loginUpdate.php?error=Username dan Password wajib diisi!");
        exit();
    }

    // Query untuk mencari user
    $stmt = $db->prepare("SELECT * FROM login WHERE username = ? LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        // Username tidak ditemukan
        header("Location: ../loginRegistrasi/loginUpdate.php?error=Username tidak ditemukan!");
        exit();
    }

    $user = $result->fetch_assoc();

    // Cek password
    if ($password !== $user['password']) {
        // Password salah
        header("Location: ../loginRegistrasi/loginUpdate.php?error=Password salah!");
        exit();
    }

    // Login berhasil
    $_SESSION['username'] = $user['username'];
    $_SESSION['level'] = $user['level'];

    // Pengalihan halaman berdasarkan level
    switch ($user['level']) {
        case 'admin':
            header("Location: ../landing/admin.php");
            break;
        case 'siswa':
            header("Location: ../landing/siswa.php");
            break;
        case 'petugas':
            header("Location: ../landing/petugas.php");
            break;
        default:
            header("Location: ../loginRegistrasi/loginUpdate.php?error=Level user tidak dikenali!");
            break;
    }
    exit();
}
?>