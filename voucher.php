<?php
session_start();
include 'config/koneksi.php';

$dataVoucher = mysqli_query($conn,
"SELECT * FROM vouchers
WHERE status_voucher='Aktif'");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Voucher User</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="assets/navbarstyle.css">
    <link rel="stylesheet" href="assets/theme-user.css">

</head>
<body>

<?php include 'assets/navbar.php'; ?>


<div style="margin-left:120px">

<div class="container mt-5">

    <h2>Voucher Tersedia</h2>
<br>
    <div class="row">

        <?php while($voucher = mysqli_fetch_assoc($dataVoucher)) { ?>

        <div class="col-md-4 mb-4">

            <div class="card p-4">

                <h4>
                    <?= $voucher['nama_voucher']; ?>
                </h4>

                <p class="mb-1">
                    <?= $voucher['poin_dibutuhkan']; ?>
                    poin
                </p>
                <p class="text-muted mb-3">
                    Stok: <b><?= (int)($voucher['stok'] ?? 0); ?></b>
                </p>

                <?php $stokVoucher = (int)($voucher['stok'] ?? 0); ?>
                <a href="tukar_voucher.php?id=<?= $voucher['id']; ?>"
                   class="btn btn-boba w-100 <?= $stokVoucher <= 0 ? 'disabled' : ''; ?>"
                   <?= $stokVoucher <= 0 ? 'aria-disabled="true" tabindex="-1"' : ''; ?>
                >
                    Tukar Voucher
                </a>


            </div>

        </div>

        <?php } ?>

</div>

<?php include 'assets/footer.php'; ?>

</body>
</html>
