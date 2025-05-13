<?php
require_once __DIR__ . '/../../core/config/config.php';
require_once __DIR__ . '/../../core/config/db.php';

$email = $_GET['email'] ?? '';
$token = $_GET['token'] ?? '';

if (!$email || !$token) {
    die("Thiếu thông tin xác thực.");
}

// Gọi PROCEDURE để xác minh token
$stmt = $pdo->prepare("CALL VerifyAdminToken(:email, :token)");
$stmt->execute([
    ':email' => $email,
    ':token' => $token
]);

$result = $stmt->fetch(PDO::FETCH_ASSOC);

// Kiểm tra kết quả trả về
if (!$result || !$result['is_valid']) {
    die("Xác thực không hợp lệ hoặc token đã hết hạn.");
}

// Đăng nhập thành công
session_start();
$_SESSION['admin_id'] = $result['admin_id'];
// Gọi thủ tục xóa token sau xác minh thành công
$stmt = $pdo->prepare("CALL DeleteAdminToken(?)");
$stmt->execute([$result['admin_id']]);

header("Location: ../dashboard/index.php");
exit;
