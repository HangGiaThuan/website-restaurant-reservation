<?php
// Kiểm tra và khởi động session nếu chưa có
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username && $password) {
        // Sử dụng đường dẫn tuyệt đối
        require_once $_SERVER['DOCUMENT_ROOT'] . '/restaurant-reservation/includes/functions.php'; 

        $admin = getAdminByUsername($username);
        if ($admin) {
            // Kiểm tra hash mật khẩu
            if (hash('sha256', $password) === $admin['password_hash']) {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_username'] = $username;
                $_SESSION['admin_role'] = $admin['role'];
                header('Location: index.php?page=dashboard');
                exit();
            }
        }
        $error = 'Tên đăng nhập hoặc mật khẩu không hợp lệ.';
    } else {
        $error = 'Vui lòng nhập tên đăng nhập và mật khẩu.';
    }
}
?>
<h1>Đăng Nhập Quản Trị</h1>
<?php if ($error): ?>
    <div class="error-message"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>
<form action="index.php?page=admin_login" method="post" class="login-form" style="max-width: 400px; margin: auto;">
    <label for="username">Tên đăng nhập:</label>
    <input type="text" name="username" id="username" required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" />

    <label for="password">Mật khẩu:</label>
    <input type="password" name="password" id="password" required />

    <div style="display: flex; gap: 1rem; margin-top: 1rem;">
        <button type="submit" class="btn" style="flex: 1;">Đăng Nhập</button>
    </div>
</form>

