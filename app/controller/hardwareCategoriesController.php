<?php 
include 'app/model/hardwareCategoryModel.php';


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
            addHardwareCategory($info);
            break;

        
        case 'editHardwareCategory':
            updateHardwareCategory($info);
            break;

        case 'deleteHardwareCategory':
            $categoryId = $_POST["id"]; 
            uncategorizeHardwareCategory($categoryId); 
            $deleteSuccess = true;
    }
}



$title = 'Device Categories';
$Uncategorized_Category_id = 1;
$inSreach = false;

if (isset($_GET["search"]) && !empty($_GET["search"])){
    $search = $_GET["search"];
    $inSreach = true;
    $categories = searchHardwareCategory($search);
} else {
    $categories = getHardwareCategories();
}

include 'app/view/hardwares/hardwareCategories.php';