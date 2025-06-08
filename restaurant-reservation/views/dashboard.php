<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/restaurant-reservation/includes/functions.php'; 
requireAdminLogin();

$username = $_SESSION['admin_username'] ?? '';
$role = $_SESSION['admin_role'] ?? '';
?>
<h1>Bảng Điều Khiển</h1>
<p>Chào mừng, <strong><?= htmlspecialchars($username) ?></strong> (Vai trò: <em><?= htmlspecialchars($role === 'manager' ? 'Quản lý' : 'Nhân viên') ?></em>)!</p>

<ul class="admin-menu">
    <?php if ($role === 'manager'): ?>
        <li><a href="index.php?page=admin_register">Tạo mã nhân viên</a></li>
        <li><a href="index.php?page=manage_menu">Thông tin thực Đơn</a></li>
    <?php endif; ?>
    <li><a href="index.php?page=manage_reservations">Thông tin đặt bàn</a></li>
    <li><a href="index.php?page=admin_logout">Đăng Xuất</a></li>
</ul>
