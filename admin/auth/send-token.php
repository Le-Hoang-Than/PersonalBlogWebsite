<?php
require_once __DIR__ . '/../../core/config/db.php';
require_once __DIR__ . '/../../core/mail/mail.php';

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

// Gọi stored procedure kiểm tra thông tin đăng nhập
$stmt = $pdo->prepare("CALL CheckAdminCredentials(?, ?)");
$stmt->execute([$email, $password]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$result || !$result['is_valid']) {
 header("Location: login.php?error=" . urlencode("Email hoặc mật khẩu không chính xác."));
    exit;}

// Lấy ID admin
$stmt = $pdo->prepare("SELECT id FROM admin WHERE email = ?");
$stmt->execute([$email]);
$admin = $stmt->fetch();

if (!$admin) {
    die("Không tìm thấy admin.");
}

$token = bin2hex(random_bytes(32));
$tokenHash = hash('sha256', $token); // SHA2 tương ứng trong SQL

// Gọi stored procedure tạo token
$stmt = $pdo->prepare("CALL CreateAdminToken(?, ?)");
$stmt->execute([$admin['id'], $tokenHash]);

// Tạo link xác thực
$link = "http://localhost/PersonalBlogWebsite/admin/auth/verify-token.php?email=" . urlencode($email) . "&token=$token";

// Gửi email
sendEmail(
    $email,
    "Admin",
    "Xác minh đăng nhập",
    "Nhấn vào đây để đăng nhập: <a href='$link'>Xác nhận đăng nhập</a>",
    "Nhấn vào đây để đăng nhập: $link"
);

echo "Hãy kiểm tra email để xác minh.";
