<?php
require_once '../../../core/config/db.php';

// Lấy danh sách category
$stmt = $pdo->prepare("CALL get_all_categories()");
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt->closeCursor();

$selectedCategoryId = $_GET['category_id'] ?? null;
if (!$selectedCategoryId && count($categories) > 0) {
    $selectedCategoryId = $categories[0]['id'];
}

$topics = [];
$selectedTopicId = $_GET['topic_id'] ?? null;
if ($selectedCategoryId) {
    $stmt = $pdo->prepare("CALL get_topics_by_category_id(:category_id)");
    $stmt->execute(['category_id' => $selectedCategoryId]);
    $topics = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();

    if (!$selectedTopicId && count($topics) > 0) {
        $selectedTopicId = $topics[0]['id'];
    }
}

$chapters = [];
$selectedChapterId = $_GET['chapter_id'] ?? null;
if ($selectedTopicId) {
    $stmt = $pdo->prepare("CALL get_chapters_by_topic(:topic_id)");
    $stmt->execute(['topic_id' => $selectedTopicId]);
    $chapters = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();

    if (!$selectedChapterId && count($chapters) > 0) {
        $selectedChapterId = $chapters[0]['id'];
    }
}

// Lấy bài viết theo phân cấp ưu tiên
$posts = [];
if ($selectedChapterId) {
    $stmt = $pdo->prepare("CALL get_posts_by_chapter_id(:chapter_id)");
    $stmt->execute(['chapter_id' => $selectedChapterId]);
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
} elseif ($selectedTopicId) {
    $stmt = $pdo->prepare("CALL get_posts_by_topic_id(:topic_id)");
    $stmt->execute(['topic_id' => $selectedTopicId]);
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
} elseif ($selectedCategoryId) {
    $stmt = $pdo->prepare("CALL get_posts_by_category(:category_id)");
    $stmt->execute(['category_id' => $selectedCategoryId]);
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
}
?>

<?php include '../partials/header.php'; ?>

<main id="Main-content">
    <?php include '../partials/sidebar.php'; ?>
    <div class="container">
        <h2>Danh sách bài viết</h2>

        <form method="GET" id="filterForm" class="filter-form">
            <div class="filter-item">
                <label for="category">Danh mục:</label>
                <select name="category_id" id="category" class="form-control" <?= count($categories) === 0 ? 'disabled' : '' ?>>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= htmlspecialchars($cat['id']) ?>" <?= $cat['id'] == $selectedCategoryId ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="filter-item">
                <label for="topic">Chủ đề:</label>
                <select name="topic_id" id="topic" class="form-control" <?= count($topics) === 0 ? 'disabled' : '' ?>>
                    <?php foreach ($topics as $topic): ?>
                        <option value="<?= htmlspecialchars($topic['id']) ?>" <?= $topic['id'] == $selectedTopicId ? 'selected' : '' ?>>
                            <?= htmlspecialchars($topic['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="filter-item">
                <label for="chapter">Chương:</label>
                <select name="chapter_id" id="chapter" class="form-control" <?= count($chapters) === 0 ? 'disabled' : '' ?>>
                    <?php foreach ($chapters as $ch): ?>
                        <option value="<?= htmlspecialchars($ch['id']) ?>" <?= $ch['id'] == $selectedChapterId ? 'selected' : '' ?>>
                            <?= htmlspecialchars($ch['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>

        <a href="create.php?category_id=<?= urlencode($selectedCategoryId) ?>&topic_id=<?= urlencode($selectedTopicId) ?>&chapter_id=<?= urlencode($selectedChapterId) ?>" class="btn">+ Thêm bài viết mới</a>

        <table>
            <thead>
                <tr>
                    <th>Tiêu đề</th>
                    <th>Slug</th>
                    <th>Loại</th>
                    <th>Ngày tạo</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($posts)): ?>
                    <?php foreach ($posts as $post): ?>
                        <tr>
                            <td><?= htmlspecialchars($post['title']) ?></td>
                            <td><?= htmlspecialchars($post['slug']) ?></td>
                            <td>
                                <?= $post['chapter_id'] ? 'Chương' : ($post['topic_id'] ? 'Chủ đề' : ($post['category_id'] ? 'Danh mục' : 'Không rõ')) ?>
                            </td>
                            <td><?= htmlspecialchars($post['created_at']) ?></td>
                            <td>
                                <a href="edit.php?id=<?= $post['id'] ?>" class="btn btn-sm">Sửa</a>
                                <a href="delete.php?id=<?= $post['id'] ?>" onclick="return confirm('Bạn có chắc muốn xóa?')" class="btn btn-sm btn-danger">Xóa</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5">Không có bài viết nào.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<script>
    const filterForm = document.getElementById('filterForm');
    document.getElementById('category').addEventListener('change', () => filterForm.submit());
    document.getElementById('topic').addEventListener('change', () => filterForm.submit());
    document.getElementById('chapter').addEventListener('change', () => filterForm.submit());
</script>

<?php include '../partials/footer.php'; ?>
