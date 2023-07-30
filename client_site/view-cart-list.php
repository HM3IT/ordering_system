<?php
if (!isset($_SESSION)) {
    session_start();
}

require "../dao/connection.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php require "./components/base-link.php" ?>
    <link rel="stylesheet" href="css/search-bar.css" />
    <link rel="stylesheet" href="css/cart-list-tbl.css">
    <!-- <link rel="stylesheet" href="css/check-out.css"> -->
    <link rel="stylesheet" href="css/alert-box.css" />
    <link rel="stylesheet" href="css/product-section.css">
 
</head>

<body>
    <div id="main-container">
        <?php
        require './components/sidebar.php';
        ?>
        <div>
            <?php
            require  './components/navbar.php';
            require  './components/cart-list-tbl.php';
            require 'components/alert-box.php';
            // if (isset($_SESSION['customer_name'])) {
            //     require './checkout.php';
            // }

            ?>
        </div>
    </div>
    <script src="scripts/navbar.js"> </script>
    <script src="scripts/cart-list.js"></script>
    <script src="scripts/cart-list-tbl.js"></script>

</body>

</html>