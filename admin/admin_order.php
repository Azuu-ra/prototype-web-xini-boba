<?php
include '../config/koneksi.php';

/*
|--------------------------------------------------------------------------
| TOLAK PEMBAYARAN
|--------------------------------------------------------------------------
*/

if(isset($_POST['tolak_pembayaran'])){

    $order_id = (int)$_POST['order_id'];

    $catatan = mysqli_real_escape_string(
        $conn,
        $_POST['catatan_admin']
    );

    mysqli_query(
        $conn,
        "UPDATE orders
        SET
            status='Menunggu Pembayaran',
            catatan_admin='$catatan',
            bukti_pembayaran=NULL,
            tanggal_bayar=NULL
        WHERE id='$order_id'"
    );

    header("Location: admin_order.php");
    exit();
}


/*
|--------------------------------------------------------------------------
| UPDATE STATUS ORDER
|--------------------------------------------------------------------------
*/

if(isset($_POST['update_status'])){

    $order_id = (int)$_POST['order_id'];

    $status_baru = mysqli_real_escape_string(
        $conn,
        $_POST['status']
    );

    $cekOrder = mysqli_fetch_assoc(
        mysqli_query(
            $conn,
            "SELECT *
            FROM orders
            WHERE id='$order_id'"
        )
    );

    if($cekOrder){

        mysqli_query(
            $conn,
            "UPDATE orders
            SET status='$status_baru'
            WHERE id='$order_id'"
        );

        /*
        ------------------------------------------------------
        Tambah poin otomatis
        saat status berubah menjadi Selesai
        ------------------------------------------------------
        */

        if(
            $status_baru == 'Selesai'
            &&
            $cekOrder['status'] != 'Selesai'
        ){

            $poin =
            floor(
                $cekOrder['total_harga'] / 1000
            );

            mysqli_query(
                $conn,
                "UPDATE users
                SET poin = poin + $poin
                WHERE id='".$cekOrder['user_id']."'"
            );
            mysqli_query(
                $conn,
                "INSERT INTO transaksi
                (
                    order_id,
                    user_id,
                    kode_order,
                    total_harga
                )
                VALUES
                (
                    '".$cekOrder['id']."',
                    '".$cekOrder['user_id']."',
                    '".$cekOrder['kode_order']."',
                    '".$cekOrder['total_harga']."'
                )"
            );
        }
    }

    header("Location: admin_order.php");
    exit();
}


/*
|--------------------------------------------------------------------------
| AMBIL DATA ORDER
|--------------------------------------------------------------------------
*/

$dataOrder = mysqli_query(
    $conn,
    "SELECT
        o.*,
        u.username,
        u.nama

    FROM orders o

    JOIN users u
    ON o.user_id = u.id

    WHERE o.status IN
        (
        'Menunggu Pembayaran',
        'Menunggu Verifikasi',
        'Diproses'
        )
    ORDER BY o.id DESC"
);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Kelola Pesanan</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

<link rel="stylesheet" href="../assets/style.css">
<link rel="stylesheet" href="../assets/navbarstyle.css">

<style>

body{
    background:#f8f5f2;
}

.card-modern{
    border:0;
    border-radius:20px;
    box-shadow:0 10px 24px rgba(0,0,0,.08);
}

.header-modern{
    background:linear-gradient(
        135deg,
        #6f4e37,
        #5a3d2b
    );
    color:white;
}

.badge-order{
    font-size:.85rem;
    padding:8px 14px;
    border-radius:50px;
}

.order-card{
    border:none;
    border-radius:18px;
    box-shadow:0 4px 12px rgba(0,0,0,.08);
}

.bukti-img{
    max-height:350px;
    width:auto;
    border-radius:12px;
}

</style>
</head>
<body>

<?php include '../assets/navbar.php'; ?>

<div style="margin-left:120px">

<div class="container mt-5 mb-5">

<div class="card-modern overflow-hidden">

<div class="header-modern p-4">

    <h3 class="mb-0">
        <i class="fa fa-shopping-bag"></i>
        Kelola Pesanan
    </h3>

    <div class="small opacity-75">
        Kelola pesanan pelanggan dan verifikasi pembayaran
    </div>

</div>

<div class="p-4">

<?php
if(mysqli_num_rows($dataOrder) == 0){
?>
    <div class="alert alert-warning">
        Belum ada pesanan masuk.
    </div>
<?php
}
?>

<div class="row">

