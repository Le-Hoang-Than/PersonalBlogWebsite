<?php
require_once '../../../core/config/db.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: index.php?error=missing_id");
    exit;
}

// Lấy thông tin slug các cấp
$stmt = $pdo->prepare("
    SELECT p.slug as post_slug, ch.slug as chapter_slug, t.slug as topic_slug,
           c.slug as category_slug, ch.id as chapter_id, t.id as topic_id
    FROM post p
    JOIN topic t ON p.topic_id = t.id
    JOIN category c ON t.category_id = c.id
    LEFT JOIN chapter ch ON p.chapter_id = ch.id
    WHERE p.id = ?
");
$stmt->execute([$id]);
$data = $stmt->fetch();

if (!$data) {
    header("Location: index.php?error=not_found");
    exit;
}

// Xác định đường dẫn vật lý cần xóa
$base = "../../../blog/{$data['category_slug']}/{$data['topic_slug']}";
if ($data['chapter_slug']) {
    $base .= "/{$data['chapter_slug']}/{$data['post_slug']}";
} else {
    $base .= "/{$data['post_slug']}";
}

// Xóa thư mục
function deleteDirectory($dirPath) {
    if (!is_dir($dirPath)) return false;
    $files = array_diff(scandir($dirPath), ['.', '..']);
    foreach ($files as $file) {
        $fullPath = "$dirPath/$file";
        if (is_dir($fullPath)) {
            deleteDirectory($fullPath);
        } else {
            unlink($fullPath);
        }
    }
    return rmdir($dirPath);
}

deleteDirectory($base);

// Xóa trong DB
$stmt = $pdo->prepare("CALL delete_post(:id)");
$stmt->execute([':id' => $id]);

// Quay lại trang danh sách post (theo topic hoặc chapter nếu có)
$redirect = "index.php?topic_id=" . $data['topic_id'];
if ($data['chapter_id']) {
    $redirect .= "&chapter_id=" . $data['chapter_id'];
}
header("Location: $redirect");
exit;
