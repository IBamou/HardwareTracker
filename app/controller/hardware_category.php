<?php



if (isset($_POST['name'])) {

    $categoryName = $_POST['name'];
    $categoryDescription = $_POST['description'] ?? '';

    if (!empty($_SESSION['action'])) {

        $info = [
            "newname" => $categoryName,
            "oldname" => $_SESSION["category"],
            "newdescription" => $categoryDescription,
        ];

        updateCategory($info);

        unset($_SESSION['action']);
        unset($_SESSION['category']);

    } else {

        addCategory($categoryName, $categoryDescription);
    }

    header('Location: /hardwareTracker/hardwares');
    exit();
}


if (isset($_POST["action"]) && $_POST["action"] == 'addToCategory') {
    $hardware_id = $_POST["hardware_id"];
    $new_category_id = $_POST["new_category_id"];

    $sql = 'UPDATE hardwares 
            SET category_id = :category_id 
            WHERE id = :hardware_id';

    $stmt = $db->prepare($sql);

    $stmt->execute([
        ':category_id' => $new_category_id,
        ':hardware_id' => $hardware_id
    ]);

    header('Location: /hardwareTracker/hardwareCategory');
    exit();
}


 
if (isset($_POST["action"]) && $_POST["action"] == 'deleteCategory') { 
        $cateoryName = $_POST["category"]; 
        uncategorizeCategory($cateoryName); 
        $deleteSuccess = true; // Mark delete as successful
    } 


