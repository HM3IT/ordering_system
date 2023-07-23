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
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>

    <?php require "./base_link_script.php";  ?>
    <!-- Integration -->
    <link href='https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css' rel='stylesheet' type='text/css'>

    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <!-- Integration -->

    <link rel="stylesheet" href="./css/user_tbl.css" />

</head>

<body>
    <div id="main-container">
        <?php
        require "./pages/sidebar.php";
 
        $get_all_user_sql = "SELECT * FROM users";

        ?>
        <section class="customer-section table-container-wrap">
            <h2>Customers</h2>
            <div id="table-wrapper">
                <table id='empTable' class="all-customer-table display dataTable">
                    <thead>
                        <th>Profile</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>User Level</th>
                        <th>Action</th>
                    </thead>
                </table>
            </div>
        </section>
        <script>
            $(document).ready(function() {
                $("#empTable").DataTable({
                    processing: true,
                    serverSide: true,
                    scrollY: '400px',
                    scrollCollapse: true,
                    serverMethod: "POST",
                    ajax: {
                        url: "./controller/user_tbl_controller.php",
                    },
                    columns: [{
                            data: "image"
                        },
                        {
                            data: "name"
                        },
                        {
                            data: "phone"
                        },
                        {
                            data: "email"
                        },
                        {
                            data: "user_level"
                        },
                        {
                            data: "action"
                        }
                    ]
                });
            });
        </script>

        <?php
        require "./pages/right-dashboard-panel.php";
        ?>
    </div>
    <script src="./scripts/sidebar.js"></script>
    <script src="./scripts/theme-togggler.js"></script>
</body>


</html>