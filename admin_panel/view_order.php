<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (empty($_SESSION["status"])) {
    echo '
    <script> 
        alert("Please confirm the user authentication"); 
        location.href = "./login.php"; 
    </script>';
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
    <?php require "./base_link_script.php";  ?>

    <link rel="stylesheet" href="./css/product_form.css">
    <link rel="stylesheet" href="./css/all_product_tbl.css">
    <link rel="stylesheet" href="./css/order_detail_table.css">
</head>

<body>
    <div id="main-container">
        <?php
        require "./pages/sidebar.php";
        require "./pages/order_detail_tbl.php";
        require "./pages/right-dashboard-panel.php";
        ?>
    </div>
    <script src="./scripts/sidebar.js"></script>
    <script src="./scripts/theme-togggler.js"></script>
</body>

</html>