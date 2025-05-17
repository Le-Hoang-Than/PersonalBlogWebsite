<?php
require_once '../../../core/config/db.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: index.php?error=missing_id");
    exit;
}

$stmt = $pdo->prepare("
    SELECT ch.slug as chapter_slug, t.slug as topic_slug,
           c.slug as category_slug, ch.topic_id
    FROM chapter ch
    JOIN topic t ON ch.topic_id = t.id
    JOIN category c ON t.category_id = c.id
    WHERE ch.id = ?
");
$stmt->execute([$id]);
$data = $stmt->fetch();

if (!$data) {
    header("Location: index.php?error=not_found");
    exit;
}

$path = "../../../blog/{$data['category_slug']}/{$data['topic_slug']}/{$data['chapter_slug']}";

function deleteDirectory($dirPath) {
    if (!is_dir($dirPath)) return false;
    $files = array_diff(scandir($dirPath), ['.', '..']);
    foreach ($files as $file) {
        $filePath = "$dirPath/$file";
        if (is_dir($filePath)) {
            deleteDirectory($filePath);
        } else {
            unlink($filePath);
        }
    }
    return rmdir($dirPath);
}

deleteDirectory($path);

$stmt = $pdo->prepare("CALL delete_chapter(:id)");
$stmt->execute([':id' => $id]);

header("Location: index.php?topic_id=" . $data['topic_id']);
exit;
