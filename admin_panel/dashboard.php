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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="./css/mid-dashboard-panel.css" />
    <link href="https://cdn.datatables.net/v/dt/dt-1.13.4/datatables.min.css" rel="stylesheet" />
</head>

<body>
    <div id="main-container">
        <?php
        require "./pages/sidebar.php";
        require "./pages/mid-dashboard-panel.php";
        require "./pages/right-dashboard-panel.php";
        ?>
    </div>
</body>
<script src="../assets/js/plugins/chartjs.min.js"></script>
<!-- <script src="../assets/js/core/popper.min.js"></script> -->
<script src="./scripts/performance_card.js"></script>
<script src="./scripts/sidebar.js"></script>
<script src="./scripts/theme-togggler.js"></script>

</html>