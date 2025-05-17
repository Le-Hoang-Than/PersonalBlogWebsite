<?php
require_once '../../../core/config/db.php';

$id = $_POST['id'];
$name = $_POST['name'];
$newSlug = $_POST['slug'];
$newParentId = $_POST['parent_category_id'] ?? null;

// Lấy thông tin cũ
$stmt = $pdo->prepare("SELECT * FROM category WHERE id = ?");
$stmt->execute([$id]);
$old = $stmt->fetch();

if (!$old) {
    die("Danh mục không tồn tại.");
}

$oldSlug = $old['slug'];
$oldParentId = $old['parent_category_id'];

// Lấy slug cha mới nếu có
$newPath = "../../../blog/";
$oldPath = "../../../blog/";

if ($newParentId) {
    $stmt2 = $pdo->prepare("SELECT slug FROM category WHERE id = ?");
    $stmt2->execute([$newParentId]);
    $parentNew = $stmt2->fetch();
    $newPath .= $parentNew['slug'] . "/";
}

if ($oldParentId) {
    $stmt3 = $pdo->prepare("SELECT slug FROM category WHERE id = ?");
    $stmt3->execute([$oldParentId]);
    $parentOld = $stmt3->fetch();
    $oldPath .= $parentOld['slug'] . "/";
}

$oldFullPath = $oldPath . $oldSlug;
$newFullPath = $newPath . $newSlug;

// Kiểm tra trùng slug mới (trừ chính nó)
$stmt4 = $pdo->prepare("SELECT COUNT(*) as count FROM category WHERE slug = ? AND id != ?");
$stmt4->execute([$newSlug, $id]);
$check = $stmt4->fetch();
if ($check['count'] > 0) {
    die("Slug đã tồn tại.");
}

// Đổi tên thư mục nếu đường dẫn thay đổi
if ($oldFullPath !== $newFullPath && file_exists($oldFullPath)) {
    rename($oldFullPath, $newFullPath);
}

// Cập nhật dữ liệu trong DB
$stmt = $pdo->prepare("UPDATE category SET name = :name, slug = :slug, parent_category_id = :parent_id WHERE id = :id");
$stmt->execute([
    ':name' => $name,
    ':slug' => $newSlug,
    ':parent_id' => $newParentId ?: null,
    ':id' => $id
]);

header("Location: index.php");
exit;
