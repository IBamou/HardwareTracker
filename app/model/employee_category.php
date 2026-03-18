<?php
require_once 'app/config/database.php';

function uncategorizeEmployeeCategory($categoryId) {
    try {

        // Move employees to 'Uncategorized'
        $sql = 'UPDATE employees SET category_id = 1 WHERE category_id = :category_id';
        $stmt = $GLOBALS['db']->prepare($sql);
        $stmt->execute([ ':category_id' => $categoryId]);

        // Delete the category
        $sql = 'DELETE FROM employee_categories WHERE id = :id';
        $stmt = $GLOBALS['db']->prepare($sql);
        $stmt->execute([':id' => $categoryId]);
    } catch (Exception $e) {
        echo ''. $e->getMessage();
    }
}


function getEmployeesByCategory($categoryName) {

    global $db;

    $sql = "SELECT e.*, c.name AS category_name FROM employees e JOIN employee_categories c ON e.category_id = c.id WHERE c.name = :name";

    $stmt = $db->prepare($sql);
    $stmt->execute([':name' => $categoryName]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getEmployeeCategories() {
    try {
        $sql = 'SELECT * FROM employee_categories order by id asc';
        $stmt = $GLOBALS['db']->prepare($sql);
        $stmt->execute();
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $categories;
    } catch (Exception $e) {
        echo ''. $e->getMessage();
    }
}



function getEmployeeCategoryByName($name) {
    global $db;
    $sql = "SELECT * FROM employee_categories WHERE name = :name";
    $stmt = $db->prepare($sql);
    $stmt->execute([':name' => $name]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


function addEmployeeCategory($info) {
    try {
        $sql = 'INSERT INTO employee_categories (name, description) values(:name, :description)';
        $stmt = $GLOBALS['db']->prepare($sql);
        $stmt->execute([':name' => $info['name'] ,':description'=> $info['description']]);
    } catch (Exception $e) {
        echo ''. $e->getMessage();
    }
}

function getEmployeeCategory($categoryName) {
    try {
        $sql = 'SELECT id, name, description FROM employee_categories WHERE name = :name ';
        $stmt = $GLOBALS['db']->prepare($sql);
        $stmt->execute([':name'=> $categoryName]);
        $category = $stmt->fetch(PDO::FETCH_ASSOC);
        return $category;
    } catch (Exception $e) {
        echo ''. $e->getMessage();
    }
}



function updateEmployeeCategory($info){
    try {
        $sql = 'UPDATE employee_categories SET name = :name, description = :description WHERE id = :id';
        $stmt = $GLOBALS['db']->prepare($sql); 
        $stmt->execute([':name'=> $info['name'],
                        ':description'=> $info['description'],
                        ':id' => $info['id']]);
    } catch (Exception $e) {
        echo ''. $e->getMessage();
    }
}

function deleteEmployeeCategory($categoryName){
    try {
        $sql = 'DELETE FROM employee_categories WHERE name = :name';
        $stmt = $GLOBALS['db']->prepare($sql); 
        $stmt->execute([':name'=> $categoryName]);
    } catch (Exception $e) {
        echo ''. $e->getMessage();
    }
}

