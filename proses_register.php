<?php

include 'config/koneksi.php';

$nama       = $_POST['nama'];
$username   = $_POST['username'];
$email      = $_POST['email'];
$password   = $_POST['password'];
$konfirmasi = $_POST['konfirmasi_password'];

if($password != $konfirmasi){

    echo "
    <script>
        alert('Konfirmasi password tidak sama!');
        window.location='register.php';
    </script>
    ";

    exit;
}

$password_hash = password_hash($password, PASSWORD_DEFAULT);

// pakai $conn
$cek = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");

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
    '$nama',
    '$username',
    '$email',
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