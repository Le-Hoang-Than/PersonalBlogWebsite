<?php
require_once '../../../core/config/db.php';

$name = $_POST['name'] ?? '';
$slug = $_POST['slug'] ?? '';
$topic_id = !empty($_POST['topic_id']) ? $_POST['topic_id'] : null;

if (!$topic_id) {
    header("Location: create.php?error=missing_topic");
    exit;
}

// Kiểm tra slug đã tồn tại chưa
$stmt = $pdo->prepare("CALL check_chapter_slug_exists(:slug)");
$stmt->execute(['slug' => $slug]);
$result = $stmt->fetch();
$stmt->closeCursor();

if ($result['slug_count'] > 0) {
    header("Location: create.php?topic_id=$topic_id&error=slug_exists");
    exit;
}

// Gọi procedure thêm chapter mới
$stmt = $pdo->prepare("CALL insert_chapter(:name, :slug, :topic_id)");
$stmt->execute([
    ':name' => $name,
    ':slug' => $slug,
    ':topic_id' => $topic_id
]);
$stmt->closeCursor();

// Lấy slug của topic và category cha để tạo đường dẫn vật lý, đồng thời lấy category_id để redirect
$stmt = $pdo->prepare("
    SELECT t.slug AS topic_slug, c.slug AS category_slug, c.id AS category_id
    FROM topic t 
    JOIN category c ON t.category_id = c.id 
    WHERE t.id = ?
");
$stmt->execute([$topic_id]);
$pathData = $stmt->fetch();
$stmt->closeCursor();

$topicSlug = $pathData['topic_slug'];
$categorySlug = $pathData['category_slug'];
$categoryId = $pathData['category_id'];
$chapterSlug = $slug;

// Tạo thư mục vật lý nếu chưa có
$path = "../../../blog/$categorySlug/$topicSlug/$chapterSlug";
if (!file_exists($path)) {
    mkdir($path, 0755, true);
    // Tùy chọn tạo file index
    // file_put_contents($path . "/index.html", "<h1>$name</h1>");
}

// Điều hướng về danh sách chapter theo topic
header("Location: index.php?topic_id=$topic_id&category_id=$categoryId");
exit;
