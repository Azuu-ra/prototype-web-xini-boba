<?php
include '../config/koneksi.php';

$id = $_GET['id'];
$status = $_GET['status'];

mysqli_query($conn,
"UPDATE menu SET status_menu='$status' WHERE id='$id'");
// redirect-only endpoint

header('Location: dashboard.php');
?>