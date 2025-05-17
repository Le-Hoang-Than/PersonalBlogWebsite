<?php
require_once '../../../core/config/db.php';

$category_id = $_GET['category_id'] ?? null;

if (!$category_id) {
    echo json_encode([]);
    exit;
}

// Gọi proc để lấy topic theo category
$stmt = $pdo->prepare("CALL get_topics_by_category_id   (:cat_id)");
$stmt->execute(['cat_id' => $category_id]);
$topics = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt->closeCursor();

echo json_encode($topics);
