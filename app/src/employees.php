<?php

require 'db.php';
require 'auth.php';
include 'header.php';

// DBから社員一覧を取得
$stmt = $pdo->query("SELECT * FROM employees");
$employees = $stmt->fetchAll();

// 表示
foreach ($employees as $emp) {
    echo "<a href='employee_detail.php?id={$emp['id']}'>{$emp['name']}</a><br>";
}

include 'footer.php';