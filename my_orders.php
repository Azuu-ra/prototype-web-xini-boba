<?php
session_start();
include 'config/koneksi.php';

if(isset($_POST['batalkan_order'])){

    $order_id = (int)$_POST['order_id'];
    $user_id = (int)$_SESSION['id'];

    mysqli_query(
    $conn,
    "UPDATE orders
    SET
        status='Dibatalkan',
        bukti_pembayaran=NULL,
        tanggal_bayar=NULL
    WHERE id='$order_id'
    AND user_id='$user_id'
    AND (
        status='Menunggu Pembayaran'
        OR
        status='Menunggu Verifikasi'
    )"
);

    header("Location: my_orders.php");
    exit();
}

if(
isset($_POST['upload_bukti'])
){

    $order_id =
    (int)$_POST['order_id'];

    $folder =
    "img/bukti/";

    if(
    !is_dir($folder)
    ){
        mkdir(
            $folder,
            0777,
            true
        );
    }

    $namaFile =
    time().
    "_" .
    $_FILES['bukti']['name'];

    move_uploaded_file(
        $_FILES['bukti']['tmp_name'],
        $folder.$namaFile
    );
    mysqli_query(
    $conn,
    "UPDATE orders
    SET
    bukti_pembayaran='$namaFile',
    tanggal_bayar=NOW(),
    status='Menunggu Verifikasi',
    catatan_admin=NULL
    WHERE id='$order_id'"
    );
    header(
        "Location: my_orders.php"
    );
    exit();
}

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}

$user_id = (int)$_SESSION['id'];

$dataOrder = mysqli_query(
    $conn,
    "SELECT *
    FROM orders
    WHERE user_id='$user_id'
    AND status IN (
        'Menunggu Pembayaran',
        'Menunggu Verifikasi',
        'Diproses'
    )
    ORDER BY id DESC"
);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>

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
        <div class="profile-header p-4 user-theme-gradient">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-0">
                        <i class="fa fa-shopping-bag"></i>
                            My Orders
                    </h2>

                    <div class="small opacity-75 mt-2">
                        Daftar pesanan yang pernah dibuat
                    </div>
                    </div>
                    <a href="history_orders.php"
                            class="checkin-shortcut text-decoration-none">
                                <i class="fa fa-history"></i>
                                <span>Riwayat</span>
                            </a>
                        </div>
                    </div>


        <div class="p-4">

            <?php if(mysqli_num_rows($dataOrder) == 0): ?>

                <div class="alert alert-warning rounded-4">
                    Belum ada pesanan.
                </div>

            <?php else: ?>

                <div class="row">

                <?php while($order = mysqli_fetch_assoc($dataOrder)): ?>

                    <?php

                    $badge = "secondary";

                    if($order['status'] == 'Menunggu Pembayaran'){
                        $badge = "warning";
                    }
                    elseif($order['status'] == 'Menunggu Verifikasi'){
                        $badge = "info";
                    }
                    elseif($order['status'] == 'Diproses'){
                        $badge = "primary";
                    }
                    elseif($order['status'] == 'Selesai'){
                        $badge = "success";
                    }
                    elseif($order['status'] == 'Dibatalkan'){
                        $badge = "danger";
                    }

                    ?>

            

                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card border-0 shadow-sm rounded-4 h-100">
                            <div class="card-body">
                                <h5 class="fw-bold">
                                    <?= htmlspecialchars($order['kode_order']); ?>
                                </h5>

                                <div class="small text-muted mb-2">
                                    <?= $order['tanggal_order']; ?>
                                </div>

                                <div class="mb-2">
                                    Total :
                                    <b>
                                        Rp <?= number_format($order['total_harga']); ?>
                                    </b>
                                </div>

                                <div class="mb-2">
                                    <span class="badge bg-<?= $badge ?>">
                                        <?= htmlspecialchars($order['status']); ?>
                                    </span>
                                </div>
                                <?php if(!empty($order['catatan_admin'])): ?>
                                    <div class="mb-2">
                                        <span class="badge bg-danger">
                                            Ada Catatan Admin
                                        </span>
                                    </div>
                                    <?php endif; ?>

                                <button
                                    class="btn btn-boba w-100"
                                    data-bs-toggle="modal"
                                    data-bs-target="#orderModal<?= $order['id']; ?>">

                                    Detail Pesanan
                                </button>
                                <?php
                                    if(
                                        $order['status'] == 'Menunggu Pembayaran'
                                        ||
                                        $order['status'] == 'Menunggu Verifikasi'
                                    ){
                                    ?>
                                

                                    <form method="POST" class="mt-2">
                                        <input
                                            type="hidden"
                                            name="order_id"
                                            value="<?= $order['id']; ?>">

                                        <button
                                            type="submit"
                                            name="batalkan_order"
                                            class="btn btn-outline-danger w-100"
                                            onclick="return confirm('Batalkan pesanan ini?')">
                                            Batalkan Pesanan
                                        </button>
                                    </form>
                                    <?php } ?>
                            </div>
                        </div>
                    </div>

                    <!-- MODAL DETAIL ORDER -->
                    <div
                        class="modal fade"
                        id="orderModal<?= $order['id']; ?>"
                        tabindex="-1">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">

                                        <?= htmlspecialchars($order['kode_order']); ?>

                                    </h5>
                                    <button
                                        type="button"
                                        class="btn-close"
                                        data-bs-dismiss="modal">
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <div>
                                            Status :
                                            <b>
                                                <?= htmlspecialchars($order['status']); ?>
                                            </b>
                                        </div>

                                        <div>
                                            Tanggal :
                                            <b>
                                                <?= $order['tanggal_order']; ?>
                                            </b>
                                        </div>
                                        <?php if(!empty($order['catatan_admin'])): ?>
                                    <div class="alert alert-danger mt-3">
                                        <b>
                                            <i class="fa fa-exclamation-triangle"></i>
                                            Catatan Admin
                                        </b>
                                        <hr>
                                        <?= nl2br(htmlspecialchars($order['catatan_admin'])) ?>
                                    </div>
                                    <?php endif; ?>
                                    </div>
                                    <hr>

                                    <?php

                                    $detail = mysqli_query(
                                        $conn,
                                        "SELECT
                                            oi.*,
                                            m.nama_menu
                                        FROM order_items oi
                                        JOIN menu m
                                        ON oi.menu_id = m.id
                                        WHERE oi.order_id='".$order['id']."'"
                                    );

                                    ?>

                                    <div class="table-responsive">
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

                                            <?php while($item = mysqli_fetch_assoc($detail)): ?>

                                                <?php
                                                $subtotal =
                                                $item['qty']
                                                *
                                                $item['harga'];
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
                                                        Rp <?= number_format($subtotal); ?>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <hr>
                                    <hr>

<h6>
Bukti Pembayaran
</h6>

<?php
if(
$order['status']
!= 'Diproses'
&&
$order['status']
!= 'Selesai'
){
?>

<form
method="POST"
enctype="multipart/form-data">

<input
type="hidden"
name="order_id"
value="<?= $order['id']; ?>">

<input
type="file"
name="bukti"
class="form-control mb-2"
required>

<button
type="submit"
name="upload_bukti"
class="btn btn-primary">

Upload Bukti

</button>

</form>

<?php
}
?>
                                    <div class="text-end">
                                        <h5>
                                            Total :

                                            Rp <?= number_format($order['total_harga']); ?>
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
</div>

<?php include 'assets/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>