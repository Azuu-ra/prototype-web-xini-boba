<?php
session_start();
include 'config/koneksi.php';

// Determine mode: 'forgot' or 'reset'
$mode = $_GET['action'] ?? 'forgot';
$postAction = $_POST['action'] ?? null;
$returnPage = $_GET['from'] ?? $_POST['from'] ?? 'login';
$message = '';
$errors = [];
$showForm = true;

function currentUrl()
{
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $path = $_SERVER['PHP_SELF'];
    return $scheme . '://' . $host . $path;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($postAction === 'forgot') {
        $email = trim($_POST['email'] ?? '');

        if ($email === '') {
            $errors[] = 'Email harus diisi.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Format email tidak valid.';
        }

        if (empty($errors)) {
            $emailEsc = mysqli_real_escape_string($conn, $email);
            $userQuery = mysqli_query($conn, "SELECT id FROM users WHERE email='$emailEsc' LIMIT 1");

            if ($userQuery && mysqli_num_rows($userQuery) > 0) {
                $user = mysqli_fetch_assoc($userQuery);
                $userId = (int)$user['id'];
                $token = bin2hex(random_bytes(32));
                $tokenEsc = mysqli_real_escape_string($conn, $token);

                $insert = mysqli_query(
                    $conn,
                    "INSERT INTO password_resets (user_id, token, expires_at, used) 
                     VALUES ('$userId', '$tokenEsc', DATE_ADD(NOW(), INTERVAL 1 HOUR), 0)"
                );

                if ($insert) {
                    $link = currentUrl() . '?action=reset&token=' . urlencode($token) . '&from=' . urlencode($returnPage);
                    $message = 'Permintaan reset berhasil. Gunakan link berikut untuk mengatur ulang password:<br><strong><a href="' . htmlspecialchars($link) . '">' . htmlspecialchars($link) . '</a></strong><br><small>Link berlaku 1 jam.</small>';
                    $showForm = false;
                } else {
                    $errors[] = 'Gagal membuat token reset. Silakan coba lagi.';
                }
            } else {
                $message = 'Jika email terdaftar, link reset akan dikirim ke email Anda.';
                $showForm = false;
            }
        }
    }

    if ($postAction === 'reset_password') {
        $token = trim($_POST['token'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if ($token === '') {
            $errors[] = 'Token reset tidak ditemukan.';
        }
        if ($password === '') {
            $errors[] = 'Password baru harus diisi.';
        }
        if ($confirmPassword === '') {
            $errors[] = 'Konfirmasi password harus diisi.';
        }
        if ($password !== $confirmPassword) {
            $errors[] = 'Password dan konfirmasi tidak sama.';
        }
        if (strlen($password) < 6) {
            $errors[] = 'Password minimal 6 karakter.';
        }

        if (empty($errors)) {
            $tokenEsc = mysqli_real_escape_string($conn, $token);
            $resetQuery = mysqli_query(
                $conn,
                "SELECT pr.id, pr.user_id
                 FROM password_resets pr
                 WHERE pr.token='$tokenEsc'
                   AND pr.used=0
                   AND pr.expires_at > NOW()
                 LIMIT 1"
            );

            if ($resetQuery && mysqli_num_rows($resetQuery) > 0) {
                $resetRow = mysqli_fetch_assoc($resetQuery);
                $resetId = (int)$resetRow['id'];
                $userId = (int)$resetRow['user_id'];
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                $passwordHashEsc = mysqli_real_escape_string($conn, $passwordHash);

                $updatePassword = mysqli_query(
                    $conn,
                    "UPDATE users SET password='$passwordHashEsc' WHERE id='$userId'"
                );

                if ($updatePassword) {
                    mysqli_query($conn, "UPDATE password_resets SET used=1 WHERE id='$resetId'");
                    $message = 'Password berhasil diubah. <a href="login.php">Klik di sini untuk login</a>.';
                    $showForm = false;
                } else {
                    $errors[] = 'Gagal memperbarui password. Silakan coba lagi.';
                }
            } else {
                $errors[] = 'Token reset tidak valid atau sudah kadaluarsa.';
            }
        }
    }
}

// Try to get token from GET (initial link) or POST (after form submit)
$requestedToken = trim($_GET['token'] ?? $_POST['token'] ?? '');
$returnPage = trim($_GET['from'] ?? $_POST['from'] ?? $returnPage);
$showResetForm = ($mode === 'reset' && $requestedToken !== '');

// Only validate token if we're not already processing a POST
// (POST errors should keep the form visible)
if ($showResetForm && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    $tokenEsc = mysqli_real_escape_string($conn, $requestedToken);
    $query = mysqli_query(
        $conn,
        "SELECT id FROM password_resets WHERE token='$tokenEsc' AND used=0 AND expires_at > NOW() LIMIT 1"
    );
    if (!$query || mysqli_num_rows($query) === 0) {
        $errors[] = 'Token reset tidak valid atau sudah kadaluarsa.';
        $showResetForm = false;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset - Xini Boba</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background:#f8f5f2; }
        .reset-wrap { max-width:480px; }
        .reset-card { border:0; border-radius:20px; box-shadow:0 10px 24px rgba(0,0,0,0.08); overflow:hidden; }
        .reset-header { background:linear-gradient(135deg,#6f4e37,#5a3d2b); color:#fff; padding:24px 18px; }
        .btn-boba { background:#6f4e37; color:#fff; border:0; border-radius:14px; transition:transform .15s ease, background-color .15s ease; }
        .btn-boba:hover { background:#5a3d2b; color:#fff; transform:translateY(-1px); }
        .alert-link a { color:#6f4e37; font-weight:700; }
    </style>
</head>
<body>
<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-12 reset-wrap">
            <div class="reset-card">
                <div class="reset-header text-center">
                    <h3 class="mb-0">Reset Password</h3>
                    <div class="small opacity-75 mt-2">
                        Gunakan email yang kamu daftarkan.
                    </div>
                </div>
                <div class="p-4">
                    <?php if (!empty($message)): ?>
                        <div class="alert alert-success alert-link">
                            <?= $message ?>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <?php if ($showForm && !$showResetForm): ?>
                        <form method="POST" action="password_reset.php">
                            <input type="hidden" name="action" value="forgot">
                            <div class="mb-3">
                                <label class="form-label">Email terdaftar</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-boba w-100 py-2">Kirim Link Reset</button>
                        </form>
                    <?php elseif ($showResetForm): ?>
                        <form method="POST" action="password_reset.php?action=reset&token=<?= urlencode($requestedToken) ?>&from=<?= urlencode($returnPage) ?>">
                            <input type="hidden" name="action" value="reset_password">
                            <input type="hidden" name="token" value="<?= htmlspecialchars($requestedToken) ?>">
                            <input type="hidden" name="from" value="<?= htmlspecialchars($returnPage) ?>">
                            <div class="mb-3">
                                <label class="form-label">Password baru</label>
                                <input type="password" name="password" class="form-control" required>
                                <small class="text-muted">Minimal 6 karakter</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Konfirmasi password baru</label>
                                <input type="password" name="confirm_password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-boba w-100 py-2">Ubah Password</button>
                            <a href="login.php" class="btn btn-outline-secondary w-100 py-2 mt-2">Batal</a>
                        </form>
                    <?php endif; ?>

                    <?php if (!$showResetForm): ?>
                        <div class="text-center mt-4">
                            <a href="login.php" class="btn btn-outline-dark btn-sm mt-2 rounded-pill">Kembali ke Login</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
