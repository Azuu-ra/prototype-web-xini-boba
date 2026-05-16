<?php
session_start();
include '../config/koneksi.php';

// Hanya admin yang boleh akses
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

// Rekap penjualan
$queryTotal = mysqli_query($conn, "SELECT COALESCE(SUM(total),0) AS omzet, COUNT(*) AS total_transaksi FROM orders");
$totalData = mysqli_fetch_assoc($queryTotal);
$omzet = (int)($totalData['omzet'] ?? 0);
$totalTransaksi = (int)($totalData['total_transaksi'] ?? 0);

// Total poin berdasarkan transaksi (sesuai kalkulasi poin di checkout)
$queryPoin = mysqli_query($conn, "SELECT COALESCE(SUM(FLOOR(total/10000)),0) AS total_poin FROM orders");
$poinData = mysqli_fetch_assoc($queryPoin);
$totalPoin = (int)($poinData['total_poin'] ?? 0);

// Rekap per menu
$queryPerMenu = mysqli_query($conn, "
    SELECT m.nama_menu, m.id AS menu_id, m.gambar, m.harga,
           COALESCE(SUM(o.jumlah),0) AS qty,
           COALESCE(SUM(o.total),0) AS omzet_menu
    FROM menu m
    LEFT JOIN orders o ON o.menu_id = m.id
    GROUP BY m.id, m.nama_menu, m.gambar, m.harga
    ORDER BY omzet_menu DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan - Xini Boba</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/style.css">
    <link rel="stylesheet" href="../assets/navbarstyle.css">

    <style>
        body { background:#f8f5f2; }
        /* Tampilannya seperti kertas (lebih pas untuk print/cetak) */
        .report-card { 
            border:0; 
            border-radius:20px; 
            box-shadow:0 10px 24px rgba(0,0,0,0.08); 
            overflow:hidden;
            background: linear-gradient(0deg, rgba(255,255,255,0.72), rgba(255,255,255,0.72)), #fff;
        }

        /* Tekstur kertas halus */
        .report-card:before {
            content: "";
            position: absolute;
            inset: 0;
            background-image: radial-gradient(rgba(0,0,0,0.03) 1px, transparent 1px);
            background-size: 6px 6px;
            opacity: 0.35;
            pointer-events: none;
        }

        /* Pastikan ::before posisinya benar */
        .report-card { position: relative; }

        .report-header { background:linear-gradient(135deg,#6f4e37,#5a3d2b); color:#fff; }
        .stat-pill { border-radius:16px; background:rgba(255,255,255,0.12); padding:14px 16px; }
        .table-card { border:1px solid rgba(0,0,0,0.06); border-radius:18px; overflow:hidden; }
        .table thead th { background:rgba(111,78,55,0.08); }
        .status-badge { border-radius:999px; padding:6px 12px; font-weight:600; }
        .menu-thumb { width:54px; height:54px; object-fit:cover; border-radius:14px; border:1px solid rgba(0,0,0,0.06); }

        @media print {
            body { background: #fff; }
            .no-print { display:none !important; }
            .report-card { box-shadow:none; }
        }
    </style>
</head>
<body>

<?php include '../assets/navbar.php'; ?>

<div style="margin-left:120px">

<div class="container mt-4 mt-lg-5 mb-5">
    <div class="report-card">
        <div class="report-header p-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div>
                    <h2 class="mb-0">Laporan Penjualan</h2>
                    <div class="small opacity-75">Owner/Admin • Rekap berdasarkan tabel <b>orders</b></div>
                </div>
                <div class="no-print d-flex gap-2">
                    <a href="dashboard.php" class="btn btn-light btn-sm rounded-pill">Kembali</a>
                    <button class="btn btn-outline-light btn-sm rounded-pill" onclick="window.print();">Cetak Laporan</button>
                </div>
            </div>
        </div>

        <div class="p-4">
            <div class="row g-3">
                <div class="col-12 col-md-4">
                    <div class="stat-pill">
                        <div class="small opacity-75">Omzet</div>
                        <div class="fs-4 fw-bold">Rp <?= number_format($omzet); ?></div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="stat-pill">
                        <div class="small opacity-75">Total Transaksi</div>
                        <div class="fs-4 fw-bold"><?= $totalTransaksi; ?></div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="stat-pill">
                        <div class="small opacity-75">Total Poin (estimasi)</div>
                        <div class="fs-4 fw-bold"><?= $totalPoin; ?></div>
                    </div>
                </div>
            </div>

            <div class="mt-4 table-card bg-white">
                <table class="table mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>Menu</th>
                            <th class="text-end">Harga</th>
                            <th class="text-center">Qty</th>
                            <th class="text-end">Omzet</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($queryPerMenu)) { 
                            $qty = (int)($row['qty'] ?? 0);
                            $omzMenu = (int)($row['omzet_menu'] ?? 0);
                            ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <?php if (!empty($row['gambar'])) { ?>
                                            <img src="<?= $row['gambar']; ?>" class="menu-thumb" alt="<?= htmlspecialchars($row['nama_menu']); ?>">
                                        <?php } else { ?>
                                            <div class="menu-thumb d-flex align-items-center justify-content-center bg-light text-muted">-</div>
                                        <?php } ?>
                                        <div>
                                            <div class="fw-semibold"><?= $row['nama_menu']; ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end">Rp <?= number_format((int)($row['harga'] ?? 0)); ?></td>
                                <td class="text-center fw-semibold"><?= $qty; ?></td>
                                <td class="text-end fw-bold">Rp <?= number_format($omzMenu); ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

</div>

</body>
</html>

