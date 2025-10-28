USE training_db;

CREATE TABLE employee_company_emails(
  id INT UNSIGNED NOT NULL AUTO_INCREMENT, 
  employee_number INT UNSIGNED NOT NULL, 
  email VARCHAR(255) NOT NULL UNIQUE,
  PRIMARY KEY(id),
  FOREIGN KEY(employee_number) REFERENCES employees(employee_number)
 );