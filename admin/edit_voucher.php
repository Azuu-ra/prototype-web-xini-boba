<?php
include '../config/koneksi.php';

if (!isset($_GET['id'])) {
    header('Location: voucher_admin.php');
    exit();
}

$id = (int)$_GET['id'];

if ($id <= 0) {
    header('Location: voucher_admin.php');
    exit();
}

$result = mysqli_query($conn,
"SELECT * FROM vouchers WHERE id='$id' LIMIT 1");

$voucher = mysqli_fetch_assoc($result);

if (!$voucher) {
    header('Location: voucher_admin.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Voucher</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body{
            background:#f8f5f2;
        }

        .card-modern{
            border:0;
            border-radius:20px;
            box-shadow:0 10px 24px rgba(0,0,0,0.08);
        }

        .header-modern{
            background:linear-gradient(135deg,#6f4e37,#5a3d2b);
            color:#fff;
            border-radius:18px 18px 0 0;
        }

        .btn-boba{
            background:#6f4e37;
            color:#fff;
            border:0;
            border-radius:14px;
        }

        .btn-boba:hover{
            background:#5a3d2b;
            color:#fff;
        }
    </style>
</head>
<body>

<div class="container mt-5 mb-5">

    <div class="card-modern overflow-hidden">

        <div class="header-modern p-4">
            <h3 class="mb-0">Edit Voucher</h3>
            <div class="small opacity-75">
                Ubah data voucher
            </div>
        </div>

        <div class="p-4">

            <form method="POST" action="update_voucher.php">

                <input type="hidden"
                       name="voucher_id"
                       value="<?= (int)$voucher['id']; ?>">

                <div class="mb-3">
                    <label class="form-label">Nama Voucher</label>

                    <input type="text"
                           name="nama_voucher"
                           class="form-control"
                           required
                           value="<?= htmlspecialchars($voucher['nama_voucher']); ?>">
                </div>

                <div class="mb-3">
                    <label>Jenis Voucher</label>

                    <select
                    name="jenis_voucher"
                    id="jenisVoucher"
                    class="form-control">

                    <option value="persen"
                    <?= $voucher['jenis_voucher']=='persen' ? 'selected' : '' ?>>
                    Diskon Persen
                    </option>

                    <option value="nominal"
                    <?= $voucher['jenis_voucher']=='nominal' ? 'selected' : '' ?>>
                    Potongan Nominal
                    </option>

                    <option value="gratis_menu"
                    <?= $voucher['jenis_voucher']=='gratis_menu' ? 'selected' : '' ?>>
                    Gratis Menu
                    </option>

                    </select>
                </div>
            <div id="voucherNormal">

                <div class="mb-3">
                    <label>Nilai Voucher</label>

                <input type="number"
                     name="nilai_voucher"
                     class="form-control"
                     value="<?= $voucher['nilai_voucher']; ?>">
                </div>

                <div class="mb-3">
                    <label>Minimal Belanja</label>
                    
                    <input type="number"
                    name="minimal_belanja"
                    class="form-control"
                    value="<?= $voucher['minimal_belanja']; ?>">
                </div>
            </div>

                <div class="mb-3">
                    <label>Tanggal Mulai</label>

                <input  type="date"
                     name="tanggal_mulai"
                     class="form-control"
                     value="<?= $voucher['tanggal_mulai']; ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Poin Dibutuhkan</label>

                    <input type="number"
                           name="poin_dibutuhkan"
                           class="form-control"
                           required
                           min="0"
                           value="<?= (int)$voucher['poin_dibutuhkan']; ?>">
                </div>

                <div class="mb-3">
                    <label>Tanggal Berakhir</label>

                    <input type="date"
                        name="tanggal_berakhir"
                         class="form-control"
                         value="<?= $voucher['tanggal_berakhir']; ?>">
                </div>

                <?php
                    $dataMenu =
                    mysqli_query(
                    $conn,
                    "SELECT * FROM menu"
                    );
                    ?>

                <div id="voucherGratis"
                    style="<?= $voucher['jenis_voucher']=='gratis_menu' ? '' : 'display:none;' ?>">

                    <label>Menu Gratis</label>
                    <select
                        name="menu_gratis_id"
                        class="form-control">

                    <option value="">
                        Pilih Menu
                    </option>

                    <?php
                    while($menu=mysqli_fetch_assoc($dataMenu)){
                    ?>

                    <option
                        value="<?= $menu['id']; ?>"
                        <?= $voucher['menu_gratis_id']==$menu['id'] ? 'selected' : '' ?>>

                        <?= htmlspecialchars($menu['nama_menu']); ?>
                    </option>
                    <?php } ?>
                    </select>
                    </div>

                <div class="d-flex gap-2">

                    <button type="submit"
                            class="btn btn-boba w-100 py-2 fw-semibold">
                        Simpan Perubahan
                    </button>

                    <a href="voucher_admin.php"
                       class="btn btn-outline-secondary py-2 fw-semibold"
                       style="border-radius:14px;">
                        Kembali
                    </a>

                </div>

            </form>

        </div>
    </div>
</div>

<script>

const jenisVoucher =
document.getElementById(
'jenisVoucher'
);

function toggleVoucher(){

    if(
        jenisVoucher.value
        ==
        'gratis_menu'
    ){

        document.getElementById(
        'voucherGratis'
        ).style.display =
        'block';

        document.getElementById(
        'voucherNormal'
        ).style.display =
        'none';

    }else{

        document.getElementById(
        'voucherGratis'
        ).style.display =
        'none';

        document.getElementById(
        'voucherNormal'
        ).style.display =
        'block';
    }
}

jenisVoucher.addEventListener(
'change',
toggleVoucher
);

toggleVoucher();

</script>
</body>
</html>