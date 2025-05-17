<?php
require_once '../../../core/config/db.php';
$selectedCategoryId = $_GET['category_id'] ?? '';  // Lấy category_id từ URL nếu có

// Gọi thủ tục lấy tất cả danh mục cha (tức là các category để gán cho topic)
$stmt = $pdo->query("CALL get_parent_categories()");
$categories = $stmt->fetchAll();
$stmt->closeCursor();
?>

<?php include '../partials/header.php'; ?>

<main id="Main-content">
    <?php include '../partials/sidebar.php'; ?>
    <div class="container">
        <a href="index.php" class="btn">Quay lại danh sách</a>
        <h2>Thêm chủ đề mới</h2>

        <!-- Hiển thị lỗi nếu có -->
        <?php if (isset($_GET['error']) && $_GET['error'] == 'slug_exists'): ?>
            <div class="error-message">Slug đã tồn tại. Vui lòng chọn tên khác.</div>
        <?php endif; ?>

        <form action="store.php" method="POST">
            <div class="form-group">
                <label for="name">Tên chủ đề:</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="slug">Slug (URL):</label>
                <input type="text" name="slug" id="slug" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="category_id">Thuộc danh mục:</label>
                <select name="category_id" class="form-control" required>
                    <option value="">-- Chọn danh mục --</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= ($cat['id'] == $selectedCategoryId) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Tạo chủ đề</button>
        </form>
    </div>
</main>

<?php include '../partials/footer.php'; ?>