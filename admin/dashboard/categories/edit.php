<?php
require_once '../../../core/config/db.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    die("Thiếu ID danh mục.");
}

$stmt = $pdo->prepare("SELECT * FROM category WHERE id = ?");
$stmt->execute([$id]);
$category = $stmt->fetch();

if (!$category) {
    die("Không tìm thấy danh mục.");
}

// Lấy danh sách category để chọn làm cha
$cats = $pdo->query("SELECT id, name FROM category WHERE id != $id")->fetchAll();
?>
<?php include '../partials/header.php'; ?>

<main id="Main-content">
    <?php include '../partials/sidebar.php'; ?>
    <div class="container">
        <a href="index.php" class="btn">Quay lại danh sách</a>

        <h2>Sửa danh mục</h2>
        <form action="update.php" method="post">
            <input type="hidden" name="id" value="<?= $category['id'] ?>">
            <div class="form-group">
                <label for="name">Tên danh mục:</label>
                <input class="form-control" type="text" name="name" value="<?= htmlspecialchars($category['name']) ?>"
                    required><br>
            </div>

            <div class="form-group">
                <label for="slug">Slug:</label>
                <input class="form-control" type="text" name="slug" value="<?= htmlspecialchars($category['slug']) ?>"
                    required><br>
            </div>

            <div class="form-group">
                <label for="parent_category_id">Danh mục cha:</label>
                <select name="parent_category_id" class="form-control">
                    <option value="">--Không có--</option>
                    <?php foreach ($cats as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= $category['parent_category_id'] == $cat['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button class="btn btn-primary" type="submit">Cập nhật</button>
        </form>
    </div>
</main>