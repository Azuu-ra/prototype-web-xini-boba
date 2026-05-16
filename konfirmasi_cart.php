dalam ke<?php
session_start();
include 'config/koneksi.php';

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}

if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart']) || count($_SESSION['cart']) === 0) {
    header('Location: cart.php');
    exit();
}

$user_id = (int)$_SESSION['id'];
$cart = $_SESSION['cart'];

// Ambil detail menu
$ids = array_keys($cart);
$idsEsc = array_map(fn($v) => (int)$v, $ids);
$in = implode(',', $idsEsc);

$menuRows = [];
$q = mysqli_query($conn, "SELECT id, harga FROM menu WHERE id IN ($in)");
while ($row = mysqli_fetch_assoc($q)) {
    $menuRows[(int)$row['id']] = $row;
}

// Hitung total
$totalAll = 0;
$items = [];
foreach ($cart as $menu_id => $qty) {
    $menu_id = (int)$menu_id;
    $qty = (int)$qty;
    if (!isset($menuRows[$menu_id])) continue;

    $harga = (int)$menuRows[$menu_id]['harga'];
    $total = $harga * $qty;
    $totalAll += $total;

    $items[] = [
        'menu_id' => $menu_id,
        'harga' => $harga,
        'jumlah' => $qty,
        'total' => $total
    ];
}

$grandPoin = floor($totalAll / 10000);

$done = false;
if (isset($_POST['confirm_cart'])) {
    // Masukkan ke orders dan update poin seperti checkout.php
    // (tetap memakai logic yang sama per item)
    foreach ($items as $it) {
        $menu_id = (int)$it['menu_id'];
        $jumlah = (int)$it['jumlah'];
        $total = (int)$it['total'];

        mysqli_query($conn,
            "INSERT INTO orders(user_id,menu_id,jumlah,total)
            VALUES('$user_id','$menu_id','$jumlah','$total')");

        $poin = floor($total / 10000);
        mysqli_query($conn,
            "UPDATE users SET poin = poin + $poin WHERE id='$user_id'");
    }

    // Kosongkan keranjang setelah konfirmasi sukses
    $_SESSION['cart'] = [];
    $done = true;
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Keranjang - Xini Boba</title>
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
    <div class="card-modern">
        <div class="p-4 user-theme-gradient">

            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h2 class="mb-0">Konfirmasi Pesanan</h2>
                    <div class="small opacity-75">Setelah konfirmasi, masuk ke orders</div>
                </div>
            </div>
        </div>

        <div class="p-4">
            <?php if ($done) { ?>
                <div class="text-center">
                    <h1 class="mb-2">Pesanan Berhasil</h1>
                    <div class="mb-3">
                        <div class="small opacity-75">Total Bayar</div>
                        <div class="fs-2 fw-bold">Rp <?= number_format($totalAll); ?></div>
                    </div>
                    <div class="mb-4">
                        <div class="small opacity-75">Poin (estimasi)</div>
                        <div class="fs-4 fw-bold"><?= $grandPoin; ?> poin</div>
                    </div>

                    <a href="menu.php" class="btn btn-boba rounded-pill px-4">Kembali ke Menu</a>

                </div>
            <?php } else { ?>

                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Menu</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        // Detail nama/gambar juga
                        $q2 = mysqli_query($conn, "SELECT id, nama_menu, gambar FROM menu WHERE id IN ($in)");
                        $names = [];
                        while ($r = mysqli_fetch_assoc($q2)) { $names[(int)$r['id']] = $r; }

                        foreach ($items as $it) {
                            $mid = (int)$it['menu_id'];
                            $qty = (int)$it['jumlah'];
                            $subtotal = (int)$it['total'];
                            $name = $names[$mid]['nama_menu'] ?? 'Menu';
                            $gambar = $names[$mid]['gambar'] ?? '';
                        ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <?php if (!empty($gambar)) { ?>
                                            <img src="<?= $gambar; ?>" style="width:56px;height:56px;border-radius:16px;object-fit:cover;border:1px solid rgba(0,0,0,0.06);" alt="<?= htmlspecialchars($name); ?>">
                                        <?php } ?>
                                        <div>
                                            <div class="fw-semibold"><?= htmlspecialchars($name); ?></div>
                                            <div class="small opacity-75">Rp <?= number_format((int)$it['harga']); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center fw-semibold"><?= $qty; ?></td>
                                <td class="text-end fw-semibold">Rp <?= number_format($subtotal); ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mt-4">
                    <div>
                        <div class="small opacity-75">Total Bayar</div>
                        <div class="fs-4 fw-bold">Rp <?= number_format($totalAll); ?></div>
                    </div>
                    <div class="text-end">
                        <div class="small opacity-75">Poin (estimasi)</div>
                        <div class="fs-5 fw-bold"><?= $grandPoin; ?> poin</div>
                    </div>
                </div>

                <form method="POST" class="mt-4 d-flex gap-2 flex-column flex-sm-row">
                    <a href="cart.php" class="btn btn-outline-secondary rounded-pill">Edit Keranjang</a>
                    <button type="submit" name="confirm_cart" class="btn btn-boba w-100 rounded-pill">Konfirmasi & Masuk Orderan</button>
                </form>
            <?php } ?>
        </div>
    </div>
</div>




<?php include 'assets/footer.php'; ?>

</div>

</body>
</html>



