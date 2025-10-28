<?php

$dbname = getenv('DB_DATABASE');

$dsn = 'mysql:host=mysql-server;dbname=' . $dbname . ';charset=utf8mb4';
$user = getenv('DB_USERNAME');
$pass = getenv('DB_PASSWORD');

$pdo = new PDO($dsn, $user, $pass);