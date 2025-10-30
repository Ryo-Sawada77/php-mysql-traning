<?php

use Em\Employee;
use Em\Service\EmployeeService;

require __DIR__.'/../../vendor/autoload.php';

$service = new EmployeeService();
$employees = $service->fetchAll();
 
?>
<!DOCTYPE html>
<html lang="ja">
<head><meta charset="UTF-8"><title>社員一覧</title></head>
<link rel="stylesheet" href="static/index.css">
<body>
<h1>社員一覧</h1>
<!-- 社員新規登録ページに遷移 -->
<a href="add.php">＋ 新規登録</a>

<!-- 社員番号でどの社員の詳細データを出す決める -->
<ul class="employee-list">
  <?php foreach($employees as $e): ?>
    <li>
      <div class="employee-info">
        <?= htmlspecialchars($e->employee_number . '. ' . $e->family_name) ?>
      </div>
      <a href="detail.php?employee_number=<?= $e->employee_number ?>" class="detail-button">詳細</a>
    </li> <?php endforeach; ?>
</ul>

</body>
</html>
