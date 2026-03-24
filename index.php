<?php
session_start();
require 'app/view/navigation.php';

// Get the requested URL path
$url = isset($_GET['url']) ? trim($_GET['url'], '/') : 'dashboard';

// Define available routes
$routes = [
    'hardwares' => 'app/controller/hardwareCategoriesController.php',
    'hardwareCategory' => 'app/controller/hardwareCategoryController.php',
    'employees' => 'app/controller/employeeCategoriesController.php',
    'employeeCategory' => 'app/controller/employeeCategoryController.php',
    'dashboard' => 'app/controller/dashboardController.php',
    'deviceLogs' => 'app/controller/deviceLogsController.php'
];

// Check if route exists
if (array_key_exists($url, $routes)) {
    include $routes[$url];
} else {
    http_response_code(404);
    echo "404 Not Found";
}
?>
