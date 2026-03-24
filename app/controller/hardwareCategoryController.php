<?php 
include 'app/model/hardwareCategoryModel.php';
include 'app/model/employeeModel.php';
include 'app/model/hardwareModel.php';
include 'app/model/assignment.php';



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
            $info['hardware_id'] = getHardware($info['serial_number'])['id'];
            addToLogs($info, 'added', $info['status']);
            break;
        
        case 'editHardware':
            if (checkUpdate($info)) {
                addToLogs($info, 'updated', $info['status']);
                updateHardware($info);   
            } 
            break;
            
        case 'uncatigorizeHardware':
            uncatigorizeHardware($info['hardware_id']);
            $actionSuccess = true;
            break;

        case 'addHardwareToCategory':;
            addHardwareToCatgory($info);
            break;

        case 'assignHardwareToEmployee':
            addToLogs($info, 'assigned', 'assigned');
            addAssignment($info);
            updateHardwareStatus($info['hardware_id'], 'assigned');
            break;

        case 'returnHardware':
            addToLogs($info, 'returned', 'available');
            updateAssignment($info['hardware_id']);
            updateHardwareStatus($info['hardware_id'], 'available');
            break;

    }
    }





$currentCategoryId = $_SESSION["categoryId"];
$currentCategoryName = $_SESSION["categoryName"];
$currentCategoryDescription = $_SESSION["categoryDescription"];
$inSreach = false;
if (isset($_GET["search"])){
    $search = $_GET["search"];
    $inSreach = true;
    $hardwares = searchHardware($search);
} else {
    $hardwares = getHardwaresByCategory($currentCategoryId);
}


$isUncategorized = ($currentCategoryId == 1) ? true : false;
$employees = getEmployees() ?: [];
$categories = getHardwareCategories() ?: [];

include 'app/view/hardwares/hardwareCategory.php';