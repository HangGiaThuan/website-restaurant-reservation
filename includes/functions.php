<?php
require_once 'db.php';

/**
 * Menu Items Management
 */
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
    $stmt = $pdo->prepare(
        "INSERT INTO menu_items (name, description, price, image) VALUES (?, ?, ?, ?)"
    );
    $stmt->execute([$name, $description, $price, $image]);
    return $pdo->lastInsertId();
}

function updateMenuItem($id, $name, $description, $price, $image) {
    global $pdo;
    $stmt = $pdo->prepare(
        "UPDATE menu_items SET name=?, description=?, price=?, image=? WHERE id=?"
    );
    $stmt->execute([$name, $description, $price, $image, $id]);
}

function deleteMenuItem($id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM menu_items WHERE id=?");
    $stmt->execute([$id]);
}

/**
 * Reservations Management
 */
function addReservation($customer_name, $phone, $date, $time, $guests, $email) {
    global $pdo;
    $stmt = $pdo->prepare(
        "INSERT INTO reservations (customer_name, phone, reservation_date, reservation_time, guests, email) VALUES (?, ?, ?, ?, ?, ?)"
    );
    $stmt->execute([$customer_name, $phone, $date, $time, $guests, $email]);
    return $pdo->lastInsertId();
}

function getReservations() {
    global $pdo;
    $stmt = $pdo->query(
        "SELECT * FROM reservations ORDER BY reservation_date DESC, reservation_time DESC"
    );
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function updateReservationStatus($id, $status) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE reservations SET status=? WHERE id=?");
    $stmt->execute([$status, $id]);
}

/**
 * Admin User Management
 */
function getAdminByUsername($username) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM admins WHERE username=?");
    $stmt->execute([$username]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function addAdminUser($username, $password_hash, $role = 'staff') {
    global $pdo;
    $stmt = $pdo->prepare(
        "INSERT INTO admins (username, password_hash, role) VALUES (?, ?, ?)"
    );
    return $stmt->execute([$username, $password_hash, $role]);
}

function updateAdminRole($username, $role) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE admins SET role=? WHERE username=?");
    return $stmt->execute([$role, $username]);
}

/**
 * Session and Access Control
 */
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

/**
 * Require admin login and role check (manager or staff) for reservation manager pages
 */
function requireReservationManagerAccess() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isAdminLoggedIn()) {
        header("Location: index.php?page=admin_login");
        exit();
    }

    $role = $_SESSION['admin_role'] ?? '';
    if (!in_array($role, ['manager', 'staff'], true)) {
        echo "<p>Bạn không có quyền truy cập trang này.</p>";
        exit();
    }
}

/**
 * Render site header and footer
 */
function renderHeader() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    include __DIR__ . '/../templates/header.php';
}

function renderFooter() {
    include __DIR__ . '/../templates/footer.php';
}
?>
