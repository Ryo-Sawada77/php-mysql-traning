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
<body>
<h1>社員一覧</h1>
<!-- 社員新規登録ページに遷移 -->
<a href="add.php">＋ 新規登録</a>

<!-- 社員番号でどの社員の詳細データを出す決める -->
<ul>
  <?php foreach($employees as $e):?>
  <li>
    <?= ($e->employee_number)?>
    <?= htmlspecialchars($e->family_name, ENT_QUOTES, 'UTF-8') ?>
    <a href="detail.php?employee_number=<?= $e->employee_number ?>">詳細</a>
  </li>
  <?php endforeach?>
</ul>

</body>
</html>
