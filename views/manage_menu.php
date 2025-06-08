<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/restaurant-reservation/includes/functions.php'; 
requireAdminLogin();
$role = $_SESSION['admin_role'] ?? '';

if ($role !== 'manager') {
    echo "<p>Bạn không có quyền truy cập trang này.</p>";
    exit();
}

// Phần code quản lý thực đơn như hiện tại (giữ nguyên)
$message = '';
$error = '';

$action = $_GET['action'] ?? '';
$id = intval($_GET['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    $currentImage = $_POST['current_image'] ?? '';

    // Xử lý upload ảnh
    $imageName = $currentImage;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'assets/images/';
        $tmpName = $_FILES['image']['tmp_name'];
        $imageName = basename($_FILES['image']['name']);
        $targetFile = $uploadDir . $imageName;
        move_uploaded_file($tmpName, $targetFile);
    }

    if (!$name || $price <= 0) {
        $error = 'Tên và giá phải hợp lệ.';
    } else {
        if ($action === 'edit' && $id > 0) {
            updateMenuItem($id, $name, $description, $price, $imageName);
            $message = 'Cập nhật món ăn thành công.';
        } else {
            addMenuItem($name, $description, $price, $imageName);
            $message = 'Thêm món ăn thành công.';
        }
        $action = '';
        $id = 0;
    }
}

if ($action === 'delete' && $id > 0) {
    deleteMenuItem($id);
    $message = 'Xóa món ăn thành công.';
    $action = '';
    $id = 0;
}

$menuItems = getMenuItems();

$itemToEdit = null;
if ($action === 'edit' && $id > 0) {
    $itemToEdit = getMenuItem($id);
}
?>

<h1>Thông tin thực đơn</h1>

<?php if ($message): ?>
    <div class="success-message"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>
<?php if ($error): ?>
    <div class="error-message"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<?php if ($action === 'edit' && $itemToEdit): ?>
    <h2>Chỉnh sửa món ăn</h2>
    <form action="index.php?page=manage_menu&action=edit&id=<?= $itemToEdit['id'] ?>" method="post" enctype="multipart/form-data" class="manage-menu-form">
        <label for="name">Tên món:</label>
        <input type="text" name="name" id="name" required value="<?= htmlspecialchars($itemToEdit['name']) ?>" />

        <label for="description">Mô tả:</label>
        <textarea name="description" id="description"><?= htmlspecialchars($itemToEdit['description']) ?></textarea>

        <label for="price">Giá:</label>
        <input type="number" step="0.01" name="price" id="price" required value="<?= htmlspecialchars($itemToEdit['price']) ?>" />

        <label for="image">Ảnh:
            <?php if ($itemToEdit['image'] && file_exists("assets/images/" . $itemToEdit['image'])): ?>
                <br /><img src="assets/images/<?= htmlspecialchars($itemToEdit['image']) ?>" alt="" width="120" />
            <?php endif; ?>
        </label>
        <input type="file" name="image" id="image" accept="image/*" />
        <input type="hidden" name="current_image" value="<?= htmlspecialchars($itemToEdit['image'] ?? '') ?>" />

        <button type="submit" class="btn">Cập nhật</button>
        <a href="index.php?page=manage_menu" class="btn btn-secondary">Hủy</a>
    </form>

<?php else: ?>
    <h2>Thêm món ăn mới</h2>
    <form action="index.php?page=manage_menu" method="post" enctype="multipart/form-data" class="manage-menu-form">
        <label for="name">Tên món:</label>
        <input type="text" name="name" id="name" required />

        <label for="description">Mô tả:</label>
        <textarea name="description" id="description"></textarea>

        <label for="price">Giá:</label>
        <input type="number" step="0.01" name="price" id="price" required />

        <label for="image">Ảnh:</label>
        <input type="file" name="image" id="image" accept="image/*" />

        <button type="submit" class="btn">Thêm</button>
    </form>
<?php endif; ?>

<h2>Danh sách món ăn</h2>
<table class="menu-table">
    <thead>
        <tr>
            <th>Ảnh</th><th>Tên</th><th>Mô tả</th><th>Giá</th><th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($menuItems as $item): ?>
            <tr>
                <td>
                    <?php if ($item['image'] && file_exists("assets/images/" . $item['image'])): ?>
                        <img src="assets/images/<?= htmlspecialchars($item['image']) ?>" alt="" width="80" />
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($item['name']) ?></td>
                <td><?= htmlspecialchars($item['description']) ?></td>
                <td><?= number_format($item['price'], 0, ',', '.') ?> đ</td>
                <td>
                    <a href="index.php?page=manage_menu&action=edit&id=<?= $item['id'] ?>" class="btn btn-edit">Sửa</a>
                    <a href="index.php?page=manage_menu&action=delete&id=<?= $item['id'] ?>" class="btn btn-delete" onclick="return confirm('Bạn có muốn xóa món này?')">Xóa</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
