<?php 
include 'app/model/hardware.php';
include 'app/model/employee.php';
include 'app/model/hardware_category.php';
include 'app/model/assignment.php';
include 'app/model/employee_category.php';
session_start();



if (isset($_POST["showCategory"])) {
    $_SESSION["categoryId"] = $_POST["id"];
    $_SESSION["categoryName"] = $_POST["name"];
    $_SESSION["categoryDescription"] = $_POST["description"];
}

if (isset($_POST["action"])) {
    include 'app/controller/main.php';
}

if (isset($_POST['operation'])){

    $info = [
        'first_name'          => $_POST['first_name'] ?? '',
        'category_id'   => $_POST['category_id'] ?? '',
        'employee_id' => $_POST['employee_id'] ?? '',
        'last_name' => $_POST['last_name'] ?? '',
        'departement' => $_POST['departement'] ?? '',
        'email' => $_POST['email'] ?? '',
    ];

    switch ($_POST['operation']){

        case 'addEmployee':
            addEmployee($info);
            break;
        
        case 'editEmployee':
            updateEmployee($info);
            break;

        case 'uncatigorizeEmployee':
            uncatigorizeEmployee($info['employee_id']);
            $actionSuccess = true;
            break;

        case 'addEmployeeToCategory':;
            addEmployeeToCategory($info);
            break;

    }

    header('location: /hardwareTracker/employeeCategory');
    exit; 
    }






$currentCategoryId = $_SESSION["categoryId"];
$currentCategoryName = $_SESSION["categoryName"];
$currentCategoryDescription = $_SESSION["categoryDescription"];

$isUncategorized = ($currentCategoryId == 1) ? true : false;
$employees = getEmployeesByCategory($currentCategoryId) ?: [];
$categories = getEmployeeCategories() ?: [];


include 'app/view/employees/employee_category.php';