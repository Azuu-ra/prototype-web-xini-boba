<?php
include '../config/koneksi.php';

if (!isset($_POST['menu_id'], $_POST['nama'], $_POST['harga'])) {
    header('Location: dashboard.php');
    exit();
}

$menu_id = (int)$_POST['menu_id'];
$nama = trim((string)$_POST['nama']);
$harga = (int)$_POST['harga'];
if ($harga < 0) $harga = 0;

// Basic guard
if ($menu_id <= 0 || $nama === '') {
    header('Location: dashboard.php');
    exit();
}

// Note: project currently uses string interpolation without prepared statements.
mysqli_query(
    $conn,
    "UPDATE menu SET nama_menu='$nama', harga='$harga' WHERE id='$menu_id'"
);

header('Location: dashboard.php');
exit();

