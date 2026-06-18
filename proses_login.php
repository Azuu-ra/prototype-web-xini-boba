<?php
session_start();
include 'config/koneksi.php';

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if ($username === '' || $password === '') {
    echo "<script>alert('Username dan password harus diisi.');window.location='login.php';</script>";
    exit();
}

$usernameEsc = mysqli_real_escape_string($conn, $username);

// cek username
$query = mysqli_query($conn,
    "SELECT * FROM users WHERE username='$usernameEsc'");

if (!$query) {
    echo "<script>alert('Terjadi kesalahan. Silakan coba lagi.');window.location='login.php';</script>";
    exit();
}

$data = mysqli_fetch_assoc($query);

// cek user ditemukan atau tidak
if($data){

    // cek password hash
    if(password_verify($password, $data['password'])){

        $_SESSION['id'] = $data['id'];
        $_SESSION['username'] = $data['username'];
        $_SESSION['role'] = $data['role'];

        if($data['role'] == 'admin'){

            header('Location: admin/dashboard.php');

        }else{

            header('Location: menu.php');

        }

    }else{

        echo "
        <script>
            alert('Password salah!');
            window.location='login.php';
        </script>
        ";

    }

}else{

    echo "
    <script>
        alert('Username tidak ditemukan!');
        window.location='login.php';
    </script>
    ";

}
?>
