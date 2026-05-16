<?php
session_start();
include 'config/koneksi.php';

$user_id = $_SESSION['id'] ?? 0;
$voucher_id = (int)($_GET['id'] ?? 0);

$user = mysqli_fetch_assoc(
    mysqli_query($conn,
    "SELECT * FROM users WHERE id='$user_id'" )
);

$voucher = mysqli_fetch_assoc(
    mysqli_query($conn,
    "SELECT * FROM vouchers WHERE id='$voucher_id'" )
);

if(!$user || !$voucher){
    $pesan = "Voucher tidak ditemukan";
} elseif($voucher['status_voucher'] !== 'Aktif'){
    $pesan = "Voucher tidak aktif";
} elseif((int)($voucher['stok'] ?? 0) <= 0){
    $pesan = "Stok voucher habis";
} elseif((int)$user['poin'] >= (int)$voucher['poin_dibutuhkan']){
    $sisa_poin = (int)$user['poin'] - (int)$voucher['poin_dibutuhkan'];

    // kurangi poin + kurangi stok
    mysqli_query($conn,
        "UPDATE users SET poin='$sisa_poin' WHERE id='$user_id'"
    );

    mysqli_query($conn,
        "UPDATE vouchers SET stok=(stok-1) WHERE id='$voucher_id' AND stok>0"
    );

    $pesan = "Voucher berhasil ditukar";
} else{
    $pesan = "Poin tidak cukup";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tukar Voucher</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="assets/navbarstyle.css">
</head>
<body>

<?php include 'assets/navbar.php'; ?>



<div style="margin-left:120px">

<div class="container mt-5">

    <div class="card p-5 text-center">

        <h2><?= $pesan; ?></h2>

        <a href="voucher.php"
        class="btn btn-dark mt-3">

            Kembali

        </a>

    </div>

</div>

</div>

</body>
</html>