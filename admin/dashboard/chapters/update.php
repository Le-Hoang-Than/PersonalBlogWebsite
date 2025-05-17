<?php
require_once '../../../core/config/db.php';

$id = $_POST['id'] ?? null;
$name = trim($_POST['name'] ?? '');
$newSlug = trim($_POST['slug'] ?? '');
$newTopicId = $_POST['topic_id'] ?? null;

if (!$id || !$name || !$newSlug || !$newTopicId) {
    // Có thể redirect về form edit với thông báo lỗi hoặc dừng
    header("Location: edit.php?id=$id&error=missing_fields");
    exit;
}

// Lấy thông tin chapter cũ
$stmt = $pdo->prepare("SELECT * FROM chapter WHERE id = ?");
$stmt->execute([$id]);
$old = $stmt->fetch();

if (!$old) {
    // Chương không tồn tại
    header("Location: index.php?error=not_found");
    exit;
}

$oldSlug = $old['slug'];
$oldTopicId = $old['topic_id'];

// Lấy slug topic và category cũ
$oldPath = "../../../blog/";
$newPath = "../../../blog/";

if ($oldTopicId) {
    $stmt2 = $pdo->prepare("
        SELECT t.slug AS topic_slug, c.slug AS category_slug 
        FROM topic t 
        JOIN category c ON t.category_id = c.id 
        WHERE t.id = ?
    ");
    $stmt2->execute([$oldTopicId]);
    $oldData = $stmt2->fetch();
    if ($oldData) {
        $oldPath .= $oldData['category_slug'] . "/" . $oldData['topic_slug'] . "/";
    }
}

if ($newTopicId) {
    $stmt3 = $pdo->prepare("
        SELECT t.slug AS topic_slug, c.slug AS category_slug 
        FROM topic t 
        JOIN category c ON t.category_id = c.id 
        WHERE t.id = ?
    ");
    $stmt3->execute([$newTopicId]);
    $newData = $stmt3->fetch();
    if ($newData) {
        $newPath .= $newData['category_slug'] . "/" . $newData['topic_slug'] . "/";
    }
}

// Kiểm tra slug trùng (trừ chính nó)
$stmt4 = $pdo->prepare("SELECT COUNT(*) as count FROM chapter WHERE slug = ? AND id != ?");
$stmt4->execute([$newSlug, $id]);
$check = $stmt4->fetch();
if ($check['count'] > 0) {
    header("Location: edit.php?id=$id&error=slug_exists");
    exit;
}

$oldFullPath = $oldPath . $oldSlug;
$newFullPath = $newPath . $newSlug;

// Đổi tên thư mục nếu đường dẫn thay đổi và thư mục cũ tồn tại
if ($oldFullPath !== $newFullPath && file_exists($oldFullPath)) {
    if (!rename($oldFullPath, $newFullPath)) {
        // Xử lý lỗi rename không thành công
        header("Location: edit.php?id=$id&error=rename_failed");
        exit;
    }
}

// Cập nhật database
$stmt = $pdo->prepare("UPDATE chapter SET name = :name, slug = :slug, topic_id = :topic_id WHERE id = :id");
$stmt->execute([
    ':name' => $name,
    ':slug' => $newSlug,
    ':topic_id' => $newTopicId,
    ':id' => $id
]);

header("Location: index.php?topic_id=$newTopicId");
exit;
