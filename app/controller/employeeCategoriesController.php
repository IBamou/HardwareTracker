<?php 
include 'app/model/employeeCategoryModel.php';


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
}





if (isset($_GET["search"]) && !empty($_GET["search"])){
    $search = $_GET["search"];
    $categories = searchEmployeeCategory($search);
} else {
    $categories = getEmployeeCategories();
}




$title = 'Employee Categories';
$Uncategorized_Category_id = 1;


include 'app/view/employees/employeeCategories.php';