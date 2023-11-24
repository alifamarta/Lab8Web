<?php
include_once 'koneksi.php';
$id = $_GET['id'];
$sql = "DELETE FROM data_barang WHERE id_barang = '{$id}'";
$conn = mysqli_connect($host, $user, $pass, $db);
$result = mysqli_query($conn, $sql);
header("location: index.php");
?>