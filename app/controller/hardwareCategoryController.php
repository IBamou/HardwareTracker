<?php 
include 'app/model/hardware.php';
include 'app/model/employee.php';
include 'app/model/hardware_category.php';
include 'app/model/assignment.php';
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
        'name'          => $_POST['hardware'] ?? '',
        'category_id'   => $_POST['category_id'] ?? '',
        'serial_number' => $_POST['serial_number'] ?? '',
        'status'        => $_POST['status'] ?? '',
        'purchase_date' => $_POST['purchase_date'] ?? '',
        'received_date' => $_POST['received_date'] ?? '',
        'hardware_id'   => $_POST['hardware_id'] ?? '',
        'price'         => $_POST['price'] ?? '',
        'employee_id'   => $_POST['employee_id'] ?? ''
    ];

    switch ($_POST['operation']){

        case 'addHardware':
            addHardware($info);
            break;
        
        case 'editHardware':
            updateHardware($info);
            break;

        case 'uncatigorizeHardware':
            echo 'Uncategorizing hardware...';
            uncatigorizeHardware($info['hardware_id']);
            $actionSuccess = true;
            break;

        case 'addHardwareToCategory':;
            addHardwareToCatgory($info);
            break;

        case 'assignHardwareToEmployee':
            addAssignment($info);
            updateHardwareStatus($info['hardware_id'], 'assigned');
            break;

        case 'returnHardware':
            updateAssignment($info['hardware_id']);
            updateHardwareStatus($info['hardware_id'], 'available');
            break;

    }

    header('location: /hardwareTracker/hardwareCategory');
    exit; 
    }






$currentCategoryId = $_SESSION["categoryId"];
$currentCategoryName = $_SESSION["categoryName"];
$currentCategoryDescription = $_SESSION["categoryDescription"];

$isUncategorized = ($currentCategoryId == 1) ? true : false;
$hardwares = getHardwaresByCategory($currentCategoryId);
$employees = getEmployees() ?: [];
$categories = getCategories() ?: [];


include 'app/view/hardwares/hardware_category.php';