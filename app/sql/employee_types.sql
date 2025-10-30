use training_db;

CREATE TABLE employee_types (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    employee_type_name VARCHAR(64) NOT NULL UNIQUE,
    PRIMARY KEY(id)
);