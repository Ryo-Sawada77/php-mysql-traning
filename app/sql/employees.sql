USE training_db;

CREATE TABLE employees(
  employee_number INT UNSIGNED NOT NULL AUTO_INCREMENT,
  family_name VARCHAR(64) NOT NULL,
  address VARCHAR(255) NOT NULL,
  phone_number VARCHAR(20) NOT NULL,
  employee_type_id INT UNSIGNED NOT NULL,
  PRIMARY KEY(employee_number),
  FOREIGN KEY(employee_type_id) REFERENCES employee_types(id)
);