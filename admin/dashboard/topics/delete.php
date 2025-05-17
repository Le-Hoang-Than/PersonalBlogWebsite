<?php
require_once '../../../core/config/db.php';

// Kiểm tra xem có truyền id chủ đề cần xóa không
if (isset($_GET['id'])) {
    $topic_id = $_GET['id'];

    // Lấy thông tin chủ đề để xác định đường dẫn thư mục
    $stmt = $pdo->prepare("SELECT * FROM topic WHERE id = :id");
    $stmt->execute(['id' => $topic_id]);
    $topic = $stmt->fetch();

    if ($topic) {
        $slug = $topic['slug'];
        $category_id = $topic['category_id'];

        // Lấy slug của category để tạo đường dẫn thư mục
        $stmt2 = $pdo->prepare("SELECT slug FROM category WHERE id = :id");
        $stmt2->execute(['id' => $category_id]);
        $category = $stmt2->fetch();

        if ($category) {
            $categorySlug = $category['slug'];
            $path = "../../../blog/{$categorySlug}/{$slug}";
        } else {
            // Không tìm thấy danh mục
            $path = null;
        }

        try {
            // Gọi thủ tục xóa topic
            $stmt = $pdo->prepare("CALL delete_topic(:id)");
            $stmt->execute(['id' => $topic_id]);

            // Xóa thư mục vật lý nếu tồn tại
            if ($path && file_exists($path)) {
                deleteDirectory($path);
            }

            header("Location: index.php?category_id=" . $category_id);
            exit;
        } catch (PDOException $e) {
            echo "Lỗi khi xóa chủ đề: " . $e->getMessage();
        }
    } else {
        echo "Chủ đề không tồn tại!";
    }
}

// Hàm xóa thư mục và các tệp con
function deleteDirectory($dirPath) {
    if (!is_dir($dirPath)) {
        return;
    }

    $files = array_diff(scandir($dirPath), array('.', '..'));

    foreach ($files as $file) {
        $filePath = "$dirPath/$file";
        if (is_dir($filePath)) {
            deleteDirectory($filePath);
        } else {
            unlink($filePath);
        }
    }

    rmdir($dirPath);
}
?>
