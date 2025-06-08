<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Đặt bàn Nhà Hàng</title>
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="stylesheet" href="assets/css/responsive.css" />
</head>
<body>
<header>
        <nav class="navbar">
            <a href="index.php?page=home" class="nav-logo">2 Huynh Đệ</a>
            <ul class="nav-links">
                <li><a href="index.php?page=home">Trang Chủ</a></li>
                <li><a href="index.php?page=menu">Thực Đơn</a></li>
                <li><a href="index.php?page=reservation">Đặt Bàn</a></li>
                <li><a href="index.php?page=location">Vị Trí</a></li>
                <?php if ($isAdmin): ?>
                    <li><a href="index.php?page=dashboard">Bảng Điều Khiển</a></li>
                    <li><a href="index.php?page=admin_logout">Đăng Xuất</a></li>
                <?php else: ?>
                    <li><a href="index.php?page=admin_login">Đăng Nhập Admin</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
<main class="container">