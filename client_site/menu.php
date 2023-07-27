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
    <title>Ordering System</title>

    <?php require "./components/base-link.php" ?>
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/search-bar.css" />
    <link rel="stylesheet" href="css/product-section.css">
    <link rel="stylesheet" href="css/swiper.css">
    <link rel="stylesheet" href="css/product-slider.css">
    <link rel="stylesheet" href="css/alert-box.css" />
    <Style>
        #product-slider-section {
            display: none;
        }

        @media screen and (max-width: 780px) {
            .product-container {
                display: none;
            }

            .feature-section {
                display: none;
            }

            #product-slider-section {
                display: block;
            }

            .product-section h2,
            .product-section p {
                display: none;
            }
        }
    </Style>
</head>

<body>

    <div id="main-container">
        <?php
        require './components/sidebar.php';
        // default type
        $category_id = 4;
        if(isset($_GET["category-id"])){
            $category_id = $_GET["category-id"];
            $get_all_menu_item_sql = "SELECT * FROM item WHERE category_id =$category_id" ;
        }else{
            $get_all_menu_item_sql = "SELECT * FROM item" ;
        }
      
        $dataset = $connection->query($get_all_menu_item_sql);
        ?>
        <div>
            <?php

            require './components/navbar.php';
            require './components/product-section.php';
            ?>
        </div>
        <?php

        // require "./compoenets/right-dashboard-panel.php";
        ?>
    </div>
    <?php


    // require COMPONENTS_PATH . 'product-slider.php';
    // require 'components/alert-box.php';
    // require COMPONENTS_PATH . 'swiper.html';
    // require COMPONENTS_PATH . 'cart-list.php';
    // require COMPONENTS_PATH . 'footer.html';
    ?>

    <script src="scripts/navbar.js"> </script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
    <script src="scripts/swiper.js"> </script>
</body>

</html>