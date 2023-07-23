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
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <?php require "./base_link_script.php";  ?>
    <!-- Integration -->
    <link href='https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css' rel='stylesheet' type='text/css'>

    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <link rel="stylesheet" href="./css/all_product_tbl.css" />
</head>

<body>
    <div id="main-container">
        <?php
        require "./pages/sidebar.php";
        require "./pages/all_product_tbl.php";
        require "./pages/right-dashboard-panel.php";
        ?>
    </div>
    <script src="./scripts/sidebar.js"></script>
    <script src="./scripts/theme-togggler.js"></script>
    <!-- <script src="./scripts/all_product_tbl.js"></script> -->
</body>


</html>