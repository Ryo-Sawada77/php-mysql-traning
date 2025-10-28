<?php
require '../db_connection.php';
$stmt = $pdo->query('SELECT employee_number, family_name FROM employees');
$employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ja">
<head><meta charset="UTF-8"><title>社員一覧</title></head>
<body>
<h1>社員一覧</h1>
<a href="add.php">＋ 新規登録</a>
<ul>
  <?php foreach ($employees as $e): ?>
    <li>
      <a href="detail.php?employee_number=<?= htmlspecialchars($e['employee_number']) ?>">
        <?= htmlspecialchars($e['family_name']) ?>
      </a>
    </li>
  <?php endforeach; ?>
</ul>
</body>
</html>
