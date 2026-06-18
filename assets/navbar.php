<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$role = $_SESSION['role'] ?? null;
$isAdmin = ($role === 'admin');
$isLogged = isset($_SESSION['id']);

// Links for different user states
$guestLinks = [
    ['href' => 'menu.php', 'icon' => 'fa fa-home', 'label' => 'Beranda'],
    ['href' => 'voucher.php', 'icon' => 'fa fa-tag', 'label' => 'Voucher'],
    ['href' => 'login.php', 'icon' => 'fa fa-sign-in', 'label' => 'Masuk'],
    ['href' => 'register.php', 'icon' => 'fa fa-user-plus', 'label' => 'Daftar'],
];

$userLinks = [
    ['href' => 'menu.php', 'icon' => 'fa fa-home', 'label' => 'Beranda'],
    ['href' => 'cart.php', 'icon' => 'fa fa-shopping-cart', 'label' => 'Keranjang'],
    ['href' => 'My_orders.php', 'icon' => 'fa fa-shopping-bag', 'label' => 'Orderanku'],
    ['href' => 'voucher.php', 'icon' => 'fa fa-tag', 'label' => 'Voucher'],
    ['href' => 'profile.php', 'icon' => 'fa fa-user', 'label' => 'Profil'],
];

$adminLinks = [
    ['href' => 'admin/dashboard.php', 'icon' => 'fa fa-home', 'label' => 'Dashboard'],
    ['href' => 'admin/daftar_menu.php', 'icon' => 'fa fa-cutlery', 'label' => 'Menu'],
    ['href' => 'admin/admin_order.php', 'icon' => 'fa fa-list', 'label' => 'Order'],
    ['href' => 'admin/voucher_admin.php', 'icon' => 'fa fa-ticket', 'label' => 'Voucher'],
    ['href' => 'admin/transaksi.php', 'icon' => 'fa fa-bar-chart', 'label' => 'Laporan'],
];

// Choose links based on auth/role
if ($isAdmin) {
    $links = array_map(function ($l) {
        $href = $l['href'];
        $href = preg_replace('~^(?:\./)?admin/~i', 'admin/', $href);
        $href = preg_replace('~^admin/admin/~i', 'admin/', $href);
        $href = preg_replace('~^admin/(.*)$~i', '$1', $href);
        return ['href' => $href, 'icon' => $l['icon'], 'label' => $l['label']];
    }, $adminLinks);
} elseif ($isLogged) {
    $links = $userLinks;
} else {
    $links = $guestLinks;
}

$showLogout = $isLogged || $isAdmin;
?>

<nav>
    <?php foreach ($links as $l): ?>
        <a href="<?= htmlspecialchars($l['href']) ?>" title="<?= htmlspecialchars($l['label']) ?>" >
            <i class="<?= htmlspecialchars($l['icon']) ?>"></i>
        </a>
    <?php endforeach; ?>

    <?php if ($showLogout): ?>
        <button type="button" class="logout-button" title="Logout" onclick="document.getElementById('logoutModal').classList.add('open'); document.getElementById('logoutBackdrop').classList.add('open');">
            <i class="fa fa-arrow-left"></i>
        </button>
    <?php endif; ?>
</nav>

<div id="logoutBackdrop" class="logout-modal-backdrop"></div>
<div id="logoutModal" class="logout-modal" role="dialog" aria-modal="true" aria-labelledby="logoutModalLabel">
    <div class="logout-modal-content">
        <div class="logout-modal-header">
            <h5 class="logout-modal-title" id="logoutModalLabel">Yakin mau keluar?</h5>
        </div>
        <div class="logout-modal-body">
            <p>Tekan <strong>Keluar</strong> untuk mengakhiri sesi, atau <strong>Batal</strong> untuk tetap berada di halaman ini.</p>
        </div>
        <div class="logout-modal-footer">
            <button type="button" class="btn-modal-cancel" onclick="document.getElementById('logoutModal').classList.remove('open'); document.getElementById('logoutBackdrop').classList.remove('open');">Batal</button>
            <a href="<?= $isAdmin ? '../logout.php' : 'logout.php' ?>" class="btn-modal-confirm">Keluar</a>
        </div>
    </div>
</div>

