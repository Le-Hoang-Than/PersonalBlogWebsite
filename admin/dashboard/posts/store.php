<?php
require_once '../../../core/config/db.php';
require_once '../../../core/helpers/slugify.php';

$title = $_POST['title'] ?? '';
$slug = $_POST['slug'] ?? slugify($title);
$contentJson = $_POST['content'] ?? '';
$category_id = $_POST['category_id'] ?? null;
$topic_id = $_POST['topic_id'] ?? null;
$chapter_id = $_POST['chapter_id'] ?? null;
$admin_id = 1; // Bạn có thể lấy từ session hoặc khác tùy logic

// Kiểm tra tiêu đề và nội dung không được để trống
if (empty(trim($title))) {
    die('Tiêu đề bài viết không được để trống.');
}
if (empty(trim($contentJson))) {
    die('Nội dung bài viết không được để trống.');
}

// Bắt buộc phải chọn ít nhất một cấp (category/topic/chapter)
if (!$category_id && !$topic_id && !$chapter_id) {
    die('Bài viết phải thuộc ít nhất một category, topic hoặc chapter.');
}

$categorySlug = '';
$topicSlug = '';
$chapterSlug = '';

// Lấy slug theo phân cấp
try {
    if ($chapter_id) {
        // Lấy chapter info (slug, topic_id)
        $stmt = $pdo->prepare("SELECT slug, topic_id FROM chapter WHERE id = ?");
        $stmt->execute([$chapter_id]);
        $chapter = $stmt->fetch();
        if (!$chapter) die('Không tìm thấy chapter.');

        $chapterSlug = $chapter['slug'];
        $topic_id = $chapter['topic_id'];

        // Lấy topic info (slug, category_id)
        $stmt = $pdo->prepare("SELECT slug, category_id FROM topic WHERE id = ?");
        $stmt->execute([$topic_id]);
        $topic = $stmt->fetch();
        if (!$topic) die('Không tìm thấy topic.');

        $topicSlug = $topic['slug'];
        $category_id = $topic['category_id'];

        // Lấy category slug
        $stmt = $pdo->prepare("SELECT slug FROM category WHERE id = ?");
        $stmt->execute([$category_id]);
        $category = $stmt->fetch();
        if (!$category) die('Không tìm thấy category.');

        $categorySlug = $category['slug'];

    } elseif ($topic_id) {
        // Lấy topic info (slug, category_id)
        $stmt = $pdo->prepare("SELECT slug, category_id FROM topic WHERE id = ?");
        $stmt->execute([$topic_id]);
        $topic = $stmt->fetch();
        if (!$topic) die('Không tìm thấy topic.');

        $topicSlug = $topic['slug'];
        $category_id = $topic['category_id'];

        // Lấy category slug
        $stmt = $pdo->prepare("SELECT slug FROM category WHERE id = ?");
        $stmt->execute([$category_id]);
        $category = $stmt->fetch();
        if (!$category) die('Không tìm thấy category.');

        $categorySlug = $category['slug'];

    } elseif ($category_id) {
        // Lấy category slug
        $stmt = $pdo->prepare("SELECT slug FROM category WHERE id = ?");
        $stmt->execute([$category_id]);
        $category = $stmt->fetch();
        if (!$category) die('Không tìm thấy category.');

        $categorySlug = $category['slug'];
    }
} catch (PDOException $e) {
    die('Lỗi truy vấn cơ sở dữ liệu: ' . $e->getMessage());
}

// Kiểm tra slug bài viết đã tồn tại chưa
$stmt = $pdo->prepare("SELECT COUNT(*) as count FROM post WHERE slug = ?");
$stmt->execute([$slug]);
$check = $stmt->fetch();
if ($check['count'] > 0) {
    die('Slug bài viết đã tồn tại. Vui lòng chọn tiêu đề khác.');
}
$stmt->closeCursor();

// Insert bài viết vào DB
$stmt = $pdo->prepare("
    INSERT INTO post (title, slug, content_json, category_id, topic_id, chapter_id, admin_id)
    VALUES (:title, :slug, :content_json, :category_id, :topic_id, :chapter_id, :admin_id)
");
$stmt->execute([
    ':title' => $title,
    ':slug' => $slug,
    ':content_json' => $contentJson,
    ':category_id' => $category_id,
    ':topic_id' => $topic_id,
    ':chapter_id' => $chapter_id,
    ':admin_id' => $admin_id
]);

// Tạo đường dẫn phân cấp
$path = "../../../blog";
if ($categorySlug) $path .= "/$categorySlug";
if ($topicSlug) $path .= "/$topicSlug";
if ($chapterSlug) $path .= "/$chapterSlug";
$path .= "/$slug";

// Tạo thư mục nếu chưa tồn tại
if (!file_exists($path)) {
    if (!mkdir($path, 0755, true)) {
        die("Không thể tạo thư mục cho bài viết: $path");
    }
}

// Copy template bài viết vào thư mục mới
$templatePath = __DIR__ . '/../../../core/template/template.html';
$destination = "$path/index.html";
if (!file_exists($templatePath)) {
    die("File template không tồn tại: $templatePath");
}
if (!copy($templatePath, $destination)) {
    die("Không thể sao chép template đến $destination.");
}

// Sau khi lưu thành công, chuyển hướng về trang danh sách bài viết
header("Location: index.php");
exit;
