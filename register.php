<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Xini Boba</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body{
            background:#f8f5f2;
        }

        .register-wrap{
            max-width:420px;
        }

        .register-card{
            border:0;
            border-radius:20px;
            box-shadow:0 10px 24px rgba(0,0,0,0.08);
            overflow:hidden;
        }

        .register-header{
            background:linear-gradient(135deg,#6f4e37,#5a3d2b);
            color:#fff;
            padding:24px 18px;
        }

        .btn-boba{
            background:#6f4e37;
            color:#fff;
            border:0;
            border-radius:14px;
            transition:transform .15s ease, background-color .15s ease;
        }

        .btn-boba:hover{
            background:#5a3d2b;
            color:#fff;
            transform:translateY(-1px);
        }

        .divider{
            height:1px;
            background:rgba(0,0,0,0.06);
        }

        .login-link{
            border-radius:14px;
            border:1px dashed rgba(0,0,0,0.15);
        }
    </style>
</head>
<body>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-12 register-wrap">

            <div class="register-card">

                <div class="register-header text-center">
                    <h3 class="mb-0">Register Xini Boba</h3>
                    <div class="small opacity-75 mt-2">
                        Buat akun untuk mulai pesan boba
                    </div>
                </div>

                <div class="p-4">

                    <form action="proses_register.php" method="POST">

                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Konfirmasi Password</label>
                            <input type="password" name="konfirmasi_password" class="form-control" required>
                        </div>

                        <button class="btn btn-boba w-100 py-2 fw-semibold" type="submit">
                            Register
                        </button>

                        <div class="divider my-4"></div>

                        <div class="login-link p-3 text-center">
                            <div class="fw-semibold">
                                Sudah punya akun?
                            </div>

                            <div class="small text-muted">
                                login sekarang di bawah ini
                            </div>

                            <a href="login.php" class="btn btn-outline-dark btn-sm mt-2 rounded-pill">
                                Login
                            </a>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>