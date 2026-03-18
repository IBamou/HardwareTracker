<?php 
$host = 'localhost';
$dbname = 'hardware_tracking';
$dsn = "mysql:host=$host; dbname=$dbname";
$user = 'root';
$password = '';

//Connect To Database testdb
try {
    $db = new PDO($dsn, $user, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    echo ''. $e->getMessage();
}




































