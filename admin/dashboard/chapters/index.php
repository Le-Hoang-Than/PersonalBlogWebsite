<?php
require_once '../../../core/config/db.php';

// Lấy danh sách danh mục (category)
$stmt = $pdo->prepare("CALL get_all_categories()");
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt->closeCursor();

$selectedCategoryId = $_GET['category_id'] ?? ($categories[0]['id'] ?? null);

// Lấy danh sách topic theo category đã chọn
$topics = [];
if ($selectedCategoryId) {
    $stmt = $pdo->prepare("CALL get_topics_by_category_id(:category_id)");
    $stmt->execute(['category_id' => $selectedCategoryId]);
    $topics = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
}

$selectedTopicId = $_GET['topic_id'] ?? ($topics[0]['id'] ?? null);

// Lấy danh sách chapter theo topic đã chọn
$chapters = [];
if ($selectedTopicId) {
    $stmt = $pdo->prepare("CALL get_chapters_by_topic(:topic_id)");
    $stmt->execute(['topic_id' => $selectedTopicId]);
    $chapters = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
}
?>

<?php include '../partials/header.php'; ?>

<main id="Main-content">
    <?php include '../partials/sidebar.php'; ?>
    <div class="container">
        <h2>Danh sách chương</h2>

        <!-- Form chọn category và topic -->
        <form method="GET" class="filter-form" id="filterForm">
            <div class="filter-item">
                <label for="category">Danh mục:</label>
                <select name="category_id" id="category" class="form-control"
                    onchange="document.getElementById('filterForm').submit()">
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $selectedCategoryId ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="filter-item">

                <label for="topic">Chủ đề:</label>
                <select name="topic_id" id="topic" class="form-control"
                    onchange="document.getElementById('filterForm').submit()">
                    <?php foreach ($topics as $topic): ?>
                        <option value="<?= $topic['id'] ?>" <?= $topic['id'] == $selectedTopicId ? 'selected' : '' ?>>
                            <?= htmlspecialchars($topic['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>

        <a href="create.php?category_id=<?= $selectedCategoryId ?>&topic_id=<?= $selectedTopicId ?>" class="btn">+ Thêm
            chương mới</a>

        <!-- Danh sách chương -->
        <table>
            <thead>
                <tr>
                    <th>Tên chương</th>
                    <th>Slug</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($chapters)): ?>

                    <?php foreach ($chapters as $ch): ?>
                        <tr>
                            <td><?= htmlspecialchars($ch['name']) ?></td>
                            <td><?= htmlspecialchars($ch['slug']) ?></td>
                            <td>
                                <a href="edit.php?id=<?= $ch['id'] ?>" class="btn btn-sm">Sửa</a>
                                <a href="delete.php?id=<?= $ch['id'] ?>" onclick="return confirm('Bạn có chắc muốn xóa?')"
                                    class="btn btn-sm btn-danger">Xóa</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">
                            Không có chương nào trong chủ đề này.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<?php include '../partials/footer.php'; ?>