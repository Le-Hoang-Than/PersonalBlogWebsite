<?php
$host = 'localhost';
$db   = 'blog';
$user = 'root';        // Tùy chỉnh nếu khác
$pass = '';            // Mặc định XAMPP thường rỗng
$charset = 'utf8mb4';
$port = 3307; // Do bạn đã đổi sang 3307

//Trên host
// $host = 'sql304.byethost22.com'; // Ví dụ với ByetHost
// $db   = 'b22_38290172_blog';
// $user = 'b22_38290172';
// $pass = 'FP6K4PS3dkU4xN3';

$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    exit("Kết nối CSDL thất bại: " . $e->getMessage());
}
