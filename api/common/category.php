<?php
require_once '../../core/config/db.php';

header('Content-Type: application/json');

function getChildren($pdo, $parent_id) {
    $stmt = $pdo->prepare("SELECT id, name, slug FROM category WHERE parent_category_id = :parent_id AND deleted_at IS NULL ORDER BY name");
    $stmt->execute(['parent_id' => $parent_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$stmt = $pdo->prepare("SELECT id, name, slug FROM category WHERE parent_category_id IS NULL AND slug NOT IN ('home', 'news', 'administrator') AND deleted_at IS NULL ORDER BY name");
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($categories as &$cat) {
    $cat['children'] = getChildren($pdo, $cat['id']);
}

echo json_encode($categories);
