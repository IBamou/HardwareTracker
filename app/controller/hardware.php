<?php 
if (isset($_POST[''])) {

    $info = [
        'name'          => $_POST['hardware'],
        'category_id'   => $_POST['category_id'],
        'serial_number' => $_POST['serial_number'],
        'status'        => $_POST['status'],
        'purchase_date' => $_POST['purchase_date'],
        'received_date' => $_POST['received_date'],
        'hardware_id'   => $_POST['hardware_id'],
        'price'         => $_POST['price'],
    ];

    if (!empty($_SESSION['action'])) {

        updateHardware($info);

    } else {

        addHardware($info);
    }


    if ($_POST['status'] == 'assigned') {

        $hardware = getHardware($_POST['serial_number']);

        $employeeFullName = explode('|', $_POST['employee']);
        $employee = getEmployee($employeeFullName);

        $assignment = [
            'hardware_id' => $hardware['id'],
            'employee_id' => $employee['id'],
        ];

        addAssignment($assignment);
    }

    unset($_SESSION['action']);
        if ($_POST['categorized'] == 'false') {
                    header('Location: /hardwareTracker/hardwares');
        exit();
       } 

        header('Location: /hardwareTracker/hardwareCategory');
        exit();  
    
}
