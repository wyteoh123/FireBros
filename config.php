<?php
// Database Configuration
define('DB_HOST', 'localhost:3306');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'FireBros_db');

// Site Configuration
define('SITE_NAME', 'FireBros');
define('SITE_URL', 'http://localhost/FireBros');

// Admin Credentials (in a real app, these would be stored securely in the database)
$admin_users = [
    'admin' => [
        'password' => password_hash('admin123', PASSWORD_DEFAULT),
        'name' => 'Administrator',
        'email' => 'admin@FireBros.com'
    ]
];

// Start session
session_start();

// Create database connection
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Include functions
require_once 'functions.php';
?>

<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'firebros_db');

// 创建数据库连接
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// 检查连接
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}
?>