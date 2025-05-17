<?php
require_once '../../../core/config/db.php';

$name = $_POST['name'] ?? '';
$slug = $_POST['slug'] ?? '';
$category_id = !empty($_POST['category_id']) ? $_POST['category_id'] : null;

// Kiểm tra slug đã tồn tại chưa
$stmt = $pdo->prepare("CALL check_topic_slug_exists(:slug)");
$stmt->execute(['slug' => $slug]);
$result = $stmt->fetch();
$stmt->closeCursor();

if ($result['slug_count'] > 0) {
    header("Location: create.php?error=slug_exists");
    exit;
}

// Gọi procedure thêm topic mới
$stmt = $pdo->prepare("CALL insert_topic(:name, :slug, :category_id)");
$stmt->execute([
    ':name' => $name,
    ':slug' => $slug,
    ':category_id' => $category_id
]);
$stmt->closeCursor();

// Lấy slug của category cha từ DB
$stmt = $pdo->prepare("SELECT slug FROM category WHERE id = ?");
$stmt->execute([$category_id]);
$category = $stmt->fetch();
$stmt->closeCursor();

$categorySlug = $category['slug'] ?? 'unknown';
$topicSlug = $slug;

// Tạo thư mục vật lý cho topic nếu chưa có
$path = "../../../blog/$categorySlug/$topicSlug";
if (!file_exists($path)) {
    mkdir($path, 0755, true);

    // Có thể tạo file index.html
    // file_put_contents($path . "/index.html", "<h1>$name</h1>");
}

header("Location: index.php?category_id=$category_id");
exit;
