<?php
require "../dao/connection.php";
?>
<?php
if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_REQUEST["collection-type"]) && !isset($_REQUEST["brand"])) {
    echo "<script>
    alert('Please use the navigation button in order to load the webpage properly');
    location.href ='./index.php';
</script>";
}
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
    <link rel="stylesheet" href="css/swiper.css">
    <link rel="stylesheet" href="css/product-section.css">
    <link rel="stylesheet" href="css/star-scale-rating.css">
</head>

<body>
    <?php

    define('COMPONENTS_PATH', './pages/');
    ?>
    <div id="main-container">

        <?php
        require COMPONENTS_PATH . 'navbar.php';
        require COMPONENTS_PATH . 'search-bar.php';
        $dataset;
        if (isset($_REQUEST["brand"])) {
            $category_id = $_REQUEST["brand"];
            $get_products_based_categery = "SELECT * FROM product WHERE category_id = $category_id";
            $dataset = $connection->query($get_products_based_categery);
        }

        if (isset($_REQUEST["collection-type"])) {
            $collection_type = $_REQUEST["collection-type"];
            if ($collection_type === "discount") {
                $get_discount_product = "SELECT * FROM product WHERE discount != 0 ORDER BY discount DESC";
                $dataset = $connection->query($get_discount_product);
            } else if ($collection_type === "trend") {
                $get_trend_product = "SELECT * FROM product ORDER BY sold_quantity DESC";
                $dataset = $connection->query($get_trend_product);
            } else if ($collection_type === "recent") {
                $get_recent_product = "SELECT * FROM product ORDER BY added_date DESC";
                $dataset = $connection->query($get_recent_product);
            } else if ($collection_type === "keycap") {
                $get_key_cap_id_qry = "SELECT id FROM category WHERE category_name ='Keycaps'";
                $keycap_row = $connection->query($get_key_cap_id_qry);
                $get_key_cap_id = $keycap_row->fetchColumn();

                $get_keycap_product = "SELECT * FROM product WHERE category_id =  $get_key_cap_id ";
                $dataset = $connection->query($get_keycap_product);
            } 
        }
        if (!isset($dataset) || empty($dataset)) {
            echo "<script>
            alert('The request query is not valid');
            location.href ='./index.php';
        </script>";
        }
        include COMPONENTS_PATH . 'product-section.php';
        include COMPONENTS_PATH . 'cart-list.php';
        require COMPONENTS_PATH . 'swiper.html';
        require COMPONENTS_PATH . 'footer.html';
        ?>
    </div>
    <script src="scripts/navbar.js"> </script>
    <script src="scripts/search-bar.js"></script>
    <script src="scripts/redirect.js"> </script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
    <script src="scripts/swiper.js"> </script>
</body>

</html>