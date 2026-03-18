<?php
require_once 'app/config/database.php';
// include 'app/model/assignment.php';

function addHardware($info){
    try {
        $sql = 'INSERT INTO hardwares (name, category_id, serial_number, status, purchase_date, received_date, price) values(:name, :category_id, :serial_number, :status, :purchase_date, :received_date, :price)';
        $stmt = $GLOBALS['db']->prepare($sql);
       
        $stmt->execute([':name' => $info['name'], 
        ':category_id'   => $info['category_id'],
        ':serial_number' => $info['serial_number'],
        ':status'        => $info['status'],
        ':purchase_date' => $info['purchase_date'],
        ':received_date'=> $info['received_date'],
        ':price' => $info['price']]) ;

        if ($info['status'] == 'assigned') {

        $hardware = getHardware($info['hardware_id']);
        $assignment_info = [
            'hardware_id' => $hardware['id'],
            'employee_id' => $info['employee_id'],
        ];

        addAssignment($assignment_info);
    }
    } catch (Exception $e) {
        echo ''. $e->getMessage();
    }   
}

function getHardwaresByCategory($categoryId) {
    try {
        $sql = 'SELECT * FROM hardwares WHERE category_id = :id';
        $stmt = $GLOBALS['db']->prepare($sql);
        $stmt->execute([':id' => $categoryId]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        echo $e->getMessage();
        return [];
    }
}


function getHardware($serial_number) {
    try {
        $sql = 'SELECT id FROM hardwares WHERE serial_number = :serial_number';
        $stmt = $GLOBALS['db']->prepare($sql);
        $stmt->execute([':serial_number' => $serial_number]);
        $hardware = $stmt->fetch(PDO::FETCH_ASSOC);
        return $hardware;
    } catch (Exception $e) {
        echo ''. $e->getMessage();
    }
}


function uncatigorizeHardware($hardware_id){
    try {
        $sql = 'UPDATE hardwares SET category_id = 1 WHERE id = :hardware_id';
        $stmt = $GLOBALS['db']->prepare($sql); 
        $stmt-> execute([':hardware_id'=> $hardware_id]);
    } catch (Exception $e) {
        echo ''. $e->getMessage();
    } 
}


function updateHardwareStatus($hardware_id, $status) {
    try {
        $sql = 'UPDATE hardwares SET status = :status WHERE id = :hardware_id';
        $stmt = $GLOBALS['db']->prepare($sql); 
        $stmt-> execute(['status' => $status,':hardware_id'=> $hardware_id]);
    } catch (Exception $e) {
        echo ''. $e->getMessage();
    } 
}

function updateHardware($info) {
    try {
        $sql = 'UPDATE hardwares SET name = :name, serial_number = :serial_number, status = :status, purchase_date = :purchase_date, received_date = :received_date, price = :price WHERE id = :hardware_id';
        $stmt = $GLOBALS['db']->prepare($sql); 
        $stmt-> execute([':name'=> $info['name'],
        ':serial_number'=> $info['serial_number'], 
        ':status'       => $info['status'],
        ':purchase_date'=> $info['purchase_date'],
        ':received_date'=> $info['received_date'],
        ':price' => $info['price'],
        ':hardware_id'=> $info['hardware_id']
         ]);
    } catch (Exception $e) {
        echo ''. $e->getMessage();
    } 
}

function AddHardwareToCatgory($info) {
    try {
        $sql = 'UPDATE hardwares SET category_id = :category_id WHERE id = :hardware_id';
        $stmt = $GLOBALS['db']->prepare($sql);
        $stmt->execute([
            ':category_id' => $info['category_id'],
            ':hardware_id' => $info['hardware_id']
        ]);
    } catch (Exception $e) {
        echo ''. $e->getMessage();
    }
}
function UpdateHardwareToCatgory($info) {

}


function getAssignedEmployeeByHardware($hardwareId) {
    try {
        $sql = "
            SELECT E.*
            FROM assignments A
            JOIN employees E ON A.employee_id = E.id
            WHERE A.hardware_id = :hardware_id
              AND A.returned_at IS NULL
            LIMIT 1
        ";

        $stmt = $GLOBALS['db']->prepare($sql);
        $stmt->execute([':hardware_id' => $hardwareId]);

        return $stmt->fetch(PDO::FETCH_ASSOC);

    } catch (Exception $e) {
        echo $e->getMessage();
        return null;
    }
}