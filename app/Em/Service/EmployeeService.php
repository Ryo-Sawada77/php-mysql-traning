<?php
namespace Em\Service;

use \Em\DBConnectionFactory;
use Em\Employee;
use \PDO;

class EmployeeService
{
  public function __construct(private \PDO $connection)
  {
    
  }

  public function fetchAll()
  {
    // 結果は PDOStatement オブジェクト として返る
    // ここではまだデータは取得されていない
    $stmt = $this->connection->query("SELECT * FROM employees ORDER BY employee_number ASC");
    $stmt = $this->connection->prepare("
        SELECT e.*, t.employee_type_name
        FROM employees e
        LEFT JOIN employee_types t ON e.employee_type_id = t.id
    ");
    $stmt->execute();
    // PDOStatement から全行を取得する
    // 取得した各行（連想配列）を 指定したクラスのインスタンス に変換
    return $stmt->fetchAll(\PDO::FETCH_CLASS, Employee::class);
  }

 
  // 指定社員の編集用データを取得
  public function edit(int $employee_number): ?Employee
  {
    $stmt = $this->connection->prepare("SELECT * FROM employees WHERE employee_number = ?");
    $stmt->execute([$employee_number]);

    $stmt->setFetchMode(PDO::FETCH_CLASS, Employee::class);
    $employee = $stmt->fetch();

    // 該当がなければ null を返す
    return $employee ?: null;
  }

   // フォームのデータで社員情報を更新
  public function update(Employee $employee): bool
  {
    $stmt = $this->connection->prepare("
      UPDATE employees
      SET family_name = :family_name,
          address = :address,
          phone_number = :phone_number,
          employee_type_id = :employee_type_id
      WHERE employee_number = :employee_number
    ");

    return $stmt->execute([
      ':family_name' => $employee->family_name,
      ':address' => $employee->address,
      ':phone_number' => $employee->phone_number,
      ':employee_type_id' => $employee->employee_type_id,
      ':employee_number' => $employee->employee_number,
    ]);
  }

  public function fetchEmployeeDetail(int $employee_number): ?Employee
  {
    // employees と employee_types を結合
    $stmt = $this->connection->prepare("
        SELECT e.*, t.employee_type_name
        FROM employees e
        LEFT JOIN employee_types t ON e.employee_type_id = t.id
        WHERE e.employee_number = ?
    ");
    $stmt->execute([$employee_number]);
    $stmt->setFetchMode(\PDO::FETCH_CLASS, Employee::class);
    $employee = $stmt->fetch();

    if ($employee === null) {
      return null;
    }

    // メール情報を取得してセット
    $stmt = $this->connection->prepare("SELECT email FROM employee_company_emails WHERE employee_number = ?");
    $stmt->execute([$employee_number]);
    $emails = $stmt->fetchAll(\PDO::FETCH_COLUMN);
    $employee->company_emails = $emails ?? []; 

    return $employee;
  }

  public function createEmployee(\Em\Employee $employee): int
  {

    // employees テーブルに登録
    $stmt = $this->connection->prepare("
        INSERT INTO employees (family_name, address, phone_number, employee_type_id)
        VALUES (:family_name, :address, :phone_number, :employee_type_id)
    ");

    $stmt->execute([
        ':family_name'      => $employee->family_name,
        ':address'          => $employee->address,
        ':phone_number'     => $employee->phone_number,
        ':employee_type_id' => $employee->employee_type_id,
    ]);

    // 登録された社員番号を取得
    $employee_number = (int)$this->connection->lastInsertId();

    // company_emails テーブルに登録
    if (!empty($employee->company_emails)) {
        $stmt = $this->connection->prepare("INSERT INTO employee_company_emails (employee_number, email) VALUES (?, ?)");
        foreach ($employee->company_emails as $email) {
            $email = trim($email);
            if ($email === '') continue;
            $stmt->execute([$employee_number, $email]);
        }
    }

    return $employee_number; // 作成された社員番号を返す
  }
  
  public function updateEmails($employee_number, $emails)
  {
    // 前後の空白を削除し、空文字を除去
    $newEmails = array_filter(array_map('trim', $emails));

    // 現在DBにあるメールを取得
    $stmt = $this->connection->prepare("SELECT id, email FROM employee_company_emails WHERE employee_number = ?");
    $stmt->execute([$employee_number]);
    $currentRows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 現在のIDとメールをマッピング
    $currentMap = [];
    foreach ($currentRows as $row) {
        $currentMap[$row['id']] = $row['email'];
    }

    // フォーム側のインデックス順で比較する（既存＋新規）
    foreach ($newEmails as $i => $email) {
        if (isset($currentRows[$i])) {
            // 既存行がある → 変更チェック
            $currentId = $currentRows[$i]['id'];
            if ($email !== $currentRows[$i]['email']) {
                $stmt = $this->connection->prepare("UPDATE employee_company_emails SET email = ? WHERE id = ?");
                $stmt->execute([$email, $currentId]);
            }
        } else {
            // 新規行（既存より多いインデックス）→ INSERT
            $stmt = $this->connection->prepare("INSERT INTO employee_company_emails (employee_number, email) VALUES (?, ?)");
            $stmt->execute([$employee_number, $email]);
        }
    }

    // 既存のほうが多い（フォームで空欄になった）→ 削除
    for ($i = count($newEmails); $i < count($currentRows); $i++) {
        $stmt = $this->connection->prepare("DELETE FROM employee_company_emails WHERE id = ?");
        $stmt->execute([$currentRows[$i]['id']]);
    }
  }

}