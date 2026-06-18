<?php
session_start();
include 'config/koneksi.php';

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}

$user_id = (int)$_SESSION['id'];

// Load data user
$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id, username, poin, role FROM users WHERE id='$user_id'"));

// Simple update handlers (ubah nama / password)
$info = '';
if (isset($_POST['update_profile'])) {
    $newUsername = trim($_POST['username'] ?? '');
    if ($newUsername !== '') {
        mysqli_query($conn, "UPDATE users SET username='" . mysqli_real_escape_string($conn, $newUsername) . "' WHERE id='$user_id'");
        $_SESSION['username'] = $newUsername;
        $info = 'Nama berhasil diperbarui.';
        // refresh
        $user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id, username, poin, role FROM users WHERE id='$user_id'"));
    }
}



if (isset($_POST['update_password'])) {

    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';

    // ambil password hash user
    $q = mysqli_query($conn, "SELECT password FROM users WHERE id='$user_id'");
    $dataPassword = mysqli_fetch_assoc($q);

    // cek password lama
    if ($dataPassword && password_verify($currentPassword, $dataPassword['password'])) {

        if ($newPassword !== '') {

            // hash password baru
            $newHash = password_hash($newPassword, PASSWORD_DEFAULT);

            mysqli_query($conn,
                "UPDATE users 
                 SET password='$newHash' 
                 WHERE id='$user_id'"
            );

            $info = 'Password berhasil diperbarui.';

        } else {

            $info = 'Password baru kosong.';

        }

    } else {

        $info = 'Password saat ini salah.';

    }

    // refresh data
    $user = mysqli_fetch_assoc(
        mysqli_query($conn,
        "SELECT id, username, poin, role 
         FROM users 
         WHERE id='$user_id'")
    );
}

$avatarId = ((int)($user_id % 2)); // 0 atau 1
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - Xini Boba</title>
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
        <div class="card-modern overflow-hidden">
            <div class="profile-header p-4 user-theme-gradient">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <img
                            src="<?php echo ($avatarId === 0) ? 'img/avatar1.png' : 'img/avatar2.png'; ?>"
                            class="avatar-circle bg-white"
                            alt="Avatar">
                        <div>
                            <h2 class="mb-0">Profil Anda</h2>
                            <div class="small opacity-75">
                                @<?php echo htmlspecialchars($user['username'] ?? 'user'); ?>
                            </div>
                            <div class="small opacity-75">
                                Poin Anda:
                                <b><?php echo (int)($user['poin'] ?? 0); ?></b>
                            </div>
                        </div>
                    </div>
                    <a href="my_voucher.php" class="checkin-shortcut text-decoration-none">
                        <i class="fa fa-ticket"></i>
                        <span>My vocher</span>
                    </a>
                </div>
            </div>

        

        <div class="p-4">
            <?php if ($info !== '') { ?>
                <div class="alert alert-info rounded-4"><?= htmlspecialchars($info) ?></div>
            <?php } ?>

            <div class="row g-4">
                <div class="col-12 col-lg-6">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4">
                            <h4 class="fw-bold">Ubah Nama</h4>
                            <p class="text-muted mb-3">Atur nama tampilan untuk akun Anda.</p>

                            <form method="POST">
                                <div class="mb-3">
                                    <label class="form-label">Nama</label>
                                    <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($user['username'] ?? '') ?>" required>
                                </div>
                                <button class="btn btn-boba w-100 py-2 fw-semibold" type="submit" name="update_profile">Simpan Nama</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-6">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4">
                            <h4 class="fw-bold">Ganti Kata Sandi</h4>
                            <p class="text-muted mb-3">Masukkan password saat ini dan password baru.</p>

                            <form method="POST">
                                <div class="mb-3">
                                    <label class="form-label">Password Saat Ini</label>
                                    <input type="password" name="current_password" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Password Baru</label>
                                    <input type="password" name="new_password" class="form-control" required>
                                </div>
                                <button class="btn btn-boba w-100 py-2 fw-semibold" type="submit" name="update_password">Simpan Password</button>
                            </form>
                            <div class="mt-3 text-end">
                                <a href="password_reset.php?from=profile" class="small text-decoration-none">Reset password via email</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4 d-flex align-items-start justify-content-between flex-wrap gap-3">
                            <div>
                                <h4 class="fw-bold">Ringkasan</h4>
                                <div class="small text-muted">Role: <b><?= htmlspecialchars($user['role'] ?? '') ?></b></div>
                                <div class="small text-muted">ID: <b><?= (int)($user['id'] ?? 0) ?></b></div>
                            </div>
                            <a href="menu.php" class="btn btn-outline-secondary rounded-pill">Ke Menu</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


</div>
<?php include 'assets/footer.php'; ?>

</body>
</html>

