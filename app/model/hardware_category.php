<?php
require_once 'app/config/database.php';

function addCategory($info) {
    try {
        $sql = 'INSERT INTO categories (name, description) values(:name, :description)';
        $stmt = $GLOBALS['db']->prepare($sql);
        $stmt->execute([':name' => $info['name'] ,':description'=> $info['description']]);
    } catch (Exception $e) {
        echo ''. $e->getMessage();
    }
}

function getCategory($categoryName) {
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

function getCategories() {
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

function updateCategory($info){
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

function deleteCategory($cateoryName){
    try {
        $sql = 'DELETE FROM categories WHERE name = :name';
        $stmt = $GLOBALS['db']->prepare($sql); 
        $stmt->execute([':name'=> $cateoryName]);
    } catch (Exception $e) {
        echo ''. $e->getMessage();
    }
}

function uncategorizeCategory($categoryId){
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


