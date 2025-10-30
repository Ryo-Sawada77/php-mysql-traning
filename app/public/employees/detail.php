<?php
require __DIR__ . '/../../vendor/autoload.php';
use Em\Service\EmployeeService;

$employee_number = $_GET['employee_number'] ?? null;
if (!$employee_number) exit('社員番号が指定されていません');

$service = new EmployeeService();
$employee = $service->getEmployeeDetail((int)$employee_number);

if (!$employee) exit('該当する社員が見つかりません');
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>社員詳細</title>
</head>
<body>
  <h1>社員詳細</h1>

  <p><strong>社員番号:</strong> <?= htmlspecialchars($employee->employee_number) ?></p>
  <p><strong>氏名:</strong> <?= htmlspecialchars($employee->family_name, ENT_QUOTES, 'UTF-8') ?></p>
  <p><strong>住所:</strong> <?= htmlspecialchars($employee->address, ENT_QUOTES, 'UTF-8') ?></p>
  <p><strong>電話番号:</strong> <?= htmlspecialchars($employee->phone_number, ENT_QUOTES, 'UTF-8') ?></p>
  <p><strong>社員種別名:</strong> <?= htmlspecialchars($employee->employee_type_name, ENT_QUOTES, 'UTF-8') ?></p>
  
  <label>Emails:</label><br>
  <?php foreach ($employee->company_emails as $email): ?>
      <?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?><br>
  <?php endforeach; ?>

  <a href="edit.php?employee_number=<?= $employee->employee_number ?>">編集する</a>
  <br>

  <a href="index.php">一覧に戻る</a>
</body>
</html>
