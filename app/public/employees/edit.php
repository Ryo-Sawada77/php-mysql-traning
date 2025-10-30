<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use Em\Service\EmployeeService;

$service = new EmployeeService();

// POST送信されている場合 → 更新処理を実行
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employee = new \Em\Employee();
    $employee->employee_number  = $_POST['employee_number'];
    $employee->family_name      = $_POST['family_name'];
    $employee->address          = $_POST['address'];
    $employee->phone_number     = $_POST['phone_number'];
    $employee->employee_type_id = $_POST['employee_type_id'];

    $service->update($employee);
    // $service->updateEmails($employee);
    echo "更新が完了しました。<a href='index.php'>一覧へ戻る</a>";
    exit;
}

// GETパラメータから社員番号を取得して、その社員情報を取得
$id = $_GET['employee_number'] ?? null;
if (!$id) {
    exit('社員番号が指定されていません');
}

$employee = $service->getEmployeeDetail($id);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>社員情報編集</title>
</head>
<body>
  <!-- 社員詳細ページに遷移 -->
  <a href="detail.php?employee_number=<?= $employee->employee_number?>">社員詳細ページに戻る</a>

  <h1>社員情報編集</h1>
  <form method="post">
    <input type="hidden" name="employee_number" value="<?= htmlspecialchars($employee->employee_number) ?>">

    <div>
      <label>名字：</label>
      <input type="text" name="family_name" value="<?= htmlspecialchars($employee->family_name) ?>">
    </div>

    <div>
      <label>住所：</label>
      <input type="text" name="address" value="<?= htmlspecialchars($employee->address) ?>">
    </div>

    <div>
      <label>電話番号：</label>
      <input type="text" name="phone_number" value="<?= htmlspecialchars($employee->phone_number) ?>">
    </div>

    <div>
      <label>社員種別：</label>
      <select name="employee_type_id" required>
        <option value="">選択してください</option>
        <option value="1" <?= $employee->employee_type_id == 1 ? 'selected' : '' ?>>1：正社員</option>
        <option value="2" <?= $employee->employee_type_id == 2 ? 'selected' : '' ?>>2：契約社員</option>
        <option value="3" <?= $employee->employee_type_id == 3 ? 'selected' : '' ?>>3：アルバイト</option>
      </select>
    </div>

    <div>
      <label>Email:</label><br>
      <?php foreach ($employee->company_emails as $i => $email): ?>
          <input type="email" name="email[]" value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>"><br>
      <?php endforeach; ?>
      <!-- 新規追加用の空行 -->
      <input type="email" name="email[]" value=""><br>
    </div>

    <button type="submit">更新</button>
  </form>
</body>
</html>
