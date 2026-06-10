<?php
session_start();
include 'config/koneksi.php';

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$user_id = (int)$_SESSION['id'];

$user = mysqli_fetch_assoc(
    mysqli_query($conn,
    "SELECT * FROM users WHERE id='$user_id'")
);

$today = date('Y-m-d');

$cekHariIni = mysqli_num_rows(
    mysqli_query($conn,
    "SELECT * FROM daily_checkin
     WHERE user_id='$user_id'
     AND tanggal='$today'")
);

function hitungStreak($conn,$user_id){

    $q = mysqli_query($conn,
    "SELECT tanggal
     FROM daily_checkin
     WHERE user_id='$user_id'
     ORDER BY tanggal DESC");

    $streak = 0;
    $tanggalCek = date('Y-m-d');

    while($row = mysqli_fetch_assoc($q)){

        if($row['tanggal'] == $tanggalCek){

            $streak++;

            $tanggalCek = date(
                'Y-m-d',
                strtotime($tanggalCek . ' -1 day')
            );

        }else{
            break;
        }
    }

    return $streak;
}

$pesan = "";

if(isset($_POST['claim']) && $cekHariIni == 0){

    $hari = date('N');

    if($hari >= 6){
        $reward = 30;
    }else{
        $reward = 10;
    }

    mysqli_query($conn,
    "INSERT INTO daily_checkin(user_id,tanggal,poin)
     VALUES('$user_id','$today','$reward')");

    mysqli_query($conn,
    "UPDATE users
     SET poin = poin + $reward
     WHERE id='$user_id'");

    $streakBaru = hitungStreak($conn,$user_id);

    if($streakBaru >= 7){

        mysqli_query($conn,
        "UPDATE users
         SET poin = poin + 70
         WHERE id='$user_id'");

        $pesan =
        "🎉 Bonus Streak 70 Poin Didapatkan!";
    }

    header("Location: checkin.php");
    exit();
}

$streak = hitungStreak($conn,$user_id);

$user = mysqli_fetch_assoc(
    mysqli_query($conn,
    "SELECT * FROM users WHERE id='$user_id'")
);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Daily Check-In</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="assets/style.css">
<link rel="stylesheet" href="assets/theme-user.css">
<link rel="stylesheet" href="assets/navbarstyle.css">

<style>

.day-box{
    height:90px;
    border-radius:15px;
    display:flex;
    flex-direction:column;
    justify-content:center;
    align-items:center;
    font-weight:bold;
    background:white;
    box-shadow:0 4px 10px rgba(0,0,0,.08);
}

.claimed{
    background:#6f4e37;
    color:white;
}

.today{
    border:3px solid gold;
}

.streak-box{
    background:#fff;
    border-radius:20px;
    padding:15px;
    box-shadow:0 4px 10px rgba(0,0,0,.08);
}

</style>

</head>
<body>



<div >

<div class="container mt-5 mb-5">

<div class="card card-modern">

<div class="user-theme-gradient p-4">

<h2>☕ Daily Check-In</h2>

<p class="mb-0">
Poin Anda :
<b><?= $user['poin']; ?></b>
</p>

</div>

<div class="p-4">

<div class="streak-box mb-4">

<h4>🔥 Streak Saat Ini</h4>

<h2><?= $streak ?> Hari</h2>

<small class="text-muted">
Capai 7 hari berturut-turut untuk bonus 70 poin
</small>

</div>

<div class="row g-3 mb-4">

<?php

$namaHari = [
"Sen",
"Sel",
"Rab",
"Kam",
"Jum",
"Sab",
"Min"
];

for($i=1;$i<=7;$i++):

$reward = ($i >= 6) ? 30 : 10;

$status = "";

if($i < date('N')){
$status = "claimed";
}

if($i == date('N')){
$status .= " today";
}

?>

<div class="col">

<div class="day-box <?= $status ?>">

<div><?= $namaHari[$i-1] ?></div>

<small><?= $reward ?> Poin</small>

</div>

</div>

<?php endfor; ?>

</div>

<?php if($cekHariIni == 0): ?>

<form class ="mb-2"method="POST">

<button
class="btn btn-boba btn-lg w-100"
name="claim">

Claim Reward Hari Ini

</button>

</form>

<?php else: ?>

<div class="alert alert-success text-center">

✅ Kamu sudah check-in hari ini

</div>

<?php endif; ?>


<button
    type="button"
    class="btn btn-boba w-100 py-2 fw-semibold"
    onclick="history.back()">
    <i class="fa fa-arrow-left"></i> Kembali
</button>

</div>

</div>

</div>

</div>


</body>
</html>