<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if admin is logged in for navigation display
$isAdmin = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Đặt bàn Nhà Hàng</title>
    <!-- Core Styles -->
    <link rel="stylesheet" href="assets/css/base.css" />
    <link rel="stylesheet" href="assets/css/layout.css" />
    <link rel="stylesheet" href="assets/css/components.css" />
    <link rel="stylesheet" href="assets/css/pages.css" />
    <link rel="stylesheet" href="assets/css/responsive.css" />
</head>
<body>
<header class="header" role="banner">
    <nav class="navbar" role="navigation" aria-label="Primary Navigation">
        <a href="index.php?page=home" class="nav-logo" aria-label="Trang chủ Nhà hàng 2 Huynh Đệ">2 Huynh Đệ</a>
        <ul class="nav-links" role="menubar">
            <li role="none"><a href="index.php?page=home" role="menuitem">Trang Chủ</a></li>
            <li role="none"><a href="index.php?page=menu" role="menuitem">Thực Đơn</a></li>
            <li role="none"><a href="index.php?page=reservation" role="menuitem">Đặt Bàn</a></li>
            <li role="none"><a href="index.php?page=location" role="menuitem">Vị Trí</a></li>
            <?php if ($isAdmin): ?>
                <li role="none"><a href="index.php?page=dashboard" role="menuitem">Bảng Điều Khiển</a></li>
                <li role="none"><a href="index.php?page=admin_logout" role="menuitem">Đăng Xuất</a></li>
            <?php else: ?>
                <li role="none"><a href="index.php?page=admin_login" role="menuitem">Đăng Nhập Admin</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>
<main class="site-main" style="max-width:1200px; margin: 2rem auto; padding: 0 1rem;">
