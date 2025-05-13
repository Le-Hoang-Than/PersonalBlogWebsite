<?php
require_once __DIR__ . '/../../../core/config/config.php';
require_once __DIR__ . '/../../../core/mail/mail.php';

$token = $_GET['token'] ?? '';

if (!$token) {
    exit('Liên kết không hợp lệ.');
}

$pendingFile = 'pending-feedbacks.json';
if (!file_exists($pendingFile)) {
    exit('Không tìm thấy dữ liệu phản hồi.');
}

$pendingData = json_decode(file_get_contents($pendingFile), true);
$found = false;

foreach ($pendingData as $index => $feedback) {
    if ($feedback['token'] === $token) {
        $found = true;

        // Soạn nội dung email gửi đến admin
        $subject = "Phản hồi từ " . $feedback['name'];
        $htmlContent = "
            <h3>Thông tin phản hồi đã được xác thực</h3>
            <p><strong>Họ tên:</strong> {$feedback['name']}</p>
            <p><strong>Email:</strong> {$feedback['email']}</p>
            <p><strong>Nội dung:</strong><br>{$feedback['message']}</p>";
        $plainContent = "Phản hồi từ {$feedback['name']} ({$feedback['email']}):\n\n{$feedback['message']}";

        $result = sendEmail(TO_EMAIL, TO_NAME, $subject, $htmlContent, $plainContent);

        if ($result !== true) {
            echo 'Lỗi gửi mail xác thực: ' . $result;
            exit;
        }

        // Xoá phản hồi đã xác thực
        array_splice($pendingData, $index, 1);
        file_put_contents($pendingFile, json_encode($pendingData, JSON_PRETTY_PRINT));

        $title = 'Xác minh phản hồi';
        $message = 'Phản hồi của bạn đã được xác minh và gửi đi. Cảm ơn!';
        $image = 'verify-success.png'; // Hình thành công
        include '../verify/verify-result.php';
        exit;
    }
}

if (!$found) {
    $title = 'Xác minh thất bại';
    $message = 'Liên kết xác thực không hợp lệ hoặc đã hết hạn.';
    $image = 'verify-fail.png'; // Hình thành công
    include '../verify/verify-result.php';
    exit;
}
