<?php 
include ("../../config/controller.php");

// menerima id login yang dipilih untuk dihapus
$ID = (int)$_GET['id'];

//kondisi ketika tombol hapus diklik
if(delete_kategori($ID) > 0){
    echo "<script>
        alert('Data Berhasil Dihapus');
        document.location.href='../../admin/kategori/kategori-data.php';
        </script>";
} else {
    echo "<script>
        alert('Data Gagal Dihapus');
        document.location.href='../../admin/kategori/kategori-data.php';
        </script>";
}
?>