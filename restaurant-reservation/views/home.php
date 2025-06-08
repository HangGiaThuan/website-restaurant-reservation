<div class="home-intro">
    <h1>Nhà Hàng 2 Huynh Đệ</h1>
    <p>Thưởng thức những bữa ăn ngon và đặt bàn dễ dàng.</p>
    <a href="index.php?page=menu" class="btn">Thực đơn</a>
    <a href="index.php?page=reservation" class="btn">Đặt bàn</a>
</div>
<div class="featured-dishes">
    <h2>Món ăn nổi bật</h2>
    <div class="dish-list">
        <?php
        $menuItems = getMenuItems();
        $count = 0;
        foreach ($menuItems as $item) {
            if ($count >= 3) break;
            ?>
            <div class="dish">
                <?php if ($item['image'] && file_exists("assets/images/" . $item['image'])): ?>
                    <img src="assets/images/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" />
                <?php else: ?>
                    <img src="assets/images/pho.jpg" alt="Dish Image" />
                <?php endif; ?>
                <h3><?= htmlspecialchars($item['name']) ?></h3>
                <p><?= htmlspecialchars($item['description']) ?></p>
                <p class="price"><?= number_format($item['price'], 0, ',', '.') ?> đ</p>
            </div>
            <?php
            $count++;
        }
        ?>
    </div>
</div>
