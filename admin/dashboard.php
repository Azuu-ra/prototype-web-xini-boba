<?php
include '../config/koneksi.php';

$dataMenu = mysqli_query($conn,"SELECT * FROM menu");

$totalMenu =
        mysqli_num_rows(
        mysqli_query(
        $conn,
        "SELECT * FROM menu"
        )
);

$totalUser =
        mysqli_num_rows(
        mysqli_query(
        $conn,
        "SELECT * FROM users
        WHERE role='user'"
        )
);

$totalOrder =
        mysqli_num_rows(
        mysqli_query(
        $conn,
        "SELECT * FROM orders"
        )
);

$omset =
        mysqli_fetch_assoc(
        mysqli_query(
        $conn,
        "SELECT
        SUM(total_harga) as total
        FROM transaksi"
        )
);

$produkTerlaris =
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

$topCustomer =
        mysqli_query(
        $conn,
        "SELECT
        u.username,
        SUM(t.total_harga) as total_belanja
        FROM transaksi t
        JOIN users u
        ON t.user_id=u.id
        GROUP BY t.user_id
        ORDER BY total_belanja DESC
        LIMIT 5"
);

$grafik =
        mysqli_query(
        $conn,
        "SELECT
        DATE(tanggal_transaksi) as tanggal,
        SUM(total_harga) as total
        FROM transaksi
        GROUP BY DATE(tanggal_transaksi)
        ORDER BY tanggal ASC"
);

$label = [];
$dataGrafik = [];

while(
    $row =
    mysqli_fetch_assoc(
    $grafik
)
){
    $label[] =
    $row['tanggal'];

    $dataGrafik[] =
    $row['total'];
}

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
        body{
    background:#f8f5f2;
            }

            .dashboard-header{
                background:linear-gradient(
                    135deg,
                    #6f4e37,
                    #5a3d2b
                );
                color:white;
                border-radius:20px;
                padding:30px;
                margin-bottom:25px;
                box-shadow:0 10px 24px rgba(0,0,0,.08);
            }

            .stat-card{
                border:none;
                border-radius:20px;
                overflow:hidden;
                box-shadow:0 8px 20px rgba(0,0,0,.08);
                transition:.3s;
            }

            .stat-card:hover{
                transform:translateY(-5px);
            }

            .stat-icon{
                font-size:2rem;
                opacity:.8;
            }

            .stat-value{
                font-size:1.8rem;
                font-weight:700;
            }

            .quick-btn{
                background:#6f4e37;
                color:white;
                border:none;
                border-radius:18px;
                padding:18px;
                text-decoration:none;
                display:block;
                text-align:center;
                transition:.3s;
            }

            .quick-btn:hover{
                background:#5a3d2b;
                color:white;
                transform:translateY(-3px);
            }

            .quick-btn i{
                font-size:1.5rem;
                display:block;
                margin-bottom:8px;
            }

            .dashboard-card{
                border:none;
                border-radius:20px;
                box-shadow:0 8px 20px rgba(0,0,0,.08);
            }

            .dashboard-card .card-header{
                background:#6f4e37;
                color:white;
                border:none;
                font-weight:600;
            }

            .rank-item{
                padding:10px 0;
                border-bottom:1px solid #eee;
            }

            .rank-item:last-child{
                border-bottom:none;
            }
    </style>
</head>

<body>
<?php include '../assets/navbar.php'; ?>

<div style="margin-left:120px">
<div class="container mt-4 mb-5">
<div class="dashboard-header">
<h2 class="mb-2">
    Dashboard Admin
</h2>

<p class="mb-0 opacity-75">
    Ringkasan aktivitas dan performa Xini Boba
</p>

</div>

<div class="row g-4 mb-4">
<div class="col-md-3">
<div class="card stat-card">
<div class="card-body text-center">
<div class="stat-icon">
<i class="fa fa-cutlery"></i>
</div>

<div class="stat-value">
<?= $totalMenu; ?>
</div>

<div>
Total Menu
</div>

</div>
</div>
</div>

<div class="col-md-3">

<div class="card stat-card">

<div class="card-body text-center">

<div class="stat-icon">
<i class="fa fa-users"></i>
</div>

<div class="stat-value">
<?= $totalUser; ?>
</div>

<div>
Total Pelanggan
</div>

</div>
</div>
</div>

<div class="col-md-3">

<div class="card stat-card">

<div class="card-body text-center">

<div class="stat-icon">
<i class="fa fa-shopping-cart"></i>
</div>

<div class="stat-value">
<?= $totalOrder; ?>
</div>

<div>
Total Order
</div>

</div>
</div>
</div>

<div class="col-md-3">
<div class="card stat-card">
<div class="card-body text-center">
<div class="stat-icon">
<i class="fa fa-money"></i>
</div>

<div class="stat-value">
Rp <?= number_format($omset['total']); ?>
</div>

<div>
Total Omset
</div>

</div>
</div>
</div>

</div>

<div class="row g-4 mb-4">

<div class="col-md-6">

<div class="card dashboard-card h-100">

<div class="card-header">

Produk Terlaris

</div>

<div class="card-body">

<?php while($produk=mysqli_fetch_assoc($produkTerlaris)){ ?>

<div class="rank-item d-flex justify-content-between">

<span>
<?= htmlspecialchars($produk['nama_menu']); ?>
</span>

<b>
<?= $produk['total_terjual']; ?>x
</b>

</div>

<?php } ?>

</div>

</div>

</div>

<div class="col-md-6">

<div class="card dashboard-card h-100">

<div class="card-header">

Top Customer

</div>

<div class="card-body">

<?php while($cust=mysqli_fetch_assoc($topCustomer)){ ?>

<div class="rank-item d-flex justify-content-between">

<span>
<?= htmlspecialchars($cust['username']); ?>
</span>

<b>
Rp <?= number_format($cust['total_belanja']); ?>
</b>

</div>
<?php } ?>
</div>
</div>
</div>
</div>


<div class="card dashboard-card">
<div class="card-header">
Grafik Penjualan
</div>
<div class="card-body">
<canvas id="chartPenjualan"></canvas>
</div>
</div>
<br>
<div class="row g-3 mb-4">
<div class="col-md-3">
<a
href="daftar_menu.php"
class="quick-btn">
<i class="fa fa-cutlery"></i>
Kelola Menu
</a>
</div>
<div class="col-md-3">
<a
href="admin_order.php"
class="quick-btn">
<i class="fa fa-shopping-bag"></i>
Pesanan
</a>
</div>
<div class="col-md-3">
<a
href="voucher_admin.php"
class="quick-btn">
<i class="fa fa-ticket"></i>
Voucher
</a>
</div>
<div class="col-md-3">
<a
href="transaksi.php"
class="quick-btn">
<i class="fa fa-bar-chart"></i>
Laporan
</a>
</div>
</div>

</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

new Chart(
document.getElementById(
'chartPenjualan'
),
{
type:'line',

data:{
labels:
<?= json_encode($label); ?>,

datasets:[
{
label:
'Penjualan',

data:
<?= json_encode($dataGrafik); ?>
}
]
}
}
);

</script>



</body>