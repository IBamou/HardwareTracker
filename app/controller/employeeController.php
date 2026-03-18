<?php 
session_start();
include 'app/model/employee_category.php';


if (isset($_POST['action'])){
    include 'app/controller/main.php';
}

if (isset($_POST['operation'])){

    $info = [
        'id'=> $_POST['id'] ?? '',
        'name' => $_POST['name'] ?? '',
        'description'=> $_POST['description'] ?? '',
    ];

    switch ($_POST['operation']){

        case 'addEmployeeCategory':
            addEmployeeCategory($info);
            break;

        
        case 'editEmployeeCategory':
            updateEmployeeCategory($info);
            break;

        case 'deleteEmployeeCategory':
            $categoryId = $_POST["id"]; 
            uncategorizeEmployeeCategory($categoryId); 
            $deleteSuccess = true;
    }
    header('location: /hardwareTracker/employees');
    exit;
}










$title = 'Employee Categories';
$Uncategorized_Category_id = 1;
$categories = getEmployeeCategories();

include 'app/view/employees/employees.php';