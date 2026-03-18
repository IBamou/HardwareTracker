<?php

if (isset($_POST['first_name'])) {

    $info = [
        'first_name'  => $_POST['first_name'],
        'last_name'   => $_POST['last_name'],
        'email'       => $_POST['email'],
        'departement' => $_POST['departement'],
        'category_id' => $_POST['category_id'],
        'employee_id'=> $_POST['employee_id'],

    ];
    if (!empty($_SESSION['action'])) {

        updateEmployee($info);

        unset($_SESSION['action']);
        unset($_SESSION['category']);

    } else {

            addEmployee($info);
    }

    if ($_POST['categorized'] == 'false') {
        header('Location: /hardwareTracker/employees');
        exit();
    }
            header('Location: /hardwareTracker/employeeCategory');
        exit();  

}

