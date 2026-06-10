<?php
include '../config/koneksi.php';

$dataMenu =
    mysqli_query(
    $conn,
    "SELECT * FROM menu"
    );

if(isset($_POST['simpan'])){
    $nama = $_POST['nama_voucher'];
    $poin = $_POST['poin_dibutuhkan'];

    $jenis = $_POST['jenis_voucher'];
    $nilai = (int)$_POST['nilai_voucher'];
    $minimal = (int)$_POST['minimal_belanja'];
    $mulai = $_POST['tanggal_mulai'];
    $berakhir = $_POST['tanggal_berakhir'];
    $menuGratis =
    !empty($_POST['menu_gratis_id'])
    ?
    (int)$_POST['menu_gratis_id']
    :
"NULL";

    $stok = isset($_POST['stok']) ? (int)$_POST['stok'] : 0;
    if ($stok < 0) $stok = 0;

    mysqli_query($conn,

            "INSERT INTO vouchers
            (   nama_voucher,
                poin_dibutuhkan,
                stok,
                jenis_voucher,
                nilai_voucher,
                minimal_belanja,
                tanggal_mulai,
                tanggal_berakhir,
                menu_gratis_id )

            VALUES
            (   '$nama',
                '$poin',
                '$stok',
                '$jenis',
                '$nilai',
                '$minimal',
                '$mulai',
                '$berakhir',
                $menuGratis )"
            );

    header('Location: voucher_admin.php');
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Voucher</title>

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
    </style>
</head>
<body>

<?php include '../assets/navbar.php'; ?>

<div style="margin-left:120px">

<div class="container mt-5 mb-5">

    <div class="admin-card overflow-hidden">
        <div class="admin-header p-4">
            <h3 class="mb-0">Tambah Voucher</h3>
            <div class="small opacity-75">Isi voucher baru</div>
        </div>

        <div class="p-4">
            <form method="POST">

                <div class="mb-3">
                    <label class="form-label">Nama Voucher</label>
                    <input type="text"
                           name="nama_voucher"
                           class="form-control"
                    >
                </div>

                <div class="mb-3">
                    <label class="form-label">Poin Dibutuhkan</label>
                    <input type="number"
                           name="poin_dibutuhkan"
                           class="form-control"
                    >
                </div>


                <div class="mb-3">
                    <label class="form-label">Stok</label>
                    <input type="number"
                           name="stok"
                           class="form-control"
                           value="0"
                           min="0"
                    >
                </div>

                    <div class="mb-3">
                    <label>Jenis Voucher</label>

                    <select
                    name="jenis_voucher"
                    id="jenis_voucher"
                    class="form-control">

                        <option value="persen">
                            Diskon Persen
                        </option>

                        <option value="nominal">
                            Potongan Nominal
                        </option>

                        <option value="gratis_menu">
                            Gratis Menu
                        </option>

                    </select>
                </div>

                <div class="mb-3" id="box_nilai">
                    <label>Nilai Voucher</label>

                    <input
                    type="number"
                    name="nilai_voucher"
                    class="form-control">
                </div>

                <div class="mb-3">
                    <label>Minimal Belanja</label>

                    <input
                    type="number"
                    name="minimal_belanja"
                    class="form-control"
                    value="0">
                </div>

                <div class="mb-3">
                    <label>Tanggal Mulai</label>

                    <input
                    type="date"
                    name="tanggal_mulai"
                    class="form-control">
                </div>

                <div class="mb-3">
                    <label>Tanggal Berakhir</label>

                    <input
                    type="date"
                    name="tanggal_berakhir"
                    class="form-control">
                </div>

                <div class="mb-3" id="box_menu">

                    <label>Menu Gratis</label>

                    <select
                    name="menu_gratis_id"
                    class="form-control">

                    <option value="">
                    -- Tidak Ada --
                    </option>

                    <?php
                    while(
                    $menu =
                    mysqli_fetch_assoc(
                    $dataMenu
                    )
                    ){
                    ?>

                    <option
                    value="<?= $menu['id']; ?>">

                    <?= htmlspecialchars(
                    $menu['nama_menu']
                    ); ?>

                    </option>

                    <?php
                    }
                    ?>

                    </select>

                    </div>

                <div class="d-flex gap-2 flex-wrap">
                    <button name="simpan" class="btn btn-boba flex-fill" type="submit">Simpan</button>
                    <a href="voucher_admin.php" class="btn btn-outline-secondary flex-fill">Kembali</a>
                </div>

            </form>
        </div>
    </div>

</div>

</div>
<script>

function toggleVoucher(){

    let jenis =
    document.getElementById(
    "jenis_voucher"
    ).value;

    let boxNilai =
    document.getElementById(
    "box_nilai"
    );

    let boxMenu =
    document.getElementById(
    "box_menu"
    );

    if(
        jenis ==
        "gratis_menu"
    ){

        boxMenu.style.display =
        "block";

        boxNilai.style.display =
        "none";

    }else{

        boxMenu.style.display =
        "none";

        boxNilai.style.display =
        "block";

    }
}

document
    .getElementById(
    "jenis_voucher"
)
    .addEventListener(
    "change",
toggleVoucher
);

toggleVoucher();

</script>

</body>
</html>

