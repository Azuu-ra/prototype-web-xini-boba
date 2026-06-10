<?php
include '../config/koneksi.php';

// endpoint update (redirect-only), tidak memakai navbar
if (!isset($_POST['menu_id'], $_POST['stok_baru'])) {
    header('Location: dashboard.php');
    exit();
}


$menu_id = (int)$_POST['menu_id'];
$stok_baru = (int)$_POST['stok_baru'];
if ($stok_baru < 0) $stok_baru = 0;

mysqli_query($conn, "UPDATE menu SET stok='$stok_baru' WHERE id='$menu_id'");

header('Location: dashboard.php');
exit();

