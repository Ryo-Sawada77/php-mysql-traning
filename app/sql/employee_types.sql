use training_db;

CREATE TABLE contract_types (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    employee_type_name VARCHAR(6464) NOT NULL UNIQUE,
    PRIMARY KEY(id)
);