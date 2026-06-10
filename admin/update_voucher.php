<?php

include '../config/koneksi.php';

$id = (int)$_POST['voucher_id'];

$nama   = mysqli_real_escape_string($conn, $_POST['nama_voucher']);
$poin   = (int)$_POST['poin_dibutuhkan'];
$status = mysqli_real_escape_string($conn, $_POST['status_voucher']);

$query = mysqli_query($conn,

"UPDATE vouchers SET
nama_voucher='$nama',
poin_dibutuhkan='$poin',
status_voucher='$status'

WHERE id='$id'"

);

if($query){

    echo "
    <script>
        alert('Voucher berhasil diupdate!');
        window.location='voucher_admin.php';
    </script>
    ";

}else{

    echo "
    <script>
        alert('Gagal update voucher!');
        window.location='voucher_admin.php';
    </script>
    ";

}
?>