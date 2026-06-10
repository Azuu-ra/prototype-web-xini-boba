<?php
session_start();
include 'config/koneksi.php';

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}

$user_id = (int)$_SESSION['id'];

$dataVoucher = mysqli_query(
    $conn,
    "SELECT
        vu.id,
        vu.status,
        vu.tanggal_tukar,
        vu.tanggal_digunakan,

        v.nama_voucher,
        v.jenis_voucher,
        v.nilai_voucher
     

    FROM voucher_user vu

    JOIN vouchers v
    ON vu.voucher_id = v.id

    WHERE vu.user_id='$user_id'
    AND vu.status='Belum Digunakan'

    ORDER BY vu.id DESC"
);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Voucher - Xini Boba</title>

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
                        <i class="fa fa-ticket"></i>
                        My Voucher
                    </h2>
                    <div class="small opacity-75">
                        Voucher yang telah kamu tukarkan

                    </div>
                </div>
            
            </div>
        </div>
        <div class="p-4">
            <?php if(mysqli_num_rows($dataVoucher) == 0): ?>
                <div class="alert alert-warning rounded-4">
                    Kamu belum memiliki voucher.
                </div>
            <?php else: ?>
                <div class="row">
                    <?php while($voucher = mysqli_fetch_assoc($dataVoucher)): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card border-0 shadow-sm rounded-4 h-100">
                            <div class="card-body p-4">
                                <div class="text-center mb-3">
                                    <i class="fa fa-ticket fa-3x voucher-icon"></i>
                                </div>
                                <h5 class="fw-bold text-center">
                                    <?= htmlspecialchars($voucher['nama_voucher']); ?>
                                </h5>
                                <div class="text-center mb-3">
                                    <?php
                                    if($voucher['jenis_voucher'] == 'persen'){
                                        echo 'Diskon ' .
                                        $voucher['nilai_voucher'] . '%';
                                    }

                                    elseif($voucher['jenis_voucher'] == 'nominal'){
                                        echo 'Potongan Rp ' .
                                        number_format(
                                            $voucher['nilai_voucher']
                                        );
                                    }

                                    elseif($voucher['jenis_voucher'] == 'gratis_menu'){
                                        echo 'Voucher Gratis Menu';
                                    }
                                    ?>
                                </div>
                                <div class="text-center mb-3">
                                    <?php if($voucher['status'] == 'Belum Digunakan'): ?>
                                        <span class="badge bg-success">
                                            Belum Digunakan
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">
                                            Sudah Digunakan
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <div class="small text-muted text-center">
                                    Ditukar:
                                    <br>
                                    <?= $voucher['tanggal_tukar']; ?>
                                </div>
                                <?php if(
                                    !empty($voucher['tanggal_digunakan'])
                                ): ?>
                                <div class="small text-muted text-center mt-2">
                                    Digunakan:
                                    <br>
                                    <?= $voucher['tanggal_digunakan']; ?>
                                </div>
                                <?php endif; ?>
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

</body>
</html>