<?php
require_once '../../../core/config/db.php';
$name = $_POST['name'] ?? '';
$slug = $_POST['slug'] ?? '';
$parent_category_id = !empty($_POST['parent_category_id']) ? $_POST['parent_category_id'] : null;

// Kiểm tra slug đã tồn tại hay chưa
$stmt = $pdo->prepare("CALL check_slug_exists(:slug)");
$stmt->execute(['slug' => $slug]);
$result = $stmt->fetch();
$stmt->closeCursor();

if ($result['slug_count'] > 0) {
    // Nếu slug đã tồn tại, quay lại trang create.php với thông báo lỗi
    header("Location: create.php?error=slug_exists");
    exit;
}

// Nếu slug chưa tồn tại, thêm danh mục vào cơ sở dữ liệu
$stmt = $pdo->prepare("CALL insert_category(:name, :slug, :parent_id)");
$stmt->execute([
    ':name' => $name,
    ':slug' => $slug,
    ':parent_id' => $parent_category_id
]);

// Xây dựng đường dẫn thư mục theo danh mục cha nếu có
if ($parent_category_id) {
    $stmt2 = $pdo->prepare("SELECT slug FROM category WHERE id = ?");
    $stmt2->execute([$parent_category_id]);
    $parent = $stmt2->fetch();
    $path = "../../../blog/{$parent['slug']}/$slug";
} else {
    $path = "../../../blog/$slug";
}

// Tạo thư mục nếu chưa tồn tại
if (!file_exists($path)) {
    mkdir($path, 0755, true);
    /**
     *file_put_contents($path . "/index.php", "<?php\n// Trang $name\n?><h1>$name</h1>");
     *file_put_contents($path . "/index.html", $template);
     * 
     */
    // $templatePath = __DIR__ . '/../../../core/template/template.html'; // Đường dẫn đến template
    // if (!file_exists($templatePath)) {
    //     die("File template không tồn tại: $templatePath");
    // }
    // if (!copy($templatePath, $path . '/index.html')) {
    //     die("Không thể sao chép template.");
    // }
}

// Sau khi lưu thành công, chuyển hướng về trang danh sách danh mục
header("Location: index.php");
exit;
