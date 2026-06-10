<?php
include '../config/koneksi.php';

if (!isset($_GET['id'])) {
    header('Location: daftar_menu.php');
    exit();
}

$id = (int)$_GET['id'];
if ($id <= 0) {
    header('Location: daftar_menu.php');
    exit();
}

$result = mysqli_query($conn, "SELECT * FROM menu WHERE id='$id' LIMIT 1");
$menu = mysqli_fetch_assoc($result);

if (!$menu) {
    header('Location: daftar_menu.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Menu</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/style.css">
    <link rel="stylesheet" href="../assets/navbarstyle.css">

    <style>
        body{background:#f8f5f2;}
        .card-modern{border:0;border-radius:20px;box-shadow:0 10px 24px rgba(0,0,0,0.08);}
        .header-modern{background:linear-gradient(135deg,#6f4e37,#5a3d2b);color:#fff;border-radius:18px 18px 0 0;}
        .btn-boba{background:#6f4e37;color:#fff;border:0;border-radius:14px;}
        .btn-boba:hover{background:#5a3d2b;color:#fff;}
    </style>
</head>
<body>



<div class="container mt-5 mb-5">
    <div class="card-modern overflow-hidden">
        <div class="header-modern p-4">
            <h3 class="mb-0">Edit Menu</h3>
            <div class="small opacity-75">Ubah nama dan harga</div>
        </div>

        <div class="p-4">
            <form method="POST" action="update_menu_nama_harga.php">
                <input type="hidden" name="menu_id" value="<?= (int)$menu['id']; ?>">

                <div class="mb-3">
                    <label class="form-label">Nama Menu</label>
                    <input type="text" name="nama" class="form-control" required value="<?= htmlspecialchars($menu['nama_menu'] ?? '', ENT_QUOTES); ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Harga</label>
                    <input type="number" name="harga" class="form-control" required min="0" value="<?= (int)($menu['harga'] ?? 0); ?>">
                </div>

                <div class="d-flex gap-2">
                    <button name="submit" class="btn btn-boba w-100 py-2 fw-semibold" type="submit">Simpan Perubahan</button>
                    <a href="daftar_menu.php" class="btn btn-outline-secondary py-2 fw-semibold" style="border-radius:14px;">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</div>

</div>

</body>
</html>

