<?php


if (isset($_POST['employee_category_name'])) {

    $newName = $_POST['employee_category_name'];

    if (!empty($_SESSION['action'])) {

        $oldName = $_SESSION['employee_category'];

        $sql = "UPDATE employee_categories 
                SET name = :newname 
                WHERE name = :oldname";

        $stmt = $db->prepare($sql);

        $stmt->execute([
            ':newname' => $newName,
            ':oldname' => $oldName
        ]);

        unset($_SESSION['employee_category']);
        unset($_SESSION['action']);

    } else {

        $sql = "INSERT INTO employee_categories (name) 
                VALUES (:name)";

        $stmt = $db->prepare($sql);

        $stmt->execute([
            ':name' => $newName
        ]);
    }

    header('Location: /hardwareTracker/employees');
    exit();
}


if (isset($_POST["action"]) && $_POST["action"] == 'addToEmployeeCategory') {
    $employee_id = $_POST["employee_id"];
    $new_category_id = $_POST["new_category_id"];

    $sql = 'UPDATE employees 
            SET category_id = :category_id 
            WHERE id = :employee_id';

    $stmt = $db->prepare($sql);

    $stmt->execute([
        ':category_id' => $new_category_id,
        ':employee_id' => $employee_id
    ]);

    header('Location: /hardwareTracker/employeeCetegory');
    exit();
}

if (isset($_POST["action"]) && $_POST["action"] == "uncategorizeEmployee") {

    $employee_id = $_POST["employee_id"];

    $sql = "UPDATE employees 
            SET category_id = 1
            WHERE id = :id";

    $stmt = $db->prepare($sql);
    $stmt->execute([':id' => $employee_id]);

    header('Location: /hardwareTracker/employeeCategory');
    exit();
}