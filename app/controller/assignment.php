<?php

if (isset($_POST['action']) && $_POST['action'] == 'assign') {

    $employeeFullName = explode('|', $_POST['employee']);
    $employee = getEmployee($employeeFullName);

    $info = [
        'hardware_id' => $_POST['hardware_id'],
        'employee_id' => $employee['id'],
    ];

    updateHardwareStatus($_POST['hardware_id'], "assigned");
    addAssignment($info);

    header('Location: /hardwareTracker/hardwareCategory');
    exit();
}




if (isset($_POST['action']) && $_POST['action'] == 'return') {

    updateHardwareStatus($_POST['hardware_id'], 'available');
    updateAssignment($_POST['hardware_id']);

    header('Location: /hardwareTracker/hardwareCategory');
    exit();
}