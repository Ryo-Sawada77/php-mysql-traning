<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use Em\Service\EmployeeService;

$service = new EmployeeService();

// POST送信された場合 → 新規登録処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employee = new \Em\Employee();
    $employee->family_name      = $_POST['family_name'] ?? '';
    $employee->address          = $_POST['address'] ?? '';
    $employee->phone_number     = $_POST['phone_number'] ?? '';
    $employee->employee_type_id = $_POST['employee_type_id'] ?? 0;
    $employee->company_emails   = $_POST['email'] ?? []; 

    $employee_number = $service->createEmployee($employee);

    echo "社員登録が完了しました。社員番号: $employee_number<br>";
    echo "<a href='index.php'>一覧に戻る</a>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>社員新規登録</title>
    <link rel="stylesheet" href="static/add.css">
</head>
<body>
<h1>社員新規登録</h1>
<form method="post">

    <div>
        <label>名字：</label>
        <input type="text" name="family_name" required>
    </div>

    <div>
        <label>住所：</label>
        <input type="text" name="address" required>
    </div>

    <div>
        <label>電話番号：</label>
        <input type="text" name="phone_number" required>
    </div>

    <div>
      <label>社員種別：</label>
      <select name="employee_type_id" required>
        <option value="">選択してください</option>
        <option value="1">1：正社員</option>
        <option value="2">2：契約社員</option>
        <option value="3">3：アルバイト</option>
      </select>
    </div>

    <div>
        <label>Emails（複数可）：</label><br>
        <input type="email" name="email[]" placeholder="example1@domain.com"><br>
        <input type="email" name="email[]" placeholder="example2@domain.com"><br>
        <input type="email" name="email[]" placeholder="example3@domain.com"><br>
    </div>

    <button type="submit">登録</button>
</form>

<a href="index.php">一覧に戻る</a>
</body>
</html>