<?php
// Main router and session start
session_start();

// Get page parameter from URL, default to home
$page = $_GET['page'] ?? 'home';

// Allowed pages whitelist to prevent unauthorized include
$allowed_pages = [
    'home',
    'menu',
    'reservation',
    'location',
    'admin_login',
    'admin_register',
    'dashboard',
    'manage_menu',
    'manage_reservations',
    'admin_logout'
];

// Validate page parameter
if (!in_array($page, $allowed_pages, true)) {
    $page = 'home';
}

// Handle admin logout
if ($page === 'admin_logout') {
    session_destroy();
    header("Location: index.php?page=admin_login");
    exit();
}

require_once "includes/functions.php";

$isAdminPage = in_array($page, ['dashboard', 'manage_menu', 'manage_reservations', 'admin_register'], true);

// Check admin login and authorization
if ($isAdminPage) {
    if (!isAdminLoggedIn()) {
        header("Location: index.php?page=admin_login");
        exit();
    }
    // Only manager can access admin_register page
    if ($page === 'admin_register' && ($_SESSION['admin_role'] ?? '') !== 'manager') {
        echo "<p>Bạn không có quyền truy cập trang này.</p>";
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Đặt bàn Nhà Hàng</title>
    <!-- Stylesheets -->
    <link rel="stylesheet" href="assets/css/base.css" />
    <link rel="stylesheet" href="assets/css/layout.css" />
    <link rel="stylesheet" href="assets/css/components.css" />
    <link rel="stylesheet" href="assets/css/pages.css" />
    <link rel="stylesheet" href="assets/css/responsive.css" />
</head>
<body>
    <?php
    renderHeader();

    include "pages/{$page}.php";

    renderFooter();
    ?>
</body>
</html>

