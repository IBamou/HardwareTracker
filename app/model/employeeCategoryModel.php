<?php
require_once 'app/config/database.php';

function addEmployeeCategory($info) {
    try {
        $sql = 'INSERT INTO employee_categories (name, description) values(:name, :description)';
        $stmt = $GLOBALS['db']->prepare($sql);
        $stmt->execute([':name' => $info['name'] ,':description'=> $info['description']]);
    } catch (Exception $e) {
        echo ''. $e->getMessage();
    }
}

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


function deleteEmployeeCategory($cateoryName){
    try {
        $sql = 'DELETE FROM categories WHERE name = :name';
        $stmt = $GLOBALS['db']->prepare($sql); 
        $stmt->execute([':name'=> $cateoryName]);
    } catch (Exception $e) {
        echo ''. $e->getMessage();
    }
}

function searchEmployeeCategory($search){
    try {
        $sql = "SELECT *
                FROM employee_categories
                WHERE name LIKE :search
        ";

        $stmt = $GLOBALS['db']->prepare($sql);
        $stmt->execute([':search' => "%$search%"]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (Exception $e) {
        echo $e->getMessage();
        return null;
    }
}
    




