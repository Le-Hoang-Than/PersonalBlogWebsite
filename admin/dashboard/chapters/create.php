<?php
require_once '../../../core/config/db.php';

// Lấy category_id và topic_id từ URL nếu có
$category_id = $_GET['category_id'] ?? null;
$topic_id = $_GET['topic_id'] ?? null;

// Lấy danh sách category
$stmt = $pdo->prepare("CALL get_all_categories()");
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt->closeCursor();

// Nếu có category_id, lấy danh sách topic theo category
$topics = [];
if ($category_id) {
    $stmt = $pdo->prepare("CALL get_topics_by_category_id(:cat_id)");
    $stmt->execute(['cat_id' => $category_id]);
    $topics = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
}

// Nếu chưa có topic_id nhưng đã có topics => tự động chọn topic đầu tiên
if (!$topic_id && count($topics) > 0) {
    $topic_id = $topics[0]['id'];
}
?>

<?php include '../partials/header.php'; ?>
<main id="Main-content">
    <?php include '../partials/sidebar.php'; ?>
    <div class="container">
        <h2>Thêm chương mới</h2>
        <form action="store.php" method="POST">
            <div class="filter-form">

                <div class="form-group filter-item">
                    <label for="category">Danh mục:</label>
                    <select name="category_id" id="category" class="form-control" required>
                        <option value="">-- Chọn danh mục --</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= ($cat['id'] == $category_id ? 'selected' : '') ?>>
                                <?= htmlspecialchars($cat['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group filter-item">
                    <label for="topic">Chủ đề:</label>
                    <select name="topic_id" id="topic" class="form-control" required>
                        <option value="">-- Chọn chủ đề --</option>
                        <?php foreach ($topics as $topic): ?>
                            <option value="<?= $topic['id'] ?>" <?= ($topic['id'] == $topic_id ? 'selected' : '') ?>>
                                <?= htmlspecialchars($topic['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <!-- Hiển thị lỗi nếu có -->
            <?php if (isset($_GET['error']) && $_GET['error'] == 'slug_exists'): ?>
                <div class="error-message">Slug đã tồn tại. Vui lòng chọn tên khác.</div>
            <?php endif; ?>

            <div class="form-group">
                <label for="name">Tên chương:</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="slug">Slug:</label>
                <input type="text" name="slug" id="slug" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Lưu</button>
        </form>
    </div>
</main>
<?php include '../partials/footer.php'; ?>

<script>
    // Khi thay đổi category -> tải lại topic bằng JS (nếu cần)
    document.getElementById('category').addEventListener('change', function () {
        const categoryId = this.value;
        const topicSelect = document.getElementById('topic');
        topicSelect.innerHTML = '<option>Đang tải...</option>';

        if (categoryId) {
            fetch('get_topics.php?category_id=' + categoryId)
                .then(response => response.json())
                .then(data => {
                    topicSelect.innerHTML = '<option value="">-- Chọn chủ đề --</option>';
                    data.forEach(topic => {
                        const option = document.createElement('option');
                        option.value = topic.id;
                        option.textContent = topic.name;
                        topicSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    topicSelect.innerHTML = '<option value="">-- Lỗi tải chủ đề --</option>';
                    console.error(error);
                });
        } else {
            topicSelect.innerHTML = '<option value="">-- Chọn chủ đề --</option>';
        }
    });
</script>