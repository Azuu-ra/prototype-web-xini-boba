<?php

include 'config/koneksi.php';

$nama       = trim($_POST['nama'] ?? '');
$username   = trim($_POST['username'] ?? '');
$email      = trim($_POST['email'] ?? '');
$password   = $_POST['password'] ?? '';
$konfirmasi = $_POST['konfirmasi_password'] ?? '';

if ($nama === '' || $username === '' || $email === '' || $password === '' || $konfirmasi === '') {
    echo "<script>alert('Semua field harus diisi.');window.location='register.php';</script>";
    exit;
}

if ($password !== $konfirmasi){

    echo "
    <script>
        alert('Konfirmasi password tidak sama!');
        window.location='register.php';
    </script>
    ";

    exit;
}

$password_hash = password_hash($password, PASSWORD_DEFAULT);

$namaEsc = mysqli_real_escape_string($conn, $nama);
$usernameEsc = mysqli_real_escape_string($conn, $username);
$emailEsc = mysqli_real_escape_string($conn, $email);

// pakai $conn
$cek = mysqli_query($conn, "SELECT * FROM users WHERE username='$usernameEsc'");

if(mysqli_num_rows($cek) > 0){

    echo "
    <script>
        alert('Username sudah digunakan!');
        window.location='register.php';
    </script>
    ";

    exit;
}

$query = mysqli_query($conn, "INSERT INTO users
(
    nama,
    username,
    email,
    password
)

VALUES
(
    '$namaEsc',
    '$usernameEsc',
    '$emailEsc',
    '$password_hash'
)
");

if($query){

    echo "
    <script>
        alert('Register berhasil!');
        window.location='login.php';
    </script>
    ";

}else{

    echo "
    <script>
        alert('Register gagal!');
        window.location='register.php';
    </script>
    ";

}

?>