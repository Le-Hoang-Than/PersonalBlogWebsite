<?php
require_once '../../../core/config/db.php';

// Gọi thủ tục lấy danh mục cha
$stmt = $pdo->query("CALL get_parent_categories()");
$categories = $stmt->fetchAll();
$stmt->closeCursor(); // Quan trọng: giải phóng kết quả để gọi tiếp thủ tục khác
?>

<?php include '../partials/header.php'; ?>

<main id="Main-content">
    <?php include '../partials/sidebar.php'; ?>
    <div class="container">
        <a href="index.php" class="btn">Quay lại danh sách</a>
        <h2>Thêm danh mục mới</h2>

        <!-- Hiển thị lỗi nếu có -->
        <?php if (isset($_GET['error']) && $_GET['error'] == 'slug_exists'): ?>
            <div class="error-message">Slug đã tồn tại. Vui lòng chọn tên khác.</div>
        <?php endif; ?>

        <form action="store.php" method="POST">
            <div class="form-group">
                <label for="name">Tên danh mục:</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="slug">Slug (URL):</label>
                <input type="text" name="slug" id="slug" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="parent_category_id">Danh mục cha (nếu có):</label>
                <select name="parent_category_id" class="form-control">
                    <option value="">-- Không có --</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Tạo danh mục</button>
        </form>
    </div>
</main>

<?php include '../partials/footer.php'; ?>
