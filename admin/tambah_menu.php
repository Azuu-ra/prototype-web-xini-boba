<?php
include '../config/koneksi.php';

if(isset($_POST['submit'])){

    $nama = $_POST['nama'];
    $harga = (int)$_POST['harga'];

    $gambar = '';
    if (isset($_FILES['gambar_file']) && $_FILES['gambar_file']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../img/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $ext = pathinfo($_FILES['gambar_file']['name'], PATHINFO_EXTENSION);
        $ext = strtolower($ext);
        $allowed = ['jpg','jpeg','png','webp','gif'];
        if (!in_array($ext, $allowed, true)) {
            header('Location: tambah_menu.php');
            exit();
        }

        $filename = 'menu_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $destPath = $uploadDir . $filename;

        if (move_uploaded_file($_FILES['gambar_file']['tmp_name'], $destPath)) {
            $gambar = 'img/' . $filename;
        }
    }

    if ($gambar === '') {
        header('Location: tambah_menu.php');
        exit();
    }

    mysqli_query($conn,
        "INSERT INTO menu(nama_menu,harga,gambar,status_menu)
        VALUES('$nama','$harga','$gambar','Ready')");

    header('Location: dashboard.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Menu</title>

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

<?php include '../assets/navbar.php'; ?>

<div style="margin-left:120px">

<div class="container mt-5 mb-5">
    <div class="card-modern overflow-hidden">
        <div class="header-modern p-4">
            <h3 class="mb-0">Tambah Menu</h3>
            <div class="small opacity-75">Isi data menu baru</div>
        </div>

        <div class="p-4">
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Nama Menu</label>
                    <input type="text" name="nama" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Harga</label>
                    <input type="number" name="harga" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Upload Gambar</label>
                    <input type="file" name="gambar_file" class="form-control" accept="image/*" required>
                </div>

                <div class="d-flex gap-2">
                    <button name="submit" class="btn btn-boba w-100 py-2 fw-semibold" type="submit">Simpan</button>
                    <a href="dashboard.php" class="btn btn-outline-secondary py-2 fw-semibold" style="border-radius:14px;">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</div>


</div>

</body>
</html>
