<?php

$dsn = 'mysql:host=php-mysql-traninng-mysql-server-1;dbname=training_db;charset=utf8';
$user = 'mysql-user';
$pass = getenv('DB_PASSWORD');

$options = [
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, 
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_EMULATE_PREPARES => FALSE,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    echo "接続成功" . PHP_EOL;
} catch (PDOException $e) {
    echo "接続失敗:" . $e->getMessage()  . PHP_EOL;
    exit;
}
?>