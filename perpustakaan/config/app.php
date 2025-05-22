<?php
//Panggil Koneksi Data
include "koneksi.php";
//Fungsi Menampilkan
function select($query)
{
    global $db;
    $result = mysqli_query($db, $query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}
//Fungsi Tambah Akun
function create_akun($post)
{
    global $db;
    $name = strip_tags($post['name']);
    $username = strip_tags($post['username']);
    $password = strip_tags($post['password']);
    $level = strip_tags($post['level']);
    $status = strip_tags($post['status']);

    //Query untuk Tambah data
    $query = "INSERT INTO login VALUES (null, '$name', '$username', '$password', '$level', '$status')";
    mysqli_query($db, $query);
    return mysqli_affected_rows($db);
}

//Fungsi Ubah data akun
function ubah_dataAkun($post)
{
    global $db;
    $id = $post['id'];
    $name = $post['name'];
    $username = $post['username'];
    $password = $post['password'];
    $level = $post['level'];
    $status = $post['status'];

    // SQL Ubah data login
    $query = "UPDATE login SET name = '$name', username = '$username', password = '$password', level = '$level', status = '$status'  WHERE id = $id";
    mysqli_query($db, $query);
    return mysqli_affected_rows($db);
}

//Fungsi Hapus data akun
function delete_dataAkun($id)
{
    global $db;

    //SQL delete dataAkun
    $query = "DELETE FROM login WHERE id=$id";
    mysqli_query($db, $query);
    return mysqli_affected_rows($db);
}

//KATEGORI
function delete_kategori($ID)
{
    global $db;

    //SQL delete kategori
    $query = "DELETE FROM kategori WHERE id_kategori=$ID";
    mysqli_query($db, $query);
    return mysqli_affected_rows($db);
}

//Fungsi Ubah katgori
function edit_kategori($post)
{
    global $db;
    $ID = $post['id_kategori'];
    $nama = $post['nama'];
    $dibuat_oleh = strip_tags($post['dibuat_oleh']);

    // SQL Ubah kategori
    $query = "UPDATE kategori SET nama = '$nama', dibuat_oleh = '$dibuat_oleh' WHERE id_kategori = $ID";
    mysqli_query($db, $query);
    return mysqli_affected_rows($db);
}

//Fungsi Tambah kategori
function add_kategori($post)
{
    global $db;
    $nama = strip_tags($post['nama']);
    $dibuat_oleh = strip_tags($post['dibuat_oleh']);

    //Query untuk Tambah kategori
    $query = "INSERT INTO kategori (nama, dibuat_oleh) VALUES ('$nama', '$dibuat_oleh')";
    mysqli_query($db, $query);
    return mysqli_affected_rows($db);
}

//Fungsi Tambah Buku
function add_buku($post)
{
    global $db;
    $id_kategori = strip_tags($post['id_kategori']);
    $id_rak = !empty($post['id_rak']) ? strip_tags($post['id_rak']) : null;
    $judul_buku = strip_tags($post['judul_buku']);
    $gambar = strip_tags($post['gambar']);
    $pengarang = strip_tags($post['pengarang']);
    $tahun_terbit = strip_tags($post['tahun_terbit']);
    $deskripsi = strip_tags($post['deskripsi']);
    $user = strip_tags($post['user']);
    $stok = strip_tags($post['stok']);

    if ($id_rak !== null) {
        $check_rak = select("SELECT id_rak FROM rak WHERE id_rak = '$id_rak'");
        if (empty($check_rak)) {
            return -1;
        }
    }

    //Query untuk Tambah data
    $query = "INSERT INTO buku (id_kategori, id_rak, judul_buku, gambar, pengarang, tahun_terbit, deskripsi, user, stok) 
              VALUES ('$id_kategori', " . ($id_rak !== null ? "'$id_rak'" : "NULL") . ", '$judul_buku', '$gambar', '$pengarang', '$tahun_terbit', '$deskripsi', '$user', '$stok')";
    mysqli_query($db, $query);
    return mysqli_affected_rows($db);
}

//Fungsi Ubah data buku
function ubah_dataBuku($post)
{
    global $db;
    $id_buku = $post['id_buku'];
    $id_kategori = $post['id_kategori'];
    $id_rak = !empty($post['id_rak']) ? strip_tags($post['id_rak']) : null;
    $judul_buku = $post['judul_buku'];
    $gambar = $post['gambar'];
    $pengarang = $post['pengarang'];
    $tahun_terbit = $post['tahun_terbit'];
    $deskripsi = $post['deskripsi'];
    $user = $post['user'];
    $stok = $post['stok'];

    if ($id_rak !== null) {
        $check_rak = select("SELECT id_rak FROM rak WHERE id_rak = '$id_rak'");
        if (empty($check_rak)) {
            return -1;
        }
    }

    // SQL Ubah data buku
    $query = "UPDATE buku SET 
              id_kategori = '$id_kategori', 
              id_rak = " . ($id_rak !== null ? "'$id_rak'" : "NULL") . ", 
              judul_buku = '$judul_buku', 
              gambar = '$gambar', 
              pengarang = '$pengarang', 
              tahun_terbit = '$tahun_terbit', 
              deskripsi = '$deskripsi', 
              user = '$user', 
              stok = '$stok' 
              WHERE id_buku = $id_buku";
    mysqli_query($db, $query);
    return mysqli_affected_rows($db);
}

//Fungsi Hapus data buku
function delete_buku($id_buku)
{
    global $db;

    //SQL delete bukui
    $query = "DELETE FROM buku WHERE id_buku=$id_buku";
    mysqli_query($db, $query);
    return mysqli_affected_rows($db);
}

//Fungsi Hapus data aktivitas
function delete_ab($id_log, $type)
{
    global $db;

    //SQL delete aktivitas
    $query = "DELETE FROM log_aktivitas WHERE id_log=$id_log AND tipe_data='$type'";
    mysqli_query($db, $query);
    return mysqli_affected_rows($db);
}
