<!-- START of the right-side-panel -->
<section id="right-side-panel">
    <!-- START of Top div  -->
    <div class="top">
        <button id="menu-btn">
            <i class="fa-solid fa-bars"></i>
        </button>
        <div class="theme-toggler">
            <i class="fa-solid fa-sun" id="light-mode-toggle"></i>
            <i class="fa-solid fa-moon" id="dark-mode-toggle"></i>
        </div>
        <div class="profile">
            <div class="info">
                <p>Welcome, <b id="admin-name">
                        <?php
                        if (isset($_SESSION["name"])) {
                            echo $_SESSION["name"];
                        } else {
                            //  if the user is passed thorugh the login authentication, there have to be name varibale in session
                            echo '<script> 
                            alert("Invalid authentication"); 
                            // location.href = "./login.php"; 
                            </script>';
                        }
                        ?>
                    </b></p>
                <small class="text-muted">(Admin)</small>
            </div>
            <div class="profile-photo">
                <img src="../images/User/<?php
                                            if (session_status() == PHP_SESSION_NONE) {
                                                session_start();
                                            }
                                            echo $_SESSION["image"]  ?>" alt="admin.png" id="admin-img">
            </div>
        </div>
    </div>
    <!-- END of Top div  -->

    <!-- START of RECENT UPDATES section  -->
    <section id="recent-update">
        <h2>Recent updates</h2>
        <div class="update-container">
            <?php
            if (!isset($connection)) {
                require "../dao/connection.php";
            }
            $get_recent_received = "SELECT `orders`.*, customer.*
            FROM `orders`
            INNER JOIN customer ON `orders`.customer_id = customer.id
            WHERE `orders`.delivery_status = 'RECEIVED'
            ORDER BY `orders`.order_received_date DESC
            LIMIT 3";


            $stmt = $connection->prepare($get_recent_received);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($results as $row) {
            ?>
                <div class="update">
                    <div class="profile-photo">
                        <img src="../images/User/<?php echo  $row['image'] ?>" alt="<?php echo  $row['image'] ?>">
                    </div>
                    <div class="message">

                        <?php
                        $order_received_date = $row['order_received_date'];

                        $current_time = time();
                        $received_time = strtotime($order_received_date);

                        $time_elapsed = $current_time - $received_time;

                        if ($time_elapsed < 60) {
                            $elapsed_time = $time_elapsed . " " . ($time_elapsed > 1 ? "s" : "") . " ago";
                        } elseif ($time_elapsed >= 60 && $time_elapsed < 3600) {
                            $elapsed_minutes = floor($time_elapsed / 60);
                            $elapsed_time = $elapsed_minutes . " minute" . ($elapsed_minutes > 1 ? "s" : "") . " ago";
                        } elseif ($time_elapsed >= 3600 && $time_elapsed < 86400) {
                            $elapsed_hours = floor($time_elapsed / 3600);
                            $elapsed_time = $elapsed_hours . " hour" . ($elapsed_hours > 1 ? "s" : "") . " ago";
                        } elseif ($time_elapsed >= 86400) {
                            $elapsed_days = floor($time_elapsed / 86400);
                            $elapsed_time = $elapsed_days . " day" . ($elapsed_days > 1 ? "s" : "") . " ago";
                        }

                        ?>
                        <p><b><?php echo  $row['name'] ?></b> has received the order.</p>
                        <small class="text-muted"><?php echo  $elapsed_time ?></small>
                    </div>
                </div>

            <?php

            }

            ?>

        </div>
    </section>
    <!-- END of RECENT UPDATES section  -->

    <?php
    $today = date('Y-m-d');
    $yesterday = date('Y-m-d', strtotime('-1 day'));
    
    // Set the time component to 00:00:00 for today and yesterday
    $todayStart = $today . ' 00:00:00';
    $todayEnd = $today . ' 23:59:59';
    $yesterdayStart = $yesterday . ' 00:00:00';
    $yesterdayEnd = $yesterday . ' 23:59:59';
    
    // Query to retrieve today's total revenue
    $todayQuery = "SELECT SUM(total_price) AS today_revenue FROM orders WHERE order_date >= :today_start AND order_date <= :today_end";
    $todayStatement = $connection->prepare($todayQuery);
    $todayStatement->bindParam(':today_start', $todayStart);
    $todayStatement->bindParam(':today_end', $todayEnd);
    $todayStatement->execute();
    $todayData = $todayStatement->fetch(PDO::FETCH_ASSOC);
    $todayRevenue = $todayData['today_revenue'];
    
    if (empty($todayRevenue)) {
        $todayRevenue = 0;
    }
    
    // Query to retrieve yesterday's total revenue
    $yesterdayQuery = "SELECT SUM(total_price) AS yesterday_revenue FROM orders WHERE order_date >= :yesterday_start AND order_date <= :yesterday_end";
    $yesterdayStatement = $connection->prepare($yesterdayQuery);
    $yesterdayStatement->bindParam(':yesterday_start', $yesterdayStart);
    $yesterdayStatement->bindParam(':yesterday_end', $yesterdayEnd);
    $yesterdayStatement->execute();
    $yesterdayData = $yesterdayStatement->fetch(PDO::FETCH_ASSOC);
    $yesterdayRevenue = $yesterdayData['yesterday_revenue'];
    
    if (empty($yesterdayRevenue)) {
        $yesterdayRevenue = 0;
    }

    // Calculate the percentage difference
    $revenueDifference = $todayRevenue - $yesterdayRevenue;
    $revenuePercentage = 0;
    if ($yesterdayRevenue > 0) {
        $revenuePercentage = ($revenueDifference / $yesterdayRevenue) * 100;
    } 
    ?>

    <section id="sales-analytics">
        <h2>Performance</h2>

        <!-- START of the item-online card  -->
        <div class="item-card online-card">
            <div class="icon">
                <i class="fa-brands fa-shopify"></i>
            </div>
            <div class="info">
                <div>
                    <h3>ONLINE ORDERS</h3>
                    <small class="text-muted">Last 24 Hours</small>
                </div>
                <h5 class="<?php echo ($revenueDifference >= 0) ? 'success' : 'danger'; ?>">
                <?php 
                 echo ($revenueDifference >= 0) ? '+' : '-'; 
                 echo number_format(abs($revenuePercentage), 2); ?>%</h5>

                <h3><?php echo $todayRevenue; ?> ks</h3>
            </div>
        </div>
        <!-- END of the item-online card  -->

        <?php
        // Get the current date and yesterday's date
        $currentDate = date('Y-m-d');
        $yesterdayDate = date('Y-m-d', strtotime('-1 day'));

        // Query to fetch today's new customers
        $todayQuery = "SELECT COUNT(*) AS today_new_customers FROM customer WHERE DATE(FROM_UNIXTIME(created_date)) = :currentDate";

        // Query to fetch yesterday's new customers
        $yesterdayQuery = "SELECT COUNT(*) AS yesterday_new_customers FROM customer WHERE DATE(FROM_UNIXTIME(created_date)) = :yesterdayDate";

        $todayStatement = $connection->prepare($todayQuery);
        $todayStatement->bindValue(':currentDate', $currentDate);
        $todayStatement->execute();
        $todayResult = $todayStatement->fetch(PDO::FETCH_ASSOC);
        $todayNewCustomers = $todayResult['today_new_customers'];

        $yesterdayStatement = $connection->prepare($yesterdayQuery);
        $yesterdayStatement->bindValue(':yesterdayDate', $yesterdayDate);
        $yesterdayStatement->execute();
        $yesterdayResult = $yesterdayStatement->fetch(PDO::FETCH_ASSOC);
        $yesterdayNewCustomers = $yesterdayResult['yesterday_new_customers'];

        // Calculate the difference and percentage change
        $customerDifference = $todayNewCustomers - $yesterdayNewCustomers;
        $customerPercentage = 0;

        if ($yesterdayNewCustomers > 0) {
            $customerPercentage = ($customerDifference / $yesterdayNewCustomers) * 100;
        }
        ?>

        <!-- START of the new customer card  -->
        <div class="item-card new-customer-card">
            <div class="icon">
                <i class="fa-solid fa-user"></i>
            </div>
            <div class="info">
                <div>
                    <h3>NEW CUSTOMERS</h3>
                    <small class="text-muted">Last 24 Hours</small>
                </div>
                <h5 class="<?php echo ($customerDifference >= 0) ? 'success' : 'danger'; ?>">
                    <?php
                    echo ($customerDifference >= 0) ? '+' : '-'; 
                    echo number_format(abs($customerPercentage), 2); ?>%
                </h5>
                <h3><?php echo $todayNewCustomers; ?> customers</h3>
            </div>
        </div>

        <!-- END of the new customer card  -->

        <!-- START of add new product card  -->
        <a href="./add_product.php">
            <div class="item-card add-product-card">
                <div>
                    <i class="fa-solid fa-plus"></i>
                    <h3>Add Product</h3>
                </div>
            </div>
        </a>
        <!-- END of add new product card  -->


        <!-- START of add new product card  -->
        <button type="button" id="add-category-btn">
            <div class="item-card add-category-card">
                <div>
                    <i class="fa-solid fa-plus"></i>
                    <h3>Add category</h3>
                </div>
            </div>
        </button>
        <!-- END of add new product card  -->
    </section>
    <!-- END of the sales analytics section  -->
</section>
<!-- END of the right-side-panel -->
<div id="overlay"></div>
<div id="add-category-form">

    <div id="close-btn-relative">
        <i class="fa-solid fa-circle-xmark" id="close-add-category-form"></i>
        <i class="fa-solid fa-circle-info info-emoji"></i>

    </div>
    <div>
        <form action="./controller/category_controller.php" method="post">
            <div>
                <label for="new-category">New category Name</label>
                <input type="text" id="new-category" name="category" class="category-category">
            </div>
            <input type="submit" class="information-bg add-category" name="add-category" value="Submit">
        </form>
    </div>
</div>
<script>
    $(document).ready(function() {
        // Add category popup
        $("#add-category-btn").click(function() {
            $("#overlay").show();
            $("#add-category-form").show();
        });

        $("#close-add-category-form").click(function() {
            $("#overlay").hide();
            $("#add-category-form").hide();
        })
    });
</script>