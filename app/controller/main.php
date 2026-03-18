<?php 

switch ($_POST['action']) {

        case 'editHardwareCategory':
            $isEditing = true;
            $categoryId = $_POST['id'] ?? '';
            $categoryName = $_POST['name'] ?? '';
            $categoryDescription = $_POST['description'] ?? '';
            include 'app/view/hardwares/hardware_category_form.php';
            exit();

        case 'editEmployeeCategory':
            $isEditing = true;
            $categoryId = $_POST['id'] ?? '';
            $categoryName = $_POST['name'] ?? '';
            $categoryDescription = $_POST['description'] ?? '';
            include 'app/view/employees/employee_category_form.php';
            exit();

        case 'editHardware':
            $hardwareName = $_POST['hardware_name'] ?? '';
            $serial_number = $_POST['serial_number'] ?? '';
            $status        = $_POST['hardware_status'] ?? '';
            $purchase_date = $_POST['purchase_date'] ?? '';
            $received_date = $_POST['received_date'] ?? '';
            $hardware_id   = $_POST['hardware_id'] ?? '';
            $price = $_POST['price'] ?? '';
            $employees = getEmployees();
            $isEditing = true;
            include 'app/view/hardwares/hardware_form.php';
            exit();

        
        case 'addHardwareCategory':
            unset($_SESSION['action']);
            unset($_SESSION['category']);
            unset($_SESSION['description']);
            include 'app/view/hardwares/hardware_category_form.php';
            exit();
// 
        case 'editEmployee':
            $employee_id = $_POST['employee_id'];
            $first_name = $_POST['firstName'];
            $last_name = $_POST['lastName'];
            $email = $_POST['email'];
            $departement = $_POST['departement'];
            $isEditing = true;
            include 'app/view/employees/employee_form.php';
            exit();

        case 'addEmployee':
            $category_id = $_POST['category_id'] ?? null;
            $iscategorized = $_POST['isuncategorized'];
            include 'app/view/employees/employee_form.php';
            exit();
// 
        case 'addEmployeeCategory':
            unset($_SESSION['action']);
            unset($_SESSION['category']);
            unset($_SESSION['description']);
            include 'app/view/employees/employee_category_form.php';
            exit();
        
        case 'addHardware':
            $category_id = $_POST['category_id'];
            $iscategorized = $_POST['isuncategorized'];
            include 'app/view/hardwares/hardware_form.php';
            exit();


        case 'ShowHardwareDetails':
            $hardwareName = $_POST['hardware_name'] ?? '';
            $serial_number = $_POST['serial_number'] ?? '';
            $status        = $_POST['hardware_status'] ?? '';
            $purchase_date = $_POST['purchase_date'] ?? '';
            $received_date = $_POST['received_date'] ?? '';
            $hardware_id   = $_POST['hardware_id'] ?? '';
            $price = $_POST['price'] ?? '';
            $assignToEmployee = getAssignedEmployeeByHardware($hardware_id);
            $Readonly = true;
            include 'app/view/hardwares/hardware_form.php';
            exit();

}


