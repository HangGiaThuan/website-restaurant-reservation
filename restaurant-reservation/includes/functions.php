<?php
require_once 'db.php';

function getMenuItems() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM menu_items ORDER BY id DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getMenuItem($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM menu_items WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function addMenuItem($name, $description, $price, $image) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO menu_items (name, description, price, image) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $description, $price, $image]);
    return $pdo->lastInsertId();
}

function updateMenuItem($id, $name, $description, $price, $image) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE menu_items SET name=?, description=?, price=?, image=? WHERE id=?");
    $stmt->execute([$name, $description, $price, $image, $id]);
}

function deleteMenuItem($id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM menu_items WHERE id=?");
    $stmt->execute([$id]);
}

function addReservation($customer_name, $phone, $date, $time, $guests, $email) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO reservations (customer_name, phone, reservation_date, reservation_time, guests, email) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$customer_name, $phone, $date, $time, $guests, $email]);
    return $pdo->lastInsertId();
}

function getReservations() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM reservations ORDER BY reservation_date DESC, reservation_time DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function updateReservationStatus($id, $status) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE reservations SET status=? WHERE id=?");
    $stmt->execute([$status, $id]);
}

function getAdminByUsername($username) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM admins WHERE username=?");
    $stmt->execute([$username]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function addAdminUser($username, $password_hash, $role = 'staff') {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO admins (username, password_hash, role) VALUES (?, ?, ?)");
    return $stmt->execute([$username, $password_hash, $role]);
}

function updateAdminRole($username, $role) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE admins SET role=? WHERE username=?");
    return $stmt->execute([$role, $username]);
}

function isAdminLoggedIn() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

function requireAdminLogin() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isAdminLoggedIn()) {
        header("Location: index.php?page=admin_login");
        exit();
    }
}

// Hàm mới: Yêu cầu đăng nhập admin và phân quyền cho trang quản lý đặt bàn
function requireReservationManagerAccess() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isAdminLoggedIn()) {
        header("Location: index.php?page=admin_login");
        exit();
    }
    $role = $_SESSION['admin_role'] ?? '';
    if (!in_array($role, ['manager', 'staff'])) {
        echo "<p>Bạn không có quyền truy cập trang này.</p>";
        exit();
    }
}

function renderHeader() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $isAdmin = isAdminLoggedIn();
    ?>
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
    <?php
}

function renderFooter() {
    ?>
    <footer>
        <p>&copy; <?= date('Y') ?> Đặt Bàn Nhà Hàng. Bảo lưu mọi quyền.</p>
    </footer>
    <?php
}
?>

