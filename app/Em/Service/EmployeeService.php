<?php
namespace Em\Service;

use \Em\DBConnectionFactory;
use Em\Employee;
use \PDO;

class EmployeeService
{
  public function fetchAll()
  {
    // PDO オブジェクトを取得
    $pdo = DBConnectionFactory::newConnection();

    // 結果は PDOStatement オブジェクト として返る
    // ここではまだデータは取得されていない
    $stmt = $pdo->query("SELECT * FROM employees");

    // PDOStatement から全行を取得する
    // 取得した各行（連想配列）を 指定したクラスのインスタンス に変換
    return $stmt->fetchAll(\PDO::FETCH_CLASS, Employee::class);
  }

 
  // 指定社員の編集用データを取得
  public function edit(int $employee_number): ?Employee
  {
    $pdo = DBConnectionFactory::newConnection();
    $stmt = $pdo->prepare("SELECT * FROM employees WHERE employee_number = ?");
    $stmt->execute([$employee_number]);

    $stmt->setFetchMode(PDO::FETCH_CLASS, Employee::class);
    $employee = $stmt->fetch();

    // 該当がなければ null を返す
    return $employee ?: null;
  }

   // フォームのデータで社員情報を更新
  public function update(Employee $employee): bool
  {
    $pdo = DBConnectionFactory::newConnection();
    $stmt = $pdo->prepare("
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

  public function getEmployeeDetail(int $employee_number): ?Employee
  {
    $pdo = DBConnectionFactory::newConnection();

    // employees と employee_types を結合
    $stmt = $pdo->prepare("
        SELECT e.*, t.employee_type_name
        FROM employees e
        LEFT JOIN employee_types t ON e.employee_type_id = t.id
        WHERE e.employee_number = ?
    ");
    $stmt->execute([$employee_number]);
    $stmt->setFetchMode(\PDO::FETCH_CLASS, Employee::class);
    $employee = $stmt->fetch();

    if (!$employee) return null;

    // メール情報を取得してセット
    $stmt = $pdo->prepare("SELECT email FROM employee_company_emails WHERE employee_number = ?");
    $stmt->execute([$employee_number]);
    $emails = $stmt->fetchAll(\PDO::FETCH_COLUMN);
    $employee->company_emails = $emails ?: []; // null の場合は空配列

    return $employee;
  }

  public function createEmployee(\Em\Employee $employee): int
  {
    $pdo = DBConnectionFactory::newConnection();

    // employees テーブルに登録
    $stmt = $pdo->prepare("
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
    $employee_number = (int)$pdo->lastInsertId();

    // company_emails テーブルに登録
    if (!empty($employee->company_emails)) {
        $stmt = $pdo->prepare("INSERT INTO employee_company_emails (employee_number, email) VALUES (?, ?)");
        foreach ($employee->company_emails as $email) {
            $email = trim($email);
            if ($email === '') continue;
            $stmt->execute([$employee_number, $email]);
        }
    }

    return $employee_number; // 作成された社員番号を返す
  }
}