<?php
require_once '../../../core/config/db.php';

$chapter_id = $_GET['id'] ?? null;
if (!$chapter_id) {
    header("Location: index.php");
    exit;
}

// Lấy thông tin chapter cần sửa
$stmt = $pdo->prepare("SELECT * FROM chapter WHERE id = ?");
$stmt->execute([$chapter_id]);
$chapter = $stmt->fetch();
$stmt->closeCursor();

if (!$chapter) {
    die("Chương không tồn tại.");
}

// Lấy topic hiện tại của chapter
$stmt = $pdo->prepare("SELECT * FROM topic WHERE id = ?");
$stmt->execute([$chapter['topic_id']]);
$currentTopic = $stmt->fetch();
$stmt->closeCursor();

if (!$currentTopic) {
    die("Chủ đề của chương không tồn tại.");
}

// Lấy category hiện tại của topic
$stmt = $pdo->prepare("SELECT * FROM category WHERE id = ?");
$stmt->execute([$currentTopic['category_id']]);
$currentCategory = $stmt->fetch();
$stmt->closeCursor();

if (!$currentCategory) {
    die("Danh mục của chủ đề không tồn tại.");
}
?>

<?php include '../partials/header.php'; ?>
<main id="Main-content">
    <?php include '../partials/sidebar.php'; ?>
    <div class="container">
        <h2>Sửa chương</h2>
        <form action="update.php" method="POST">
            <input type="hidden" name="id" value="<?= htmlspecialchars($chapter['id']) ?>">

            <div class="filter-form">
                <div class="form-group filter-item">
                    <label>Danh mục:</label>
                    <p class="form-control-plaintext"><strong><?= htmlspecialchars($currentCategory['name'] ?? 'Không rõ') ?></strong></p>
                    <!-- Nếu muốn giữ giá trị category_id để gửi lên server (nếu update.php cần), có thể thêm hidden input -->
                    <input type="hidden" name="category_id" value="<?= htmlspecialchars($currentCategory['id']) ?>">
                </div>

                <div class="form-group filter-item">
                    <label>Chủ đề:</label>
                    <p class="form-control-plaintext"><strong><?= htmlspecialchars($currentTopic['name'] ?? 'Không rõ') ?></strong></p>
                    <!-- Tương tự giữ topic_id nếu cần -->
                    <input type="hidden" name="topic_id" value="<?= htmlspecialchars($currentTopic['id']) ?>">
                </div>
            </div>

            <!-- Hiển thị lỗi nếu có -->
            <?php if (isset($_GET['error']) && $_GET['error'] == 'slug_exists'): ?>
                <div class="error-message">Slug đã tồn tại. Vui lòng chọn tên khác.</div>
            <?php endif; ?>

            <div class="form-group">
                <label for="name">Tên chương:</label>
                <input type="text" name="name" id="name" class="form-control" required value="<?= htmlspecialchars($chapter['name']) ?>">
            </div>

            <div class="form-group">
                <label for="slug">Slug:</label>
                <input type="text" name="slug" id="slug" class="form-control" required value="<?= htmlspecialchars($chapter['slug']) ?>">
            </div>

            <button type="submit" class="btn btn-primary">Cập nhật</button>
        </form>
    </div>
</main>
<?php include '../partials/footer.php'; ?>
