<?php
session_start();
include 'config/koneksi.php';

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}

// Keranjang disimpan di session: $_SESSION['cart'] = [menu_id => jumlah]
if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$cart = $_SESSION['cart'];
$menuRows = [];
$totalAll = 0;
$totalQty = 0;

if (count($cart) > 0) {
    $ids = array_keys($cart);
    $idsEsc = array_map(fn($v) => (int)$v, $ids);
    $in = implode(',', $idsEsc);

    $q = mysqli_query($conn, "SELECT id, nama_menu, harga, gambar FROM menu WHERE id IN ($in)");
    while ($row = mysqli_fetch_assoc($q)) {
        $menuRows[(int)$row['id']] = $row;
    }

    foreach ($cart as $menu_id => $qty) {
        $menu_id = (int)$menu_id;
        $qty = (int)$qty;
        $totalQty += $qty;

        if (isset($menuRows[$menu_id])) {
            $subtotal = (int)$menuRows[$menu_id]['harga'] * $qty;
            $totalAll += $subtotal;
        }
    }
}

if (isset($_POST['remove_item'])) {
    $removeId = (int)($_POST['remove_id'] ?? 0);
    unset($_SESSION['cart'][$removeId]);
    header('Location: cart.php');
    exit();
}

if (isset($_POST['clear_cart'])) {
    $_SESSION['cart'] = [];
    header('Location: cart.php');
    exit();
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang - Xini Boba</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="assets/navbarstyle.css">
    <link rel="stylesheet" href="assets/theme-user.css">



    <style>
        .cart-thumb{width:64px;height:64px;object-fit:cover;border-radius:16px;border:1px solid rgba(0,0,0,0.06);} 
    </style>
</head>
<body>

<?php include 'assets/navbar.php'; ?>

<div style="margin-left:120px">
<div class="container mt-4 mt-lg-5 mb-5">
    <div class="card-modern overflow-hidden">
        <div class="p-4 user-theme-gradient">

            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h2 class="mb-0">Keranjang</h2>
                    <div class="small opacity-75">Konfirmasi item untuk masuk ke orderan</div>
                </div>
                <div class="d-flex gap-2">
                    <form method="POST" class="m-0">
                        <button name="clear_cart" class="btn btn-outline-light btn-sm rounded-pill" type="submit" onclick="return confirm('Kosongkan keranjang?')">Kosongkan</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="p-4">
            <?php if (count($cart) === 0) { ?>
                <div class="alert alert-warning rounded-4">Keranjang kamu masih kosong.</div>
                <a href="menu.php" class="btn btn-boba">Tambah Menu</a>
            <?php } else { ?>

                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                    <div>
                        <div class="small opacity-75">Total item</div>
                        <div class="fs-5 fw-bold"><?= $totalQty; ?> item</div>
                    </div>
                    <div class="text-end">
                        <div class="small opacity-75">Total bayar</div>
                        <div class="fs-4 fw-bold">Rp <?= number_format($totalAll); ?></div>
                    </div>
                </div>

                <div class="table-responsive">

                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Menu</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Subtotal</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($cart as $menu_id => $qty) { 
                            $menu_id = (int)$menu_id;
                            $qty = (int)$qty;
                            if (!isset($menuRows[$menu_id])) continue;
                            $row = $menuRows[$menu_id];
                            $subtotal = (int)$row['harga'] * $qty;
                        ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="<?= $row['gambar']; ?>" class="cart-thumb" alt="<?= htmlspecialchars($row['nama_menu']); ?>">
                                        <div>
                                            <div class="fw-semibold"><?= htmlspecialchars($row['nama_menu']); ?></div>
                                            <div class="small opacity-75">Rp <?= number_format((int)$row['harga']); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center fw-semibold"><?= $qty; ?></td>
                                <td class="text-end">Rp <?= number_format($subtotal); ?></td>
                                <td class="text-end">
                                    <form method="POST" class="d-inline">
                                        <input type="hidden" name="remove_id" value="<?= $menu_id; ?>">
                                        <button type="submit" name="remove_item" class="btn btn-outline-danger btn-sm rounded-pill" onclick="return confirm('Hapus item dari keranjang?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mt-4">
                    <div>
                        <div class="small opacity-75">Total item</div>
                        <div class="fs-5 fw-bold"><?= $totalQty; ?> item</div>
                    </div>
                    <div class="text-end">
                        <div class="small opacity-75">Total bayar</div>
                        <div class="fs-4 fw-bold">Rp <?= number_format($totalAll); ?></div>
                    </div>
                </div>

                <div class="d-flex gap-2 flex-column flex-sm-row mt-4">
                    <a href="menu.php" class="btn btn-outline-secondary rounded-pill">Tambah Lagi</a>
                    <a href="konfirmasi_cart.php" class="btn btn-boba w-100 rounded-pill text-center">Konfirmasi & Masuk Orderan</a>
                </div>
            <?php } ?>
        </div>
    </div>
</div>


<?php include 'assets/footer.php'; ?>

</body>
</html>



