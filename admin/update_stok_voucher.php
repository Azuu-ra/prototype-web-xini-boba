<?php
include '../config/koneksi.php';

// endpoint update (redirect-only), tidak memakai navbar


if (!isset($_POST['voucher_id'], $_POST['stok_baru'])) {
    header('Location: voucher_admin.php');
    exit();
}

$voucher_id = (int)$_POST['voucher_id'];
$stok_baru = (int)$_POST['stok_baru'];

if ($stok_baru < 0) {
    $stok_baru = 0;
}

mysqli_query(
    $conn,
    "UPDATE vouchers SET stok='$stok_baru' WHERE id='$voucher_id'"
);

header('Location: voucher_admin.php');
exit();

