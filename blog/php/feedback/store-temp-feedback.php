
<?php
require_once __DIR__ . '/../../../core/config/config.php';
require_once __DIR__ . '/../../../core/mail/mail.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);
    $token = bin2hex(random_bytes(16)); // Tạo mã xác minh
    $timestamp = time();

    $data = [
        'name' => $name,
        'email' => $email,
        'message' => $message,
        'token' => $token,
        'timestamp' => $timestamp
    ];

    // Ghi vào JSON file
    $file = 'pending-feedbacks.json';
    $list = [];

    if (file_exists($file)) {
        $json = file_get_contents($file);
        $list = json_decode($json, true) ?: [];
    }

    $list[] = $data;
    file_put_contents($file, json_encode($list, JSON_PRETTY_PRINT));

    // Gửi email xác nhận
    $verifyLink = "http://localhost/PersonalBlogWebsite/blog/php/feedback/verify-feedback.php?token=$token";

    $subject = "Xác nhận phản hồi từ $name";
    $htmlContent = "<p>Hãy đánh dấu email này là <strong>Không phải spam</strong> để truy cập liên kết xác nhận.<br>Click để xác nhận phản hồi:</p><a href='$verifyLink'>Xác nhận phản hồi</a>";
    $plainContent = "Hãy đánh dấu email này là không phải spam để truy cập liên kết xác nhận. Click vào link để xác nhận gửi phản hồi: $verifyLink";

    $result = sendEmail($email, $name, $subject, $htmlContent, $plainContent);

    if ($result === true) {
        echo "Vui lòng kiểm tra email để xác nhận phản hồi.";
    } else {
        echo "Lỗi gửi email xác minh: $result";
    }
}
?>