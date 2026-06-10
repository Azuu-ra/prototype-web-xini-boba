<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Xini Boba</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body{background:#f8f5f2;}
        .login-wrap{max-width:420px;}
        .login-card{border:0;border-radius:20px;box-shadow:0 10px 24px rgba(0,0,0,0.08);overflow:hidden;}
        .login-header{background:linear-gradient(135deg,#6f4e37,#5a3d2b);color:#fff;padding:24px 18px;}
        .btn-boba{background:#6f4e37;color:#fff;border:0;border-radius:14px;}
        .btn-boba:hover{background:#5a3d2b;color:#fff;transform:translateY(-1px);} 
        .btn-boba{transition:transform .15s ease, background-color .15s ease;}
        .divider{height:1px;background:rgba(0,0,0,0.06);}
        .print-report{border-radius:14px;border:1px dashed rgba(0,0,0,0.15);}
    </style>
</head>
<body>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-12 login-wrap">
            <div class="login-card">
                <div class="login-header text-center">
                    <h3 class="mb-0">Login Xini Boba</h3>
                    <div class="small opacity-75 mt-2">Masuk untuk pesan & cek poin</div>
                </div>

                <div class="p-4">
                    <form action="proses_login.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <button class="btn btn-boba w-100 py-2 fw-semibold" type="submit">
                            Login
                        </button>

                        <div class="divider my-4"></div>

                        <!-- line untuk cetak laporan (admin) -->
                        <div class="print-report p-3 text-center mt-2">
                            <div class="fw-semibold">Belum punya akun?</div>
                            <div class="small text-muted">bisa daftra di bawah ini </div>
                            <a href="register.php" class="btn btn-outline-dark btn-sm mt-2 rounded-pill">Daftar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
