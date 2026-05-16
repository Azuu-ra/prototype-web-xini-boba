<?php

include '../config/koneksi.php';

if (!isset($_GET['id'])) {
    header('Location: voucher_admin.php');
    exit();
}

$id = (int)$_GET['id'];

if ($id <= 0) {
    header('Location: voucher_admin.php');
    exit();
}

$cek = mysqli_query($conn,
"SELECT * FROM vouchers WHERE id='$id'");

if (mysqli_num_rows($cek) == 0) {
    header('Location: voucher_admin.php');
    exit();
}

$hapus = mysqli_query($conn,
"DELETE FROM vouchers WHERE id='$id'");

if ($hapus) {

    echo "
    <script>
        alert('Voucher berhasil dihapus!');
        window.location='voucher_admin.php';
    </script>
    ";

} else {

    echo "
    <script>
        alert('Gagal menghapus voucher!');
        window.location='voucher_admin.php';
    </script>
    ";

}
?>