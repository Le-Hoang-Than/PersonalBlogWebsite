<?php
require_once '../../../core/config/db.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    die("Thiếu ID chủ đề.");
}

// Lấy thông tin topic
$stmt = $pdo->prepare("SELECT * FROM topic WHERE id = ?");
$stmt->execute([$id]);
$topic = $stmt->fetch();

if (!$topic) {
    die("Không tìm thấy chủ đề.");
}

// Lấy danh sách các danh mục (parent category) để gán lại cho topic
$stmt = $pdo->query("CALL get_parent_categories()");
$categories = $stmt->fetchAll();
$stmt->closeCursor();
?>

<?php include '../partials/header.php'; ?>

<main id="Main-content">
    <?php include '../partials/sidebar.php'; ?>
    <div class="container">
        <a href="index.php" class="btn">Quay lại danh sách</a>

        <h2>Sửa chủ đề</h2>
        <form action="update.php" method="post">
            <input type="hidden" name="id" value="<?= $topic['id'] ?>">

            <div class="form-group">
                <label for="name">Tên chủ đề:</label>
                <input class="form-control" type="text" name="name" value="<?= htmlspecialchars($topic['name']) ?>" required>
            </div>

            <div class="form-group">
                <label for="slug">Slug:</label>
                <input class="form-control" type="text" name="slug" value="<?= htmlspecialchars($topic['slug']) ?>" required>
            </div>

            <div class="form-group">
                <label for="category_id">Danh mục:</label>
                <select name="category_id" class="form-control" required>
                    <option value="">-- Chọn danh mục --</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= $topic['category_id'] == $cat['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button class="btn btn-primary" type="submit">Cập nhật</button>
        </form>
    </div>
</main>

<?php include '../partials/footer.php'; ?>
