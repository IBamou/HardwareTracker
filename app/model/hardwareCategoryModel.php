<?php
require_once 'app/config/database.php';

function addHardwareCategory($info) {
    try {
        $sql = 'INSERT INTO categories (name, description) values(:name, :description)';
        $stmt = $GLOBALS['db']->prepare($sql);
        $stmt->execute([':name' => $info['name'] ,':description'=> $info['description']]);
    } catch (Exception $e) {
        echo ''. $e->getMessage();
    }
}

function uncategorizeHardwareCategory($categoryId){
    try {
        // Uncategorize all hardware in this category (set to 1)
        $sql = 'UPDATE hardwares SET category_id = 1 WHERE category_id = :category_id';
        $stmt = $GLOBALS['db']->prepare($sql);
        $stmt->execute([':category_id'=> $categoryId]);

        // Delete the category
        $sql = 'DELETE FROM categories WHERE id = :id';
        $stmt = $GLOBALS['db']->prepare($sql); 
        $stmt->execute([':id'=> $categoryId]);
    } catch (Exception $e) {
        echo ''. $e->getMessage();
    }
}



function getHardwareCategories() {
    try {
        $sql = 'SELECT * FROM categories';
        $stmt = $GLOBALS['db']->prepare($sql);
        $stmt->execute();
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $categories;
    } catch (Exception $e) {
        echo ''. $e->getMessage();
    }
}


function getHardwareCategory($categoryName) {
    try {
        $sql = 'SELECT id, name, description FROM categories WHERE name = :name ';
        $stmt = $GLOBALS['db']->prepare($sql);
        $stmt->execute([':name'=> $categoryName]);
        $category = $stmt->fetch(PDO::FETCH_ASSOC);
        return $category;
    } catch (Exception $e) {
        echo ''. $e->getMessage();
    }
}


function updateHardwareCategory($info){
    try {
        $sql = 'UPDATE categories SET name = :name, description = :description WHERE id = :id';
        $stmt = $GLOBALS['db']->prepare($sql); 
        $stmt->execute([':name'=> $info['name'],
                        ':description'=> $info['description'],
                        ':id' => $info['id']]);
    } catch (Exception $e) {
        echo ''. $e->getMessage();
    }
}

function deleteHardwareCategory($cateoryName){
    try {
        $sql = 'DELETE FROM categories WHERE name = :name';
        $stmt = $GLOBALS['db']->prepare($sql); 
        $stmt->execute([':name'=> $cateoryName]);
    } catch (Exception $e) {
        echo ''. $e->getMessage();
    }
}




function searchHardwareCategory($search){
    try {
        $sql = "SELECT *
                FROM categories
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
    