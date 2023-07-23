<?php
$server = "localhost";
$username = "root";
$password = "";
$dbname = "ordering_system";
try {
    $connection = new PDO("mysql:host=$server; dbname=$dbname", $username, $password);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (Exception $e) {
    echo 'Connection failed';
    exit;
}
?>