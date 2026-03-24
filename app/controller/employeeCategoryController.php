<?php 
include 'app/model/hardwareModel.php';
include 'app/model/employeeModel.php';
include 'app/model/hardwareCategoryModel.php';
include 'app/model/assignment.php';
include 'app/model/employeeCategoryModel.php';



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
        'first_name'    => $_POST['first_name'] ?? '',
        'category_id'   => $_POST['category_id'] ?? '',
        'employee_id'   => $_POST['employee_id'] ?? '',
        'last_name'     => $_POST['last_name'] ?? '',
        'departement'   => $_POST['departement'] ?? '',
        'email'         => $_POST['email'] ?? '',
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

        case 'inactive':
            updateEmployeeStatus($info['employee_id'], 'inactive', 2);
            returnAssignment($info['employee_id']);
            break;

        case 'active':
            updateEmployeeStatus($info['employee_id'], 'active', $info['category_id']);
            break;
    }


}



$currentCategoryId = $_SESSION["categoryId"];
$currentCategoryName = $_SESSION["categoryName"];
$currentCategoryDescription = $_SESSION["categoryDescription"];
if (isset($_GET["search"])){
    $search = $_GET["search"];
    $employees = searchEmployee($search);
} else {
    $employees = getEmployeesByCategory($currentCategoryId) ?: [];
}
$isUncategorized = ($currentCategoryId == 1) ? true : false;
$inactive = ($currentCategoryId == 2) ? true : false;
$categories = getEmployeeCategories() ?: [];


include 'app/view/employees/employeeCategory.php';