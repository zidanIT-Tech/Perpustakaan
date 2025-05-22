<?php 
include ("../../config/controller.php");

// menerima id login yang dipilih untuk dihapus
$id = (int)$_GET['id'];

//kondisi ketika tombol hapus diklik
if(delete_dataAkun($id) > 0){
    echo "<script>
        alert('Data Berhasil Dihapus');
        document.location.href='../../landing/admin.php';
        </script>";
} else {
    
}
?>