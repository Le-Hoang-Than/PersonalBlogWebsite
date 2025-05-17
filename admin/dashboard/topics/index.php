<?php
include '../partials/header.php';
require_once '../../../core/config/db.php';

// Lấy tất cả danh mục để hiển thị trong dropdown
$stmt = $pdo->prepare("CALL get_all_categories()");
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt->closeCursor();

// Xác định category_id được chọn (mặc định là cái đầu tiên nếu chưa chọn gì)
$selectedCategoryId = isset($_GET['category_id']) ? (int) $_GET['category_id'] : ($categories[0]['id'] ?? 0);
$topics = [];

if ($selectedCategoryId > 0) {
    // Gọi SP để lấy danh sách topic theo category
    $stmt = $pdo->prepare("CALL get_topics_by_category_id(:cat_id)");
    $stmt->bindValue(':cat_id', $selectedCategoryId, PDO::PARAM_INT);
    $stmt->execute();
    $topics = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
}
?>

<main id="Main-content">
    <?php include '../partials/sidebar.php'; ?>

    <div class="container">
        <h2>Quản lý chủ đề (Topic)</h2>

        <!-- Dropdown chọn danh mục -->
        <form method="GET" action="">
            <label for="category_id">Chọn danh mục:</label>
            <select name="category_id" id="category_id" class="form-control" onchange="this.form.submit()">
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $selectedCategoryId ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>

        <!-- Nút thêm mới -->
        <a href="create.php?category_id=<?= $selectedCategoryId ?>" class="btn btn-primary">+ Thêm topic</a>

        <!-- Bảng danh sách topic -->

        <table class="category-table">
            <thead>
                <tr>
                    <th>Tên chủ đề</th>
                    <th>Slug</th>
                    <th>Hiển thị</th>
                    <th>Ngày tạo</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($topics)): ?>

                    <?php foreach ($topics as $topic): ?>
                        <tr>
                            <td><?= htmlspecialchars($topic['name']) ?></td>
                            <td><?= htmlspecialchars($topic['slug']) ?></td>
                            <td><?= $topic['is_visible'] ? '✔' : '✖' ?></td>
                            <td><?= htmlspecialchars($topic['created_at']) ?></td>
                            <td>
                                <a href="edit.php?id=<?= $topic['id'] ?>" class="btn btn-sm">Sửa</a>
                                <a href="delete.php?id=<?= $topic['id'] ?>" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Bạn có chắc chắn muốn xóa topic này?');">Xóa</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">Không có topic nào trong danh mục này.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<?php include '../partials/footer.php'; ?>