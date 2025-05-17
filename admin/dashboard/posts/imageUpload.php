<?php
// Đường dẫn thư mục lưu ảnh upload
$uploadDir = __DIR__ . '/uploads/';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $file = $_FILES['image'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    // Kiểm tra định dạng file ảnh
    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    if (!in_array($ext, $allowed)) {
        echo json_encode(['success' => 0, 'message' => 'Chỉ chấp nhận file ảnh jpg, png, gif, webp']);
        exit;
    }

    // Đặt tên file mới tránh trùng
    $fileName = uniqid('img_') . '.' . $ext;
    $targetFile = $uploadDir . $fileName;

    if (move_uploaded_file($file['tmp_name'], $targetFile)) {
        // Đường dẫn URL để client truy cập ảnh
        $url = '/PersonalBlogWebsite/admin/dashboard/posts/uploads/' . $fileName;

        echo json_encode([
            'success' => 1,
            'file' => ['url' => $url]
        ]);
    } else {
        echo json_encode(['success' => 0, 'message' => 'Không thể lưu file ảnh']);
    }
} else {
    echo json_encode(['success' => 0, 'message' => 'Không tìm thấy file ảnh']);
}
