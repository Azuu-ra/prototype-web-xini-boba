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
$user_id = (int)$_SESSION['id'];


$dataVoucherUser = mysqli_query(
    $conn,
    "SELECT
        vu.id,
        v.nama_voucher,
        v.jenis_voucher,
        v.nilai_voucher,
        v.minimal_belanja

    FROM voucher_user vu

    JOIN vouchers v
    ON vu.voucher_id = v.id

    WHERE vu.user_id='$user_id'
    AND vu.status='Belum Digunakan'"
);

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

if(isset($_POST['buat_order'])){

    if(empty($_SESSION['cart'])){
        die('Keranjang kosong');
    }

    $voucher_user_id =
    (int)($_POST['voucher_user_id'] ?? 0);

    $kode_order =
    'XB' .
    date('ymdHis');

    $totalAkhir = $totalAll;


    /*
    nanti bagian diskon voucher
    kita pasang setelah ini
    */
    $totalAkhir = $totalAll;

    if($voucher_user_id > 0){

    $voucher =
    mysqli_fetch_assoc(
        mysqli_query(
            $conn,
            "SELECT
                vu.id,
                v.jenis_voucher,
                v.nilai_voucher,
                v.minimal_belanja

            FROM voucher_user vu

            JOIN vouchers v
            ON vu.voucher_id = v.id

            WHERE vu.id='$voucher_user_id'
            AND vu.user_id='$user_id'
            AND vu.status='Belum Digunakan'
            LIMIT 1"
        )
    );
    if($voucher){
        if(
            $totalAll >=
            $voucher['minimal_belanja']
        ){
            if(
                $voucher['jenis_voucher']
                == 'persen'
            ){
                $diskon =
                floor(
                    $totalAll *
                    $voucher['nilai_voucher']
                    / 100
                );
            }
            elseif(
                $voucher['jenis_voucher']
                == 'nominal'
            ){
                $diskon =
                $voucher['nilai_voucher'];
            }
            else{

                $diskon = 0;

            }
            $totalAkhir =
            max(
                0,
                $totalAll - $diskon
            );
        }
    }
    }

    mysqli_query(
        $conn,
        "INSERT INTO orders
        (
            user_id,
            kode_order,
            total_harga,
            voucher_user_id,
            status
        )
        VALUES
        (
            '$user_id',
            '$kode_order',
            '$totalAkhir',
            '$voucher_user_id',
            'Menunggu Pembayaran'
        )"
    );

    $order_id =
    mysqli_insert_id($conn);

    foreach(
        $_SESSION['cart']
        as $menu_id => $qty
    ){

        $menu_id =
        (int)$menu_id;

        $qty =
        (int)$qty;

        $menu =
        mysqli_fetch_assoc(
            mysqli_query(
                $conn,
                "SELECT harga
                FROM menu
                WHERE id='$menu_id'"
            )
        );

        $harga =
        (int)$menu['harga'];

        mysqli_query(
            $conn,
            "INSERT INTO order_items
            (
                order_id,
                menu_id,
                qty,
                harga
            )
            VALUES
            (
                '$order_id',
                '$menu_id',
                '$qty',
                '$harga'
            )"
        );
    }

    if($voucher_user_id > 0){

    mysqli_query(
        $conn,
        "UPDATE voucher_user
        SET
            status='Sudah Digunakan',
            tanggal_digunakan=NOW()
        WHERE id='$voucher_user_id'"
    );
    }

    $_SESSION['cart'] = [];

    $_SESSION['last_order_id'] =
    $order_id;
    header(
        "Location: pembayaran.php"
    );
    exit();
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
                    <h2 class="mb-0">
                        <i class="fa fa-shopping-cart"></i>
                        Keranjang</h2>
                    <div class="small opacity-75">
                        Konfirmasi item untuk masuk ke orderan
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <form method="POST" class="m-0">
                        <button
                            name="clear_cart"
                            type="submit"
                            class="checkin-shortcut border-0"
                            onclick="return confirm('Kosongkan keranjang?')">

                            <i class="fa fa-trash"></i>
                            <span>Kosongkan</span>

                        </button>
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
                   <button
                    type="button"
                    class="btn btn-boba w-100 rounded-pill"
                    data-bs-toggle="modal"
                    data-bs-target="#checkoutModal">
                        Konfirmasi & Masuk Orderan
                    </button>
                </div>

            <?php } ?>
        </div>
    </div>
</div>
</div>

<div class="modal fade" id="checkoutModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Konfirmasi Pesanan
                </h5>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>
            </div>
            <div class="modal-body">
                <h6>Ringkasan Pesanan</h6>
                <div class="mb-3">
                    <div>
                        Total Item :
                        <b><?= $totalQty ?></b>
                    </div>
                    <div>
                        Total Bayar :
                        <b>Rp <?= number_format($totalAll) ?></b>
                    </div>
                </div>
                <hr>
                <h6>Voucher</h6>
                <select
                class="form-select mb-3"
                id="voucherSelect">
                <option
                    value="0"
                    data-jenis=""
                    data-nilai="0">

                    Tidak Menggunakan Voucher
                </option>
                <?php while($v = mysqli_fetch_assoc($dataVoucherUser)): ?>
                    <option
                        value="<?= $v['id']; ?>"
                        data-jenis="<?= $v['jenis_voucher']; ?>"
                        data-nilai="<?= $v['nilai_voucher']; ?>"
                        data-minimal="<?= $v['minimal_belanja']; ?>">

                        <?= htmlspecialchars($v['nama_voucher']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
                        <div class="mt-3">
                    <div>
                        Total Awal :
                        <b>
                            Rp <?= number_format($totalAll); ?>
                        </b>
                    </div>
                    <div>
                        Potongan :
                        <b id="diskonVoucher">
                            Rp 0
                        </b>
                    </div>
                    <div class="fs-5 mt-2">
                        Total Bayar :
                        <b id="totalBayar">
                            Rp <?= number_format($totalAll); ?>
                        </b>
                    </div>
                </div>
                <hr>
                <div class="alert alert-info">
                Setelah pesanan dikonfirmasi,
                QR pembayaran akan ditampilkan.
            </div>
            </div>

            <div class="modal-footer">

                <button
                type="button"
                class="btn btn-secondary"
                data-bs-dismiss="modal">
                    Batal
                </button>

                
                <form method="POST">
                <input
                    type="hidden"
                    name="voucher_user_id"
                    id="voucherUserId">

                <button
                    type="submit"
                    name="buat_order"
                    class="btn btn-boba">
                    Lanjut Bayar
                </button>
            </form>
            </div>
        </div>
    </div>
</div>

<?php include 'assets/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>

const totalAwal =
<?= $totalAll ?>;

const voucherSelect =
document.getElementById(
'voucherSelect'
);

voucherSelect.addEventListener(
'change',
function(){

    const option =
    this.options[
        this.selectedIndex
    ];

    document.getElementById(
    'voucherUserId'
    ).value = option.value;

    const jenis =
    option.dataset.jenis;

    const nilai =
    parseInt(
        option.dataset.nilai
    ) || 0;

    const minimal =
    parseInt(
        option.dataset.minimal
    ) || 0;
    
    if(
    totalAwal < minimal
    ){
        alert(
            'Minimal belanja Rp ' +
            minimal.toLocaleString('id-ID')
        );
        this.value = 0;
        document.getElementById(
            'voucherUserId'
        ).value = 0;
        return;
    }

    let diskon = 0;

    if(jenis === 'persen'){

        diskon =
        Math.floor(
            totalAwal * nilai / 100
        );
    }

    else if(
        jenis === 'nominal'
    ){

        diskon = nilai;

    }

    let totalAkhir =
    totalAwal - diskon;

    if(totalAkhir < 0){
        totalAkhir = 0;
    }

    document.getElementById(
        'diskonVoucher'
    ).innerHTML =
    'Rp ' +
    diskon.toLocaleString(
        'id-ID'
    );

    document.getElementById(
        'totalBayar'
    ).innerHTML =
    'Rp ' +
    totalAkhir.toLocaleString(
        'id-ID'
    );
});
</script>
</body>
</html>



