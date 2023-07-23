<?php
session_start();

if (isset($_SESSION["cart"])) {
    foreach ($_SESSION["cart"] as $key => $value) {
        // Remove the product from the session
        unset($_SESSION["cart"][$key]);
    }
}
session_destroy();
header("Location: ./login.php");
