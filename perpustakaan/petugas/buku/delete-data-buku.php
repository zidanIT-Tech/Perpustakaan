<?php 
include ("../../config/controller.php");

// menerima id login yang dipilih untuk dihapus
$id_buku = (int)$_GET['id'];

//kondisi ketika tombol hapus diklik
if(delete_buku($id_buku) > 0){
    echo "<script>
        alert('Data Berhasil Dihapus');
        document.location.href='buku.php';
        </script>";
} else {
    
}
?>