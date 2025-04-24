<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Đường dẫn PHPMailer
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// Thông tin form
$name = $_POST['name'] ?? 'Ẩn danh';
$email = $_POST['email'] ?? 'Không cung cấp';
$type = $_POST['type'] ?? 'Khác';
$message = $_POST['message'] ?? '';

// Gmail của bạn
$toEmail = "lehoangthan.blog@gmail.com"; // Đổi thành email bạn

$mail = new PHPMailer(true);

try {
    // Cấu hình SMTP
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'lehoangthan.blog@gmail.com';         //  Gmail của bạn
    $mail->Password = 'zdgulzuyywvakrqr';     //  Mật khẩu ứng dụng Gmail
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    // Nội dung
    $mail->setFrom($mail->Username, 'Blog Feedback');
    $mail->addAddress($toEmail);
    $mail->addReplyTo($email);

    // Đính kèm nếu có
    if (isset($_FILES['screenshot']) && $_FILES['screenshot']['error'] == 0) {
        $mail->addAttachment($_FILES['screenshot']['tmp_name'], $_FILES['screenshot']['name']);
    }

    $mail->isHTML(true);
    $mail->Subject = "📬 Phản hồi mới từ blog";
    $mail->Body    = "
        <b>Loại phản hồi:</b> $type<br>
        <b>Tên:</b> $name<br>
        <b>Email:</b> $email<br><br>
        <b>Nội dung:</b><br>" . nl2br($message);

    $mail->send();
    echo "✔️ Phản hồi đã được gửi thành công!";
} catch (Exception $e) {
    echo "❌ Gửi thất bại. Lỗi: {$mail->ErrorInfo}";
}
?>
