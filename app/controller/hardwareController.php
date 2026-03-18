<?php 
session_start();
include 'app/model/hardware_category.php';


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

        case 'addHardwareCategory':
            addCategory($info);
            break;

        
        case 'editHardwareCategory':
            updateCategory($info);
            break;

        case 'deleteHardwareCategory':
            $categoryId = $_POST["id"]; 
            uncategorizeCategory($categoryId); 
            $deleteSuccess = true;
    }
    header('location: /hardwareTracker/hardwares');
    exit;
}










$title = 'Device Categories';
$Uncategorized_Category_id = 1;
$categories = getCategories();

include 'app/view/hardwares/hardwares.php';