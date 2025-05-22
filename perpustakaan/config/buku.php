<?php
function create_buku($post)
{
    global $db;
    $name = strip_tags($post['name']);
    $judul_buku = strip_tags($post['judul']);
    $gambar = strip_tags($post['gambar']);
    $deskripsi = strip_tags($post['deskripsi']);

    //Query untuk Tambah data
    $query = "INSERT INTO login VALUES (null, '$name', '$judul_buku', '$gambar', '$deskripsi')";
    mysqli_query($db, $query);
    return mysqli_affected_rows($db);
}
?>