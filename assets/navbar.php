<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$role = $_SESSION['role'] ?? 'user';
$isAdmin = ($role === 'admin');

// Sidebar links (ikon pakai FontAwesome di halaman/page layout)
$userLinks = [
    ['href' => 'menu.php', 'icon' => 'fa fa-home', 'label' => 'Beranda'],
    ['href' => 'cart.php', 'icon' => 'fa fa-shopping-cart', 'label' => 'Keranjang'],
    ['href' => 'voucher.php', 'icon' => 'fa fa-ticket', 'label' => 'Voucher'],
    ['href' => 'profile.php', 'icon' => 'fa fa-user', 'label' => 'Profil'],
];

$adminLinks = [
    ['href' => 'admin/dashboard.php', 'icon' => 'fa fa-home', 'label' => 'Dashboard'],
    ['href' => 'admin/tambah_menu.php', 'icon' => 'fa fa-plus-square', 'label' => 'Tambah Menu'],
    ['href' => 'admin/voucher_admin.php', 'icon' => 'fa fa-ticket', 'label' => 'Voucher'],
    ['href' => 'admin/tambah_voucher.php', 'icon' => 'fa fa-plus-circle', 'label' => 'Tambah Voucher'],
    ['href' => 'admin/laporan_penjualan.php', 'icon' => 'fa fa-file-text-o', 'label' => 'Laporan'],

];

$logoutHref = $isAdmin ? '../logout.php' : 'logout.php';


// Perbaikan href admin jika navbar dipakai dari halaman yang ada di folder /admin
// (menghindari kemungkinan redirect menjadi admin/admin/....)
if ($isAdmin) {
    $links = array_map(function ($l) {
        $href = $l['href'];
        // Pastikan hanya satu kali prefix "admin/"
        $href = preg_replace('~^(?:\./)?admin/~i', 'admin/', $href);
        $href = preg_replace('~^admin/admin/~i', 'admin/', $href);
        // Pastikan href tidak menjadi "admin/..." ganda saat diakses dari /admin/
        // Jadi selalu menuju file yang ada di folder /admin (tanpa prefix admin lagi)
        $href = preg_replace('~^admin/(.*)$~i', '$1', $href);
        return ['href' => $href, 'icon' => $l['icon'], 'label' => $l['label']];
    }, $adminLinks);
} else {
    $links = $userLinks;
}
?>

<nav>
    <?php foreach ($links as $l): ?>
        <a href="<?= htmlspecialchars($l['href']) ?>" title="<?= htmlspecialchars($l['label']) ?>" >
            <i class="<?= htmlspecialchars($l['icon']) ?>"></i>
        </a>
    <?php endforeach; ?>

    <a href="<?= htmlspecialchars($logoutHref) ?>" title="Logout"  onclick="return confirm('Yakin mau logout?')">
        <i class="fa fa-arrow-left"></i>
    </a>
</nav>

