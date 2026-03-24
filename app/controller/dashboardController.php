<?php
include 'app/model/hardwareModel.php';
include 'app/model/employeeModel.php';
include 'app/model/employeeCategoryModel.php';
include 'app/model/hardwareCategoryModel.php';

if (isset($_GET['type']) && $_GET['type']) {
    $type = $_GET['type'];
    if ($type == 'hardwares') {
        $page = 'app/view/hardwares/hardwareDashboard.php';
        if (isset($_GET['search']) && $_GET['search']) {
            $search = $_GET['search'];
            $hardwares = searchHardware($search);
        } else {
            $hardwares = getHardwaresForDashboard();
        }
    } elseif ($type == 'hardwareCategories') {
        if (isset($_GET['search']) && $_GET['search']) {
            $search = $_GET['search'];
            $hardwareCategories = searchHardwareCategory($search);
        } else {    
            $hardwareCategories = getHardwareCategories();
        }
    } elseif ($type == 'employees') {
        if (isset($_GET['search']) && $_GET['search']) {
            $search = $_GET['search'];
            $employees = searchEmployee($search);
        } else {
            $employees = getEmployees();
        }
    } elseif ($type == 'employeeCategories') {
        if (isset($_GET['search']) && $_GET['search']) {
            $search = $_GET['search'];
            $employeeCategories = searchEmployeeCategory($search);
        } else {
            $employeeCategories = getEmployeeCategories();
        } 
    }
} else {
    $type = 'hardwares';
    $page = 'app/view/hardwares/hardwareDashboard.php';
    $hardwares = getHardwaresForDashboard();
}


$placeholders = ['hardwares'         => 'search hardware...',
                 'hardwareCategories'  => 'search hardware category...',     
                 'employees'         => 'search employee...',
                 'employeeCategories'  => 'search employee category...'
                ];


$totalHardware = $db->query("SELECT COUNT(*) FROM hardwares")->fetchColumn();
$assignedHardware = $db->query("SELECT COUNT(DISTINCT hardware_id) FROM assignments WHERE returned_at IS NULL")->fetchColumn();
$unassignedHardware = $totalHardware - $assignedHardware;

// Calculate total prices
$priceStats = $db->query("SELECT 
    SUM(price) as total_price,
    SUM(CASE WHEN status = 'available' THEN price ELSE 0 END) as available_price,
    SUM(CASE WHEN status = 'assigned' THEN price ELSE 0 END) as in_use_price,
    SUM(CASE WHEN status = 'repair' THEN price ELSE 0 END) as maintenance_price
    FROM hardwares")->fetch(PDO::FETCH_ASSOC);

$totalPrice = $priceStats['total_price'] ?? 0;
$availablePrice = $priceStats['available_price'] ?? 0;
$inUsePrice = $priceStats['in_use_price'] ?? 0;
$maintenance_price = $priceStats['maintenance_price'] ?? 0;



// Get hardware by status
$statusStats = $db->query("SELECT status, COUNT(*) as count FROM hardwares GROUP BY status")->fetchAll(PDO::FETCH_ASSOC);



include 'app/view/dashboard.php';
