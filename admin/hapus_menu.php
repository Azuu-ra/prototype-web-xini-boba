<?php
include '../config/koneksi.php';

if (!isset($_GET['id'])) {
    header('Location: dashboard.php');
    exit();
}

$id = (int)$_GET['id'];

if ($id <= 0) {
    header('Location: dashboard.php');
    exit();
}

// optional: cek dulu data ada atau tidak
$cek = mysqli_query($conn, "SELECT * FROM menu WHERE id='$id'");

if (mysqli_num_rows($cek) == 0) {
    header('Location: dashboard.php');
    exit();
}

// hapus data
$hapus = mysqli_query($conn, "DELETE FROM menu WHERE id='$id'");

if ($hapus) {
    echo "
    <script>
        alert('Menu berhasil dihapus!');
        window.location='dashboard.php';
    </script>
    ";
} else {
    echo "
    <script>
        alert('Gagal menghapus menu!');
        window.location='dashboard.php';
    </script>
    ";
}
?>