<?php
require_once '../../../core/config/db.php';

$id = $_POST['id'];
$name = $_POST['name'];
$newSlug = $_POST['slug'];
$newCategoryId = $_POST['category_id'] ?? null;

// Lấy dữ liệu topic cũ
$stmt = $pdo->prepare("SELECT * FROM topic WHERE id = ?");
$stmt->execute([$id]);
$old = $stmt->fetch();

if (!$old) {
    die("Không tìm thấy chủ đề.");
}

$oldSlug = $old['slug'];
$oldCategoryId = $old['category_id'];

// Lấy slug danh mục mới và cũ
$oldPath = "../../../blog/";
$newPath = "../../../blog/";

if ($oldCategoryId) {
    $stmt2 = $pdo->prepare("SELECT slug FROM category WHERE id = ?");
    $stmt2->execute([$oldCategoryId]);
    $oldCat = $stmt2->fetch();
    $oldPath .= $oldCat['slug'] . "/";
}

if ($newCategoryId) {
    $stmt3 = $pdo->prepare("SELECT slug FROM category WHERE id = ?");
    $stmt3->execute([$newCategoryId]);
    $newCat = $stmt3->fetch();
    $newPath .= $newCat['slug'] . "/";
}

$oldFullPath = $oldPath . $oldSlug;
$newFullPath = $newPath . $newSlug;

// Kiểm tra slug trùng (trừ chính nó)
$stmt4 = $pdo->prepare("SELECT COUNT(*) as count FROM topic WHERE slug = ? AND id != ?");
$stmt4->execute([$newSlug, $id]);
$check = $stmt4->fetch();
if ($check['count'] > 0) {
    die("Slug đã tồn tại.");
}

// Đổi tên thư mục nếu cần
if ($oldFullPath !== $newFullPath && file_exists($oldFullPath)) {
    rename($oldFullPath, $newFullPath);
}

// Cập nhật DB
$stmt = $pdo->prepare("UPDATE topic SET name = :name, slug = :slug, category_id = :cat_id WHERE id = :id");
$stmt->execute([
    ':name' => $name,
    ':slug' => $newSlug,
    ':cat_id' => $newCategoryId,
    ':id' => $id
]);

header("Location: index.php?category_id=$newCategoryId");
exit;
