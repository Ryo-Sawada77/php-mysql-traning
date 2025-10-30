<?php

namespace Em;

class Employee {
    public $employee_number; 
    public $family_name;
    public $address;
    public $phone_number;
    public $employee_type_id;
    public $employee_type_name;     
    public $company_emails = []; // 空配列で初期化

    // __set は必要ない場合は削除してOK
    public function __set($name, $value) {
        if ($name === 'company_emails') { // $ は不要
            $this->company_emails = $value ? explode(',', $value) : [];
        } else {
            $this->$name = $value;
        }
    }
}