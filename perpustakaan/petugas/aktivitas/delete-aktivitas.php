<?php
include("../../config/controller.php");

if (isset($_GET['id']) && isset($_GET['type'])) {
    if (delete_ab($_GET['id'], $_GET['type']) > 0) {
        echo "<script>
            alert('Aktivitas Berhasil Dihapus');
            document.location.href='aktivitas-data.php';
            </script>";
    } else {
        echo "<script>
            alert('Aktivitas Gagal Dihapus');
            document.location.href='aktivitas-data.php';
            </script>";
    }
} else {
    echo "<script>
        alert('Parameter tidak valid');
        document.location.href='aktivitas-data.php';
        </script>";
}
?>