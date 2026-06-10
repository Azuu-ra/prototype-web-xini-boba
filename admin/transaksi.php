<?php
include '../config/koneksi.php';

$where = "";

if(isset($_GET['filter'])){

    if($_GET['filter']=="7"){
        $where =
        "WHERE tanggal_transaksi >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
    }

    elseif($_GET['filter']=="14"){
        $where =
        "WHERE tanggal_transaksi >= DATE_SUB(NOW(), INTERVAL 14 DAY)";
    }

    elseif($_GET['filter']=="30"){
        $where =
        "WHERE tanggal_transaksi >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
    }
}

if(
    !empty($_GET['tgl_awal'])
    &&
    !empty($_GET['tgl_akhir'])
){
    $awal = $_GET['tgl_awal'];
    $akhir = $_GET['tgl_akhir'];

    $where =
    "WHERE DATE(tanggal_transaksi)
    BETWEEN '$awal'
    AND '$akhir'";
}

$sql =
"SELECT
t.*,
u.username

FROM transaksi t

JOIN users u
ON t.user_id=u.id

$where

ORDER BY t.id DESC";
if(isset($_GET['export'])){


header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Laporan_Xini_Boba.xls");

$data = mysqli_query(
    $conn,
    $sql
);


$totalOmzet = 0;
$produkTerlarisExport =
mysqli_query(
        $conn,
        "SELECT
        m.nama_menu,
        SUM(oi.qty) as total_terjual
        FROM order_items oi
        JOIN menu m
        ON oi.menu_id = m.id
        GROUP BY oi.menu_id
        ORDER BY total_terjual DESC
        LIMIT 5"
);

echo '
<table border="0">
    <tr>
        <td colspan="4" align="center">
            <h2>XINI BOBA</h2>
        </td>
    </tr>

    <tr>
        <td colspan="4" align="center">
            Laporan Transaksi
        </td>
    </tr>

    <tr>
        <td colspan="4">
            Dicetak :
            '.date("d-m-Y H:i").'
        </td>
    </tr>

    <tr></tr>
</table>

<table border="1">
    <tr style="background:#6f4e37;color:white;font-weight:bold;">
        <th>Kode Order</th>
        <th>Pelanggan</th>
        <th>Total</th>
        <th>Tanggal</th>
    </tr>
';

while($row = mysqli_fetch_assoc($data)){

    $totalOmzet += $row['total_harga'];

    echo '
    <tr>
        <td>'.$row['kode_order'].'</td>
        <td>'.$row['username'].'</td>
        <td>Rp '.number_format($row['total_harga']).'</td>
        <td>'.$row['tanggal_transaksi'].'</td>
    </tr>
    ';
}

echo '
    <tr>
        <td colspan="2">
            <b>Total Omzet</b>
        </td>

        <td colspan="2">
            <b>Rp '.number_format($totalOmzet).'</b>
        </td>
    </tr>
</table>
<br><br>

<table border="1">

<tr>
    <th colspan="2">
        TOP 5 PRODUK TERLARIS
    </th>
</tr>

<tr>
    <th>Menu</th>
    <th>Total Terjual</th>
</tr>
';

while(
$produk =
mysqli_fetch_assoc(
$produkTerlarisExport
)
){

echo '

<tr>
    <td>'.$produk['nama_menu'].'</td>
    <td>'.$produk['total_terjual'].' Cup</td>
</tr>
';

}
echo '</table>';
exit();
}

$totalPendapatan =
    mysqli_fetch_assoc(
    mysqli_query(
    $conn,
    "SELECT SUM(total_harga) as total
    FROM transaksi"
    )
);

$totalTransaksi =
    mysqli_fetch_assoc(
    mysqli_query(
    $conn,
    "SELECT COUNT(*) as total
    FROM transaksi"
    )
);

$data =
mysqli_query(
    $conn,
    $sql
);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Kelola Pesanan</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

<link rel="stylesheet" href="../assets/style.css">
<link rel="stylesheet" href="../assets/navbarstyle.css">

<style>

body{
    background:#f8f5f2;
}

.card-modern{
    border:0;
    border-radius:20px;
    box-shadow:0 10px 24px rgba(0,0,0,.08);
}

.header-modern{
    background:linear-gradient(
        135deg,
        #6f4e37,
        #5a3d2b
    );
    color:white;
}

.badge-order{
    font-size:.85rem;
    padding:8px 14px;
    border-radius:50px;
}

.order-card{
    border:none;
    border-radius:18px;
    box-shadow:0 4px 12px rgba(0,0,0,.08);
}

.bukti-img{
    max-height:350px;
    width:auto;
    border-radius:12px;
}

</style>
</head>
<body>

<?php include '../assets/navbar.php'; ?>

<div style="margin-left:120px">
<div class="container mt-5 mb-5">
<div class="card-modern overflow-hidden">
<div class="header-modern p-4">

    <h3 class="mb-0">
        <i class="fa fa-bar-chart"></i>
        Data Transaksi
    </h3>

    <div class="small opacity-75">
        Kelola Laporan transaksi 
    </div>

</div>  

<div class="p-4">

<div class="row mb-4">

<div class="col-md-6">

<div class="card shadow-sm border-0">
<div class="card-body">

<h6>Total Pendapatan</h6>


<h3>
Rp <?= number_format(
$totalPendapatan['total'] ?? 0
); ?>
</h3>

</div>
</div>

</div>

<div class="col-md-6">

<div class="card shadow-sm border-0">
<div class="card-body">

<h6>Total Transaksi</h6>

<h3>
<?= $totalTransaksi['total'] ?? 0; ?>
</h3>

</div>
</div>

</div>

</div>


    <form method="GET" class="row g-2 mb-3">

<div class="col-md-3">

<select
name="filter"
class="form-select">

<option value="">
Semua Data
</option>

<option
value="7"
<?= ($_GET['filter'] ?? '') == '7' ? 'selected' : '' ?>>
7 Hari Terakhir
</option>

<option
value="14"
<?= ($_GET['filter'] ?? '') == '14' ? 'selected' : '' ?>>
14 Hari Terakhir
</option>

<option
value="30"
<?= ($_GET['filter'] ?? '') == '30' ? 'selected' : '' ?>>
1 Bulan Terakhir
</option>

</select>

</div>

<div class="col-md-3">

<input
type="date"
name="tgl_awal"
class="form-control"
value="<?= $_GET['tgl_awal'] ?? '' ?>">

</div>

<div class="col-md-3">

<input
type="date"
name="tgl_akhir"
class="form-control"
value="<?= $_GET['tgl_akhir'] ?? '' ?>">

</div>

<div class="col-md-3">

<button
class="btn btn-boba w-100">

Tampilkan

</button>

</div>

</form>

<a
href="?export=1&filter=<?= $_GET['filter'] ?? '' ?>&tgl_awal=<?= $_GET['tgl_awal'] ?? '' ?>&tgl_akhir=<?= $_GET['tgl_akhir'] ?? '' ?>"
class="btn btn-success mb-3">

<i class="fa fa-file-excel-o"></i>
Export Excel

</a>

<div class="table-responsive">

<table class="table table-striped">

<thead>

<tr>
<th>Kode Order</th>
<th>Pelanggan</th>
<th>Total</th>
<th>Tanggal</th>
</tr>

</thead>

<tbody>

<?php
while($row=mysqli_fetch_assoc($data)){
?>

<tr>

<td>
<?= $row['kode_order']; ?>
</td>

<td>
<?= htmlspecialchars($row['username']); ?>
</td>

<td>
Rp <?= number_format($row['total_harga']); ?>
</td>

<td>
<?= $row['tanggal_transaksi']; ?>
</td>

</tr>

<?php
}
?>

</tbody>

</table>

</div>

</div>

</div>

</div>
</div>

</div>
<?php include '../assets/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
