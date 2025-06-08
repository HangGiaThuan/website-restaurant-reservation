<h1>Đặt bàn</h1>
<?php
$reservationSuccess = false;
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_name = trim($_POST['customer_name']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $date = $_POST['date'] ?? '';
    $time = $_POST['time'] ?? '';
    $guests = intval($_POST['guests']);

    if (!$customer_name || !$phone || !$date || !$time || $guests <= 0) {
        $errorMessage = 'Please fill all the fields correctly.';
    } else {
        // Add reservation to database
        addReservation($customer_name, $phone, $date, $time, $guests, $email );
        $reservationSuccess = true;
    }
}

if ($reservationSuccess): ?>
    <div class="success-message">
        <p>Cảm ơn bạn, đặt chỗ của bạn đã được gửi! Chúng tôi sẽ liên hệ với bạn để xác nhận.</p>
    </div>
<?php else: ?>
    <?php if ($errorMessage): ?>
        <div class="error-message"><?= htmlspecialchars($errorMessage) ?></div>
    <?php endif; ?>
    <form action="index.php?page=reservation" method="post" class="reservation-form">
        <label for="customer_name">Tên người đặt:</label>
        <input type="text" id="customer_name" name="customer_name" required value="<?= htmlspecialchars($_POST['customer_name'] ?? '') ?>" />

        <label for="phone">Số điện thoại:</label>
        <input type="text" id="phone" name="phone" required value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>" />

        <label for="email">Email:</label>
        <input type="text" id="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" />

        <label for="date">Chọn ngày:</label>
        <input type="date" id="date" name="date" required min="<?= date('Y-m-d') ?>" value="<?= htmlspecialchars($_POST['date'] ?? '') ?>" />

        <label for="time">Chọn thời gian:</label>
        <input type="time" id="time" name="time" required value="<?= htmlspecialchars($_POST['time'] ?? '') ?>" />

        <label for="guests">Tổng số người:</label>
        <input type="number" id="guests" name="guests" min="1" required value="<?= htmlspecialchars($_POST['guests'] ?? '') ?>" />

        <button type="submit" class="btn">Gửi</button>
    </form>
<?php endif; ?>
