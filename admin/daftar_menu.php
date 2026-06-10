<?php
include '../config/koneksi.php';

$dataMenu = mysqli_query($conn,"SELECT * FROM menu");




?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>

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
                    <h2 class="mb-0">
                        <i class="fa fa-cutlery"></i>
                        Kelolah Menu</h2>
                    <div class="small opacity-75">Atur status menu & tambah menu baru</div>
                </div>
                <div class="d-flex gap-2">
                    <a href="tambah_menu.php" class="btn btn-light btn-sm rounded-pill">Tambah Menu</a>
                </div>
            </div>
        </div>

        <div class="p-4">
            <div class="table-card bg-white">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Menu</th>
                            <th>Harga</th>
                            <th>Status</th>
                            <th>Stok</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($menu = mysqli_fetch_assoc($dataMenu)) { ?>
                        <tr>
                            <td><?= $menu['nama_menu']; ?></td>
                            <td>Rp <?= number_format($menu['harga']); ?></td>
                            <td>
                                <?php if(((int)($menu['stok'] ?? 0)) > 0){ ?>
                                    <span class="status-pill bg-success text-white">Ready</span>
                                <?php } else { ?>
                                    <span class="status-pill bg-danger text-white">Sold Out</span>
                                <?php } ?>
                            </td>
                            <td>
                                <form method="POST" action="update_stok_menu.php" class="d-flex align-items-center gap-2">
                                    <input type="hidden" name="menu_id" value="<?= (int)$menu['id']; ?>">
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="changeStokMenu(<?= (int)$menu['id']; ?>,-1)">-</button>
                                    <input type="number"
                                           name="stok_baru"
                                           value="<?= (int)($menu['stok'] ?? 0); ?>"
                                           min="0"
                                           class="form-control form-control-sm text-center"
                                           style="max-width:90px"
                                           id="stokMenu_<?= (int)$menu['id']; ?>">
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="changeStokMenu(<?= (int)$menu['id']; ?>,1)">+</button>
                                    <button type="submit" class="btn btn-success btn-sm">Simpan</button>
                                </form>
                            </td>
                            <td>
                                <a href="edit_menu.php?id=<?= (int)$menu['id']; ?>" class="btn btn-warning btn-sm rounded-pill">Edit</a>
<a href="hapus_menu.php?id=<?= $row['id']; ?>"
   class="btn btn-danger btn-sm"
   onclick="return confirm('Yakin mau hapus menu ini?')">
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
function changeStokMenu(menuId, delta) {
    const input = document.getElementById('stokMenu_' + menuId);
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
