<?php
session_start();
include 'config/koneksi.php';

$user_id = $_SESSION['id'] ?? 0;

$userLogin = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT poin FROM users WHERE id='$user_id'"
    )
);

$pesan = '';

if(isset($_POST['tukar_voucher'])){

    $user_id = $_SESSION['id'] ?? 0;
    $voucher_id = (int)$_POST['voucher_id'];

    $user = mysqli_fetch_assoc(
        mysqli_query(
            $conn,
            "SELECT * FROM users WHERE id='$user_id'"
        )
    );

    $voucher = mysqli_fetch_assoc(
        mysqli_query(
            $conn,
            "SELECT * FROM vouchers WHERE id='$voucher_id'"
        )
    );

    if(!$user || !$voucher){
        $pesan = "Voucher tidak ditemukan";
    }

    elseif($voucher['status_voucher'] !== 'Aktif'){
        $pesan = "Voucher tidak aktif";
    }

    elseif((int)$voucher['stok'] <= 0){
        $pesan = "Stok voucher habis";
    }

    elseif((int)$user['poin'] >= (int)$voucher['poin_dibutuhkan']){

        $sisa_poin =
            (int)$user['poin']
            - (int)$voucher['poin_dibutuhkan'];

        mysqli_query(
            $conn,
            "UPDATE users
            SET poin='$sisa_poin'
            WHERE id='$user_id'"
        );

        mysqli_query(
            $conn,
            "UPDATE vouchers
            SET stok=stok-1
            WHERE id='$voucher_id'"
        );

        mysqli_query(
            $conn,
            "INSERT INTO voucher_user
            (user_id, voucher_id)
            VALUES
            ('$user_id','$voucher_id')"
        );

        $pesan = "Voucher berhasil ditukar";
    }

    else{
        $pesan = "Poin tidak cukup";
    }
}

$dataVoucher = mysqli_query(
$conn,
"SELECT *FROM vouchers
WHERE stok > 0
AND CURDATE() BETWEEN tanggal_mulai
AND tanggal_berakhir"
);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
<div class="container mt-4 mt-lg-5 mb-5">
    <div class="card-modern overflow-hidden">
        <div class="profile-header p-4 user-theme-gradient">
            <div class="d-flex justify-content-between align-items-center">
<div >

        <h2 class="mb-0">
            <i  i class="fa fa-ticket"></i>
                Voucher Tersedia
            </h2>
            <div class="small opacity-75 mt-2">
                Tukarkan poinmu dengan voucher menarik
            </div>

            <div class="mt-2">
                <span class="user-point-badge">
                    <i class="fa fa-star text-warning"></i>
                    My Poin :
                    <?= (int)$userLogin['poin']; ?>
                </span>
            </div>
        </div>
            
            <a href="checkin.php" class="checkin-shortcut text-decoration-none">
                <i class="fa fa-calendar-check-o"></i>
                <span>Check In</span>
            </a>
        </div>
    </div>


        <div class="p-4">

            <?php if(!empty($pesan)): ?>

                    <div class="alert alert-info mb-4">
                        <?= $pesan ?>
                    </div>

                    <?php endif; ?>

            <div class="row">
                <?php while($voucher = mysqli_fetch_assoc($dataVoucher)) { ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 p-4 border-0 shadow-sm rounded-4">
                        <div class="card-body d-flex flex-column">
                            <div class="text-center mb-3">
                                <i class="fa fa-gift fa-3x voucher-icon"></i>
                            </div>

                            <h4 class="fw-bold text-center">
                                <?= htmlspecialchars($voucher['nama_voucher']); ?>
                            </h4>

                            <div class="text-center mb-3">

                                <span class="badge px-3 py-2" style="background:#6f4e37;" >
                                    <?= (int)$voucher['poin_dibutuhkan']; ?> Poin
                                </span>

                            </div>

                            <p class="text-muted text-center mb-4">
                                Stok:
                                <b><?= (int)($voucher['stok'] ?? 0); ?></b>
                            </p>

                            <?php $stokVoucher = (int)($voucher['stok'] ?? 0); ?>

                            <div class="mt-auto">

                            <?php if($stokVoucher > 0): ?>

                                <button
                                type="button"
                                class="btn btn-boba w-100"
                                data-bs-toggle="modal"
                                data-bs-target="#tukarModal"
                                data-id="<?= $voucher['id']; ?>"
                                data-nama="<?= htmlspecialchars($voucher['nama_voucher']); ?>"
                                data-poin="<?= $voucher['poin_dibutuhkan']; ?>">

                                <i class="fa fa-exchange"></i>
                                Tukar Voucher

                            </button>

                            <?php else: ?>

                                <button class="btn btn-secondary w-100" disabled>
                                    Stok Habis
                                </button>

                            <?php endif; ?>

                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

</div>
<div class="modal fade" id="tukarModal" tabindex="-1">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    Konfirmasi Penukaran
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <form method="POST">

                <div class="modal-body">

                    <input
                        type="hidden"
                        name="voucher_id"
                        id="voucher_id">

                    <p>
                        Tukarkan voucher
                        <strong id="voucher_nama"></strong> ?
                    </p>

                    <div class="alert-voucher">

                        Membutuhkan
                        <strong id="voucher_poin"></strong>
                        poin

                    </div>

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary btn-modal-cancel"
                        data-bs-dismiss="modal">

                        Batal

                    </button>

                    <button
                        type="submit"
                        name="tukar_voucher"
                        class="btn btn-modal-confirm">

                        Ya, Tukar

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<?php include 'assets/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>

const tukarModal =
document.getElementById('tukarModal');

tukarModal.addEventListener(
'show.bs.modal',
function(event){

    const button =
    event.relatedTarget;

    document.getElementById(
    'voucher_id'
    ).value =
    button.getAttribute('data-id');

    document.getElementById(
    'voucher_nama'
    ).textContent =
    button.getAttribute('data-nama');

    document.getElementById(
    'voucher_poin'
    ).textContent =
    button.getAttribute('data-poin');

});

</script>
</body>
</html>
