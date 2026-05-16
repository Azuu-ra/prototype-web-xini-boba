<?php
session_start();
include 'config/koneksi.php';

$user_id = $_SESSION['id'];
$menu_id = $_POST['menu_id'];
$jumlah = $_POST['jumlah'];

$menu = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT * FROM menu WHERE id='$menu_id'"));

$total = (int)$menu['harga'] * (int)$jumlah;

mysqli_query($conn,
"INSERT INTO orders(user_id,menu_id,jumlah,total)
VALUES('$user_id','$menu_id','$jumlah','$total')");

$poin = floor($total / 10000);

mysqli_query($conn,
"UPDATE users SET poin = poin + $poin WHERE id='$user_id'");

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Xini Boba</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="assets/navbarstyle.css">
    <link rel="stylesheet" href="assets/theme-user.css">
</head>
<body>
    <?php include 'assets/navbar.php'; ?>

    <div style="margin-left:120px">
        <div class="container mt-4 mt-lg-5 mb-5">
            <div class="card-modern overflow-hidden">
                <div class="p-4 user-theme-gradient">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h2 class="mb-0">Pesanan Berhasil</h2>
                            <div class="small opacity-75">Terima kasih sudah memesan</div>
                        </div>
                    </div>
                </div>

                <div class="p-4 text-center">
                    <h3 class="mb-2">Total Bayar</h3>
                    <h2 class="mb-3">Rp <?= number_format($total); ?></h2>

                    <div class="mb-4">
                        <div class="small opacity-75">Kamu mendapatkan</div>
                        <div class="fs-4 fw-bold"><?= $poin; ?> poin</div>
                    </div>

                    <a href="menu.php" class="btn btn-boba rounded-pill px-4">Kembali ke Menu</a>
                </div>
            </div>
        </div>
    </div>

    <?php include 'assets/footer.php'; ?>
</body>
</html>
