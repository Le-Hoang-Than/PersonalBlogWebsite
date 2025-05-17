<?php
require_once __DIR__ . '/../../core/config/config.php';

// Đăng nhập thành công
session_start();
$_SESSION['admin_id'] = 1;

header("Location: ../dashboard/index.php");
exit;
