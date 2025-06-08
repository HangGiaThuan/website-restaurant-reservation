<?php
// Router chính
session_start();

$page = $_GET['page'] ?? 'home';

// Danh sách trang cho phép, tránh lỗi include file không mong muốn
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

if (!in_array($page, $allowed_pages)) {
    $page = 'home';
}

// Xử lý chức năng đăng xuất admin
if ($page === 'admin_logout') {
    session_destroy();
    header("Location: index.php?page=admin_login");
    exit();
}

require_once "includes/functions.php";

$isAdminPage = in_array($page, ['dashboard', 'manage_menu', 'manage_reservations', 'admin_register']);

// Kiểm tra quyền truy cập trang admin
if ($isAdminPage) {
    if (!isAdminLoggedIn()) {
        header("Location: index.php?page=admin_login");
        exit();
    }
    // Phân quyền với trang đăng ký admin (chỉ manager được vào)
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
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="stylesheet" href="assets/css/responsive.css" />
</head>
<body>
<?php
renderHeader();

include "views/{$page}.php";

renderFooter();
?>
</body>
</html>
