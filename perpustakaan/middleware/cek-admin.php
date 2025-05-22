<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../../loginRegistrasi/loginUpdate.php?error=Silakan login dulu.");
    exit();
}

if ($_SESSION['level'] !== 'admin') {
    header("Location: ../../loginRegistrasi/loginUpdate.php?error=Akses ditolak!");
    exit();
}
?>