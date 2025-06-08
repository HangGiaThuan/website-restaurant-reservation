<h1>Thực đơn</h1>
<div class="menu-list">
    <?php
    $menuItems = getMenuItems();
    if ($menuItems): 
        foreach ($menuItems as $item): ?>
            <div class="menu-item">
                <?php if ($item['image'] && file_exists("assets/images/" . $item['image'])): ?>
                    <img src="assets/images/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" />
                <?php else: ?>
                    <img src="assets/images/pho.jpg" alt="Menu Image" />
                <?php endif; ?>
                <div class="menu-info">
                    <h3><?= htmlspecialchars($item['name']) ?></h3>
                    <p><?= htmlspecialchars($item['description']) ?></p>
                    <p class="price"><?= number_format($item['price'], 0, ',', '.') ?> đ</p>
                </div>
            </div>
        <?php endforeach; 
    else: ?>
        <p>Không có món ăn nào trong thực đơn.</p>
    <?php endif; ?>
</div>