<?php
while($row = mysqli_fetch_assoc($dataOrder)){

$badge = "secondary";

if($row['status'] == 'Menunggu Pembayaran'){
    $badge = "warning";
}
elseif($row['status'] == 'Menunggu Verifikasi'){
    $badge = "info";
}
elseif($row['status'] == 'Diproses'){
    $badge = "primary";
}
elseif($row['status'] == 'Selesai'){
    $badge = "success";
}
elseif($row['status'] == 'Dibatalkan'){
    $badge = "danger";
}
?>

<div class="col-md-6 mb-4">

    <div class="card order-card h-100">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-start">

                <div>

                    <div class="text-muted small">
                        Kode Order
                    </div>

                    <h5 class="fw-bold mb-1">
                        <?= $row['kode_order']; ?>
                    </h5>

                    <div class="small text-muted">
                        <?= $row['tanggal_order']; ?>
                    </div>

                </div>

                <span class="badge bg-<?= $badge ?> badge-order">
                    <?= $row['status']; ?>
                </span>

            </div>

            <hr>

            <div class="mb-2">
                <small class="text-muted">
                    Pelanggan
                </small>
                <div class="fw-semibold">
                    <?= htmlspecialchars($row['username']); ?>
                </div>
            </div>

            <div class="mb-3">
                <small class="text-muted">
                    Total Belanja
                </small>

                <div class="fw-bold fs-5">
                    Rp <?= number_format($row['total_harga']); ?>
                </div>
            </div>

            <button
                class="btn btn-outline-primary w-100"
                data-bs-toggle="modal"
                data-bs-target="#detail<?= $row['id']; ?>">

                Detail Pesanan

            </button>

        </div>

    </div>

</div>

<!-- MODAL DETAIL -->

<div
class="modal fade"
id="detail<?= $row['id']; ?>"
tabindex="-1">

<div class="modal-dialog modal-lg modal-dialog-centered">

<div class="modal-content">

<div class="modal-header">

<h5 class="modal-title">
<?= $row['kode_order']; ?>
</h5>

<button
type="button"
class="btn-close"
data-bs-dismiss="modal">
</button>

</div>

<div class="modal-body">

<div class="row">

<div class="col-md-6">

<b>Pelanggan :</b><br>
<?= htmlspecialchars($row['username']); ?>

</div>

<div class="col-md-6">

<b>Status :</b><br>
<?= $row['status']; ?>

</div>

</div>

<hr>

<h6>Item Pesanan</h6>

<?php

$detail = mysqli_query(
$conn,
"SELECT
oi.*,
m.nama_menu

FROM order_items oi

JOIN menu m
ON oi.menu_id = m.id

WHERE oi.order_id='".$row['id']."'"
);

?>

<table class="table">

<thead>
<tr>
<th>Menu</th>
<th>Qty</th>
<th>Harga</th>
<th>Subtotal</th>
</tr>
</thead>

<tbody>

<?php
while($item = mysqli_fetch_assoc($detail)){
?>

<tr>

<td>
<?= htmlspecialchars($item['nama_menu']); ?>
</td>

<td>
<?= $item['qty']; ?>
</td>

<td>
Rp <?= number_format($item['harga']); ?>
</td>

<td>
Rp <?= number_format(
$item['qty'] * $item['harga']
); ?>
</td>

</tr>

<?php
}
?>

</tbody>

</table>

<hr>

<h6>Bukti Pembayaran</h6>

<?php
if(!empty($row['bukti_pembayaran'])){
?>

<img
src="../img/bukti/<?= $row['bukti_pembayaran']; ?>"
class="img-fluid bukti-img">

<?php
}else{
?>

<div class="alert alert-warning">
Belum ada bukti pembayaran
</div>

<?php
}
?>

<?php
if(
!empty($row['catatan_admin'])
){
?>

<div class="alert alert-danger mt-3">

<b>Catatan Admin :</b><br>

<?= htmlspecialchars(
$row['catatan_admin']
); ?>

</div>

<?php
}
?>

<hr>

<div class="fw-bold fs-5 text-end">

Total :
Rp <?= number_format(
$row['total_harga']
); ?>

</div>

</div>

<div class="modal-footer">

<?php
if(
    $row['status']
    == 'Menunggu Verifikasi'
){
?>

<form method="POST">

    <input
        type="hidden"
        name="order_id"
        value="<?= $row['id']; ?>">

    <input
        type="hidden"
        name="status"
        value="Diproses">

    <button
        type="submit"
        name="update_status"
        class="btn btn-success">

        Terima Pembayaran

    </button>

</form>

<button
    class="btn btn-danger"
    data-bs-toggle="collapse"
    data-bs-target="#tolak<?= $row['id']; ?>">

    Tolak

</button>

<?php
}
elseif(
    $row['status']
    == 'Diproses'
){
?>

<form method="POST">

    <input
        type="hidden"
        name="order_id"
        value="<?= $row['id']; ?>">

    <input
        type="hidden"
        name="status"
        value="Selesai">

    <button
        type="submit"
        name="update_status"
        class="btn btn-primary">

        Selesaikan Pesanan

    </button>

</form>

<?php
}
?>

<button
    type="button"
    class="btn btn-secondary"
    data-bs-dismiss="modal">

    Tutup

</button>

</div>

<?php
if(
    $row['status']
    == 'Menunggu Verifikasi'
){
?>

<div class="collapse p-3"
id="tolak<?= $row['id']; ?>">

<form method="POST">

    <input
        type="hidden"
        name="order_id"
        value="<?= $row['id']; ?>">

    <textarea
        name="catatan_admin"
        class="form-control mb-2"
        placeholder="Alasan penolakan"
        required></textarea>

    <button
        type="submit"
        name="tolak_pembayaran"
        class="btn btn-danger">

        Kirim Penolakan

    </button>

</form>

</div>

<?php
}
?>

</div>
</div>
</div>

<?php
}
?>

</div>

</div>
</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>