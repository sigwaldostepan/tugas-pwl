<?php
include '../fungsi.php';
$id = $_POST['id'];
$filepath = $_POST['filepath'];
$thumbpath = $_POST['thumbpath'];
// Hapus file dari server
if (file_exists($filepath)) unlink($filepath);
if (file_exists($thumbpath)) unlink($thumbpath);
// Hapus data dari database
$sql = "DELETE FROM gambar_thumbnail WHERE id = $id";
$koneksi->query($sql);
$koneksi->close();
header("Location: galeri_bootstrap3.php")
?>