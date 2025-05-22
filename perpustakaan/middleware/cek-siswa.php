<?php
session_start();

// Cek apakah sudah login
if (!isset($_SESSION['username'])) {
    header("Location: ../loginRegistrasi/loginUpdate.php?error=Silakan login dulu.");
    exit();
}

// Cek level harus 'siswa'
if ($_SESSION['level'] !== 'siswa') {
    header("Location: ../loginRegistrasi/loginUpdate.php?error=Akses ditolak!");
    exit();
}
?>