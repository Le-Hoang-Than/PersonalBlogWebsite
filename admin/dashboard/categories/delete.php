<?php
require_once '../../../core/config/db.php';

// Kiểm tra xem có truyền id danh mục cần xóa không
if (isset($_GET['id'])) {
    $category_id = $_GET['id'];

    // Lấy thông tin danh mục trước khi xóa để xác định đường dẫn thư mục
    $stmt = $pdo->prepare("SELECT * FROM category WHERE id = :id");
    $stmt->execute(['id' => $category_id]);
    $category = $stmt->fetch();

    if ($category) {
        // Lấy slug và parent_category_id
        $slug = $category['slug'];
        $parent_category_id = $category['parent_category_id'];

        // Xác định đường dẫn thư mục
        if ($parent_category_id) {
            // Nếu có danh mục cha, đường dẫn sẽ có dạng "../../../blog/parent_slug/slug"
            $stmt2 = $pdo->prepare("SELECT slug FROM category WHERE id = :parent_id");
            $stmt2->execute(['parent_id' => $parent_category_id]);
            $parent_category = $stmt2->fetch();
            $path = "../../../blog/{$parent_category['slug']}/$slug";
        } else {
            // Nếu không có danh mục cha, đường dẫn sẽ có dạng "../../../blog/slug"
            $path = "../../../blog/$slug";
        }

        // Xóa danh mục trong cơ sở dữ liệu
        try {
            // Gọi thủ tục xóa danh mục
            $stmt = $pdo->prepare("CALL delete_category(:id)");
            $stmt->execute(['id' => $category_id]);

            // Xóa thư mục nếu tồn tại
            if (file_exists($path)) {
                deleteDirectory($path);
            }

            header("Location: index.php");
            exit;
        } catch (PDOException $e) {
            echo "Lỗi khi xóa danh mục: " . $e->getMessage();
        }
    } else {
        echo "Danh mục không tồn tại!";
    }
}

// Hàm xóa thư mục và tất cả các tệp con
function deleteDirectory($dirPath) {
    // Kiểm tra nếu thư mục tồn tại
    if (!is_dir($dirPath)) {
        return;
    }

    // Lấy tất cả các tệp trong thư mục
    $files = array_diff(scandir($dirPath), array('.', '..'));

    // Xóa từng tệp trong thư mục
    foreach ($files as $file) {
        $filePath = "$dirPath/$file";
        if (is_dir($filePath)) {
            // Đệ quy xóa thư mục con
            deleteDirectory($filePath);
        } else {
            // Xóa tệp
            unlink($filePath);
        }
    }

    // Xóa thư mục sau khi đã xóa tất cả các tệp con
    rmdir($dirPath);
}
?>
