<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/restaurant-reservation/includes/functions.php'; 
requireAdminLogin();

if ($_SESSION['admin_role'] !== 'manager') {
    echo "<p>Bạn không có quyền truy cập trang này.</p>";
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';
    $role = $_POST['role'] ?? 'staff';

    if (!$username || !$password || !$password_confirm) {
        $error = 'Vui lòng điền đầy đủ thông tin.';
    } elseif ($password !== $password_confirm) {
        $error = 'Mật khẩu và xác nhận mật khẩu không khớp.';
    } elseif (!in_array($role, ['manager', 'staff'])) {
        $error = 'Vai trò không hợp lệ.';
    } else {
        if (getAdminByUsername($username)) {
            $error = 'Tên đăng nhập đã tồn tại.';
        } else {
            $password_hash = hash('sha256', $password);
            $result = addAdminUser($username, $password_hash, $role);
            if ($result) {
                $success = "Tạo tài khoản admin thành công cho $username với vai trò $role.";
            } else {
                $error = 'Có lỗi xảy ra khi tạo tài khoản.';
            }
        }
    }
}
?>

<h1>Tạo mã nhân viên</h1>

<?php if ($error): ?>
    <div class="error-message"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>
<?php if ($success): ?>
    <div class="success-message"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<form action="index.php?page=admin_register" method="post" class="login-form" style="max-width: 400px;">
    <label for="username">Tên đăng nhập:</label>
    <input type="text" id="username" name="username" required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" />

    <label for="password">Mật khẩu:</label>
    <input type="password" id="password" name="password" required />

    <label for="password_confirm">Xác nhận mật khẩu:</label>
    <input type="password" id="password_confirm" name="password_confirm" required />

    <label for="role">Phân cấp:</label>
    <select id="role" name="role" required>
        <option value="manager" <?= (($_POST['role'] ?? '') === 'manager') ? 'selected' : '' ?>>Quản lý</option>
        <option value="staff" <?= (($_POST['role'] ?? '') === 'staff' || !isset($_POST['role'])) ? 'selected' : '' ?>>Nhân viên</option>
    </select>

    <button type="submit" class="btn">Đăng ký</button>
</form>
