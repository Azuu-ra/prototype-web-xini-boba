<?php
session_start();
include 'config/koneksi.php';

$dataMenu = mysqli_query($conn,"SELECT * FROM menu");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Xini Boba</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="assets/navbarstyle.css">



    <style>
        /* Navbar modern */
        .navbar-xini {
            background: linear-gradient(135deg, #6f4e37, #5a3d2b);
        }
        .navbar-xini .navbar-brand {
            font-weight: 800;
            letter-spacing: 0.3px;
        }
        .navbar-user-pill {
            border: 1px solid rgba(255,255,255,0.25);
            background: rgba(255,255,255,0.10);
            backdrop-filter: blur(6px);
        }

        /* Card menu modern + hover effect */
        .menu-card {
            border: 0;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 10px 24px rgba(0,0,0,0.08);
            transition: transform .2s ease, box-shadow .2s ease;
        }
        .menu-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 34px rgba(0,0,0,0.14);
        }
        .menu-card-img {
            height: 250px;
            object-fit: cover;
            width: 100%;
            transition: transform .25s ease;
        }
        .menu-card:hover .menu-card-img {
            transform: scale(1.06);
        }
        .status-badge {
            border-radius: 999px;
            padding: 8px 14px;
            font-weight: 600;
        }
        .price {
            font-weight: 800;
            letter-spacing: 0.2px;
        }

        /* Button */
        .btn-boba {
            background:#6f4e37;
            color:white;
            border:0;
            border-radius: 12px;
            transition: transform .15s ease, background-color .15s ease;
        }
        .btn-boba:hover {
            background:#5a3d2b;
            color:white;
            transform: translateY(-1px);
        }
        .btn-boba:active {
            transform: translateY(0);
        }

        /* Responsive tweaks */
        @media (max-width: 575.98px) {
            .menu-card-img {
                height: 220px;
            }
        }
    </style>
</head>
<body>

<?php include 'assets/navbar.php'; ?>

<div style="margin-left:120px">
    <div class="container">
        
        <button class="navbar-toggler text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu"
                aria-controls="navbarMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMenu">
            <div class="ms-auto d-flex align-items-center gap-3">
                <div class="navbar-user-pill text-white rounded-pill px-3 py-2">
                    <div class="small opacity-75">Poin Anda</div>
                    <div class="fw-bold">
                        <?php
                        // poin disimpan di tabel users, bisa jadi tidak selalu ada di session
                        $poin = 0;
                        if (isset($_SESSION['poin'])) {
                            $poin = (int)$_SESSION['poin'];
                        } else if (isset($_SESSION['id'])) {
                            $resPoin = mysqli_fetch_assoc(mysqli_query($conn, "SELECT poin FROM users WHERE id='" . (int)$_SESSION['id'] . "'"));
                            if ($resPoin && isset($resPoin['poin'])) {
                                $poin = (int)$resPoin['poin'];
                            }
                        }
                        echo $poin;
                        ?>
                    </div>
                </div>

                <a href="profile.php" class="btn btn-outline-light btn-sm fw-semibold rounded-pill px-3 py-2">
                    Profil
                </a>

                <a href="logout.php" class="btn btn-light btn-sm fw-semibold rounded-pill px-3 py-2">
                    Logout
                </a>
            </div>
        </div>
    </div>
</nav>

<div class="container mt-4 mt-lg-5 mb-5">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
        <h2 class="fw-bold mb-0">Menu Boba</h2>
        <div class="text-muted">Pilih menu favoritmu!</div>
    </div>

    <div class="row g-4">
        <?php while($menu = mysqli_fetch_assoc($dataMenu)) { ?>

        <div class="col-12 col-sm-6 col-md-4">
            <div class="menu-card h-100 bg-white">
                <div class="position-relative">
                    <img
                        src="<?= $menu['gambar']; ?>"
                        class="menu-card-img"
                        alt="<?= $menu['nama_menu']; ?>"
                    >
                    <div class="position-absolute top-0 start-0 m-3">
                        <?php if(((int)($menu['stok'] ?? 0)) > 0) { ?>
                            <span class="badge bg-success status-badge">Ready</span>
                        <?php } else { ?>
                            <span class="badge bg-danger status-badge">Sold Out</span>
                        <?php } ?>
                    </div>
                </div>

                <div class="card-body p-3 p-md-4 d-flex flex-column">
                    <h4 class="mb-1 fw-bold"><?= $menu['nama_menu']; ?></h4>
                    <div class="price text-dark mb-3">Rp <?= number_format($menu['harga']); ?></div>

                    <form action="tambah_ke_keranjang.php" method="POST" class="mt-auto">
                        <input type="hidden" name="menu_id" value="<?= $menu['id']; ?>">

                        <div class="d-flex align-items-center gap-2 mt-2">

    <button type="button"
            class="btn btn-outline-secondary rounded-circle minus-btn"
            style="width:40px;height:40px;">
        -
    </button>

    <input type="number"
           name="jumlah"
           class="form-control text-center rounded-3 jumlah-input"
           value="1"
           min="1"
           style="max-width:80px;"
           <?= ((int)($menu['stok'] ?? 0)) <= 0 ? 'disabled' : ''; ?>
    >

    <button type="button"
            class="btn btn-outline-secondary rounded-circle plus-btn"
            style="width:40px;height:40px;">
        +
    </button>

</div>

                        <button class="btn btn-boba w-100 mt-3 py-2 fw-semibold"
                                type="submit"
                                <?= ((int)($menu['stok'] ?? 0)) <= 0 ? 'disabled' : ''; ?>
                        >
                            Tambah ke Keranjang
                        </button>
                    </form>

                </div>
            </div>
        </div>

        <?php } ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<?php include 'assets/footer.php'; ?>

<script>

document.querySelectorAll('.menu-card').forEach(card => {

    const minusBtn = card.querySelector('.minus-btn');
    const plusBtn  = card.querySelector('.plus-btn');
    const input    = card.querySelector('.jumlah-input');

    if(minusBtn && plusBtn && input){

        minusBtn.addEventListener('click', () => {

            let value = parseInt(input.value) || 1;

            if(value > 1){
                input.value = value - 1;
            }

        });

        plusBtn.addEventListener('click', () => {

            let value = parseInt(input.value) || 1;

            input.value = value + 1;

        });

    }

});

</script>
</body>
</html>




