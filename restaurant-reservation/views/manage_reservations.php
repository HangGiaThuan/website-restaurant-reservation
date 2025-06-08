<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/restaurant-reservation/includes/functions.php';
requireAdminLogin();

$role = $_SESSION['admin_role'] ?? '';

$message = '';
if (isset($_GET['action'], $_GET['id'])) {
    $action = $_GET['action'];
    $id = intval($_GET['id']);

    if ($action === 'approve' || $action === 'cancel') {
        $status = $action === 'approve' ? 'approved' : 'cancelled';
        updateReservationStatus($id, $status);
        $message = "Đặt bàn #$id đã được đánh dấu là $status.";
    } elseif ($action === 'delete') {
        // Check reservation status before delete
        $reservations = getReservations();
        $foundReservation = null;
        foreach ($reservations as $r) {
            if ($r['id'] === $id) {
                $foundReservation = $r;
                break;
            }
        }
        if ($foundReservation) {
            if ($foundReservation['status'] === 'pending') {
                $message = "Không thể xóa đặt bàn #$id vì trạng thái chưa được duyệt.";
            } else {
                global $pdo;
                $stmt = $pdo->prepare("DELETE FROM reservations WHERE id = ?");
                $stmt->execute([$id]);
                $message = "Đặt bàn #$id đã được xóa.";
            }
        } else {
            $message = "Đặt bàn #$id không tồn tại.";
        }
    }
}

$reservations = getReservations();
?>

<h1>Thông tin đặt bàn</h1>
<?php if ($message): ?>
    <div class="success-message"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>
<table class="reservations-table">
    <thead>
        <tr>
            <th>ID</th><th>Tên Khách</th><th>Số Điện Thoại</th><th>Email</th><th>Ngày</th><th>Giờ</th><th>Số Khách</th><th>Trạng Thái</th><th>Hành Động</th><th>Xóa</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($reservations as $r): ?>
            <tr>
                <td><?= $r['id'] ?></td>
                <td><?= htmlspecialchars($r['customer_name']) ?></td>
                <td><?= htmlspecialchars($r['phone']) ?></td>
                <td><?= htmlspecialchars($r['email']) ?></td>
                <td><?= htmlspecialchars($r['reservation_date']) ?></td>
                <td><?= htmlspecialchars(substr($r['reservation_time'], 0, 5)) ?></td>
                <td><?= $r['guests'] ?></td>
                <td><?= ucfirst($r['status']) ?></td>
                <td>
                    <?php if ($r['status'] === 'pending'): ?>
                        <a href="index.php?page=manage_reservations&action=approve&id=<?= $r['id'] ?>" class="btn btn-approve">Duyệt</a>
                        <a href="index.php?page=manage_reservations&action=cancel&id=<?= $r['id'] ?>" class="btn btn-cancel">Hủy</a>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($r['status'] !== 'pending'): ?>
                        <a href="index.php?page=manage_reservations&action=delete&id=<?= $r['id'] ?>" class="btn btn-delete" onclick="return confirm('Bạn có chắc chắn muốn xóa đặt bàn này?')">Xóa</a>
                    <?php else: ?>
                        <span title="Không thể xóa đặt bàn chưa được duyệt" style="color:#999; cursor: not-allowed;">Xóa</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

