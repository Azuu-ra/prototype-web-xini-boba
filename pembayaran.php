<?php
session_start();
include 'config/koneksi.php';

$order_id = $_SESSION['last_order_id'];

    $getOrder =
    mysqli_query(
    $conn,
    "SELECT *
    FROM orders
    WHERE id='$order_id'
    LIMIT 1"
);

$order =
    mysqli_fetch_assoc(
    $getOrder
);

if(
    !isset(
        $_SESSION['last_order_id']
    )
){
    header(
        'Location: my_orders.php'
    );
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>

<title>Pembayaran</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

</head>

<body style="background:#f8f5f2;">

<div class="container mt-5 mb-5">
<div class="row justify-content-center">
<div class="col-lg-6">

<div
class="card border-0 shadow-lg"
style="border-radius:25px;">

<div
        class="text-center p-4"
        style="
        background:linear-gradient(
        135deg,
        #6f4e37,
        #5a3d2b
        );
        color:white;
        border-radius:25px 25px 0 0;
        ">

<h2 class="mb-1">
💳 Pembayaran Pesanan
</h2>

<p class="mb-0 opacity-75">
Scan QR lalu upload bukti pembayaran
</p>

</div>

<div class="card-body p-4">
<div class="text-center mb-4">
<div class="card border-0 mb-4" style="background:#f8f5f2;">

<div class="card-body text-center">

    <div class="small text-muted">
        Nomor Pesanan
    </div>

    <h5 class="fw-bold">
    <?= $order['kode_order']; ?>
    </h5>

    <hr>

    <div class="small text-muted">
    Total Pembayaran
    </div>

    <h2
    style="
    color:#6f4e37;
    font-weight:700;
    ">

    Rp <?= number_format(
    $order['total_harga']
    ); ?>
    </h2>

    </div>
    </div>

<img
    src="img/Qr/Qr testing.png"
    class="img-fluid"
    style="
    max-width:250px;
    border-radius:15px;
    padding:10px;
    background:white;
    box-shadow:0 4px 12px rgba(0,0,0,.1);
    ">

</div>

<div
class="alert alert-warning text-center rounded-4">

Setelah transfer,
upload bukti pembayaran
agar pesanan dapat diverifikasi admin.

</div>

<form
action="my_orders.php"
method="POST"
enctype="multipart/form-data">

<input
type="hidden"
name="order_id"
value="<?= $_SESSION['last_order_id']; ?>">

<div class="mb-3">

<label class="form-label fw-semibold">

Upload Bukti Pembayaran

</label>

<input
type="file"
name="bukti"
class="form-control"
required>

</div>

<button
type="submit"
name="upload_bukti"
class="btn w-100 text-white"
style="
background:#6f4e37;
border:none;
border-radius:12px;
padding:12px;
">

Kirim Bukti Pembayaran

</button>

</form>

</div>

</div>

</div>

</div>

</div>

</body>
</html>