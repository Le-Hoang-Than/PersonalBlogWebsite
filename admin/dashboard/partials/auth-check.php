<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    // Nếu chưa đăng nhập, chuyển về trang đăng nhập
    header("Location: /PersonalBlogWebsite/admin/auth/login.php");
    exit();
}
?>