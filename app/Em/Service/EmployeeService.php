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

      $sql = "
          SELECT
              e.employee_number,
              e.family_name,
              e.address,
              e.phone_number,
              e.employee_type_id,
              t.employee_type_name,
              GROUP_CONCAT(em.email) AS company_emails
          FROM employees AS e
          LEFT JOIN employee_types AS t
              ON e.employee_type_id = t.id
          LEFT JOIN employee_company_emails AS em
              ON e.employee_number = em.employee_number
          WHERE e.employee_number = ?
          GROUP BY e.employee_number
      ";

      $stmt = $pdo->prepare($sql);
      $stmt->execute([$employee_number]);

      $stmt->setFetchMode(\PDO::FETCH_CLASS, \Em\Employee::class);
      $employee = $stmt->fetch();

      // company_emails を配列に変換
      if ($employee && isset($employee->company_emails)) {
          $employee->company_emails = $employee->company_emails
              ? explode(',', $employee->company_emails)
              : [];
      }

      return $employee ?: null;
  }

  public function updateEmails(Employee $employee): bool
  {
      $pdo = DBConnectionFactory::newConnection();

      // トランザクション開始（念のため）
      $pdo->beginTransaction();

      try {
          // 既存のメールを削除
          $stmt = $pdo->prepare("DELETE FROM employee_company_emails WHERE employee_number = ?");
          $stmt->execute([$employee->employee_number]);

          // 新しいメールを挿入
          $stmt = $pdo->prepare("INSERT INTO employee_company_emails (employee_number, email) VALUES (?, ?)");
          foreach ($employee->company_emails as $email) {
              $email = trim($email);
              if ($email === '') continue; // 空文字はスキップ
              $stmt->execute([$employee->employee_number, $email]);
          }

          $pdo->commit();
          return true;

      } catch (\PDOException $e) {
          $pdo->rollBack();
          throw $e;
      }
  }

}
