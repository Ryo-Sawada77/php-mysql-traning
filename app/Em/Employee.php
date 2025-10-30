<?php

namespace Em;

class Employee {
    public $employee_number; 
    public $family_name;
    public $address;
    public $phone_number;
    public $employee_type_id;
    public $employee_type_name;     
    public $company_emails = []; 

    public function __set($name, $value) {
        if ($name === 'company_emails') { 
            $this->company_emails = $value ? explode(',', $value) : [];
        } else {
            $this->$name = $value;
        }
    }
}