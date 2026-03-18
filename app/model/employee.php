<?php
require_once 'app/config/database.php';


function addEmployee($info){
    try {
        $sql = 'INSERT INTO employees (first_name, last_name, email, departement, category_id) values(:first_name, :last_name, :email, :departement, :category_id) ';
        $stmt = $GLOBALS['db']->prepare($sql);

        $stmt->execute([':first_name' => $info['first_name'], 
                        ':last_name'   => $info['last_name'],
                        ':email' => $info['email'],
                        ':departement' => $info['departement'],
                        ':category_id' => $info['category_id']
        ]) ;
    } catch (Exception $e) {
        echo ''. $e->getMessage();
    }
}   


function getEmployees() {
    try {
        $sql = 'SELECT * FROM employees';
        $stmt = $GLOBALS['db']->prepare($sql);
        $stmt->execute();
        $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $employees;
    } catch (Exception $e) {
        echo ''. $e->getMessage();
    }
}



function getEmployee($fullName) {
    try {
        $sql = 'SELECT id FROM employees WHERE first_name = :first_name AND last_name = :last_name';
        $stmt = $GLOBALS['db']->prepare($sql);
        $stmt->execute([':first_name' => $fullName[0], 'last_name'=> $fullName[1]]);
        $employee = $stmt->fetch(PDO::FETCH_ASSOC);
        return $employee;
    } catch (Exception $e) {
        echo ''. $e->getMessage();
    }
}

function uncatigorizeEmployee($employee_id){
    try {
        $sql = 'UPDATE employees SET category_id = 1 WHERE id = :employee_id';
        $stmt = $GLOBALS['db']->prepare($sql); 
        $stmt-> execute([':employee_id'=> $employee_id]);
    } catch (Exception $e) {
        echo ''. $e->getMessage();
    } 
}

function AddEmployeeToCategory($info) {
    try {
        // Make sure category exists or set NULL
        $category_id = !empty($info['category_id']) 
            ? (int)$info['category_id'] 
            : null;

        if ($category_id !== null) {
            $check = $GLOBALS['db']->prepare('SELECT id FROM employee_categories WHERE id = :id');
            $check->execute([':id' => $category_id]);
            if (!$check->fetch()) {
                $category_id = null; // category not found, set to null
            }
        }

        $sql = 'UPDATE employees SET category_id = :category_id WHERE id = :employee_id';
        $stmt = $GLOBALS['db']->prepare($sql);
        $stmt->execute([
            ':category_id' => $category_id,
            ':employee_id' => $info['employee_id']
        ]);
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
    }
}

function  updateEmployee($info) {
    try {
        $sql = 'UPDATE employees SET first_name = :first_name, last_name = :last_name, email = :email, departement = :departement WHERE id = :employee_id';
        $stmt = $GLOBALS['db']->prepare($sql);
        $stmt->execute([':first_name' => $info['first_name'],
                        ':last_name'   => $info['last_name'],
                        ':email' => $info['email'],
                        ':departement' => $info['departement'],
                        ':employee_id' => $info['employee_id']
        ]);
    } catch (Exception $e) {
        echo ''. $e->getMessage();
    }
}

