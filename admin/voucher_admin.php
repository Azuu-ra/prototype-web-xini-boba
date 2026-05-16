<?php
include '../config/koneksi.php';

$dataVoucher = mysqli_query($conn, "SELECT * FROM vouchers");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Voucher Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/style.css">
    <link rel="stylesheet" href="../assets/navbarstyle.css">

    <style>
        body{background:#f8f5f2;}
        .admin-card{border:0;border-radius:20px;box-shadow:0 10px 24px rgba(0,0,0,0.08);}
        .admin-header{background:linear-gradient(135deg,#6f4e37,#5a3d2b);color:#fff;border-radius:20px 20px 0 0;}
        .btn-boba{background:#6f4e37;color:#fff;border:0;border-radius:14px;}
        .btn-boba:hover{background:#5a3d2b;color:#fff;}
        .table-card{border:1px solid rgba(0,0,0,0.06);border-radius:18px;overflow:hidden;}
        .table thead th{background:rgba(111,78,55,0.08);}
        .status-pill{border-radius:999px;padding:6px 12px;font-weight:600;font-size:0.9rem;}
    </style>
</head>
<body>

<?php include '../assets/navbar.php'; ?>

<div style="margin-left:120px">

<div class="container mt-5 mb-5">
    <div class="admin-card overflow-hidden">
        <div class="admin-header p-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h2 class="mb-0">Manajemen Voucher</h2>
                    <div class="small opacity-75">Kelola voucher, stok, dan status</div>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="tambah_voucher.php" class="btn btn-light btn-sm rounded-pill">Tambah Voucher</a>
                </div>
            </div>
        </div>

        <div class="p-4">
            <div class="table-card bg-white">
                <table class="table mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>Nama Voucher</th>
                            <th>Poin</th>
                            <th>Status</th>
                            <th>Stok</th>
                            <th>aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($voucher = mysqli_fetch_assoc($dataVoucher)) { ?>
                        <tr>
                            <td class="fw-semibold"><?= $voucher['nama_voucher']; ?></td>
                            <td><?= (int)$voucher['poin_dibutuhkan']; ?></td>
                            <td>
                                <?php
                                    $status = $voucher['status_voucher'] ?? '';
                                    $isAktif = (strtolower(trim($status)) === 'aktif');
                                ?>
                                <span class="status-pill <?= $isAktif ? 'bg-success text-white' : 'bg-danger text-white' ?>">
                                    <?= htmlspecialchars($status); ?>
                                </span>
                            </td>
                            <td>
                                <form method="POST" action="update_stok_voucher.php" class="d-flex align-items-center gap-2">
                                    <input type="hidden" name="voucher_id" value="<?= (int)$voucher['id']; ?>">

                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="changeStok(<?= (int)$voucher['id']; ?>,-1)">-</button>

                                    <input type="number"
                                           name="stok_baru"
                                           value="<?= (int)($voucher['stok'] ?? 0); ?>"
                                           min="0"
                                           class="form-control form-control-sm text-center"
                                           style="max-width:90px"
                                           id="stok_<?= (int)$voucher['id']; ?>"
                                    >

                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="changeStok(<?= (int)$voucher['id']; ?>,1)">+</button>

                                    <button type="submit" class="btn btn-success btn-sm">Simpan</button>
                                </form>
                            </td>
                            <td>
    <a href="edit_voucher.php?id=<?= (int)$voucher['id']; ?>"
       class="btn btn-warning btn-sm rounded-pill">
       Edit
    </a>

    <a href="hapus_voucher.php?id=<?= (int)$voucher['id']; ?>"
       class="btn btn-danger btn-sm rounded-pill"
       onclick="return confirm('Yakin mau hapus voucher ini?')">
       Hapus
    </a>
</td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function changeStok(voucherId, delta) {
    const input = document.getElementById('stok_' + voucherId);
    if (!input) return;
    const current = parseInt(input.value || '0', 10);
    let next = current + delta;
    if (next < 0) next = 0;
    input.value = next;
}
</script>

</div>

</body>
</html>

