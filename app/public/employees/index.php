<?php

use Em\Service\EmployeeService;
use Em\DBConnectionFactory;

require_once __DIR__.'/../../vendor/autoload.php';

$service = new EmployeeService(DBConnectionFactory::newConnection());
$employees = $service->fetchAll();

?>
<!DOCTYPE html>
<html lang="ja">
<head><meta charset="UTF-8"><title>社員一覧</title></head>
<link rel="stylesheet" href="static/index.css">
<body>
  <div style="width: 1360px; margin: auto;">
    
    <!-- 社員新規登録ページに遷移 -->
    <a href="add.php" style="float: right;">＋ 新規登録</a>

    <h1 style="text-align: left;">社員一覧</h1>
    <!-- 社員番号でどの社員の詳細データを出す決める -->
    <table style="width: 100%; border-collapse: collapse; border: 1px solid #ccc;">
  <tr>
    <th style="border: 1px solid #ccc; padding: 8px;">社員番号</th>
    <th style="border: 1px solid #ccc; padding: 8px;">氏名</th>
    <th style="border: 1px solid #ccc; padding: 8px;">社員種別</th>
    <th style="border: 1px solid #ccc; padding: 8px;"></th>
  </tr>
  <?php foreach ($employees as $e): ?>
  <tr>
    <td style="border: 1px solid #ccc; padding: 8px;"><?= htmlspecialchars($e->employee_number) ?></td>
    <td style="border: 1px solid #ccc; padding: 8px;"><?= htmlspecialchars($e->family_name) ?></td>
    <td style="border: 1px solid #ccc; padding: 8px;"><?= htmlspecialchars($e->employee_type_name) ?></td>
    <td style="border: 1px solid #ccc; padding: 8px;"><a class="detail-buttun" href="detail.php?employee_number=<?= ($e->employee_number) ?>">詳細</a></td>
  </tr>
  <?php endforeach; ?>
</table>
  </div>



</body>
</html>
