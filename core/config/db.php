<?php
$host = 'localhost';
$db   = 'blog';
$user = 'root';        // Tùy chỉnh nếu khác
$pass = '';            // Mặc định XAMPP thường rỗng
$charset = 'utf8mb4';
$port = 3307; // Do bạn đã đổi sang 3307

//Trên host
// $host = 'sql.byethost.com'; // Ví dụ với ByetHost
// $db   = 'your_database_name';
// $user = 'your_username';
// $pass = 'your_password';

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
