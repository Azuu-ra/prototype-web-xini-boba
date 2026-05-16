<?php
session_start();
include 'config/koneksi.php';

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}

if (!isset($_POST['menu_id'])) {
    header('Location: menu.php');
    exit();
}

$menu_id = (int)$_POST['menu_id'];
$jumlah = isset($_POST['jumlah']) ? (int)$_POST['jumlah'] : 1;
if ($jumlah < 1) $jumlah = 1;

if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (!isset($_SESSION['cart'][$menu_id])) {
    $_SESSION['cart'][$menu_id] = 0;
}

// Tambahkan qty
$_SESSION['cart'][$menu_id] += $jumlah;

header('Location: cart.php');
exit();

