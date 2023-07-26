<?php
$server = "localhost";
$username = "root";
$password = "";
$dbname = "e_commerce_website";
try {
    $conn = new PDO("mysql:host=$server; dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (Exception $e) {
    echo 'Connection failed';
    exit;
}
?>