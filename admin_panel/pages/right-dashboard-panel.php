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
                        if (isset($_SESSION["admin_name"])) {
                            echo $_SESSION["admin_name"];
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
                                            echo $_SESSION["admin_image"]  ?>" alt="admin.png" id="admin-img">
            </div>
        </div>
    </div>
    <!-- END of Top div  -->

    <!-- START of RECENT UPDATES section  -->
    <section id="recent-update">
        <h2>Top 3 Performing Waiters</h2>
        <div class="update-container">
            <?php
            $top_3_waiter = "SELECT users.name AS waiter_name, users.*, COUNT(orders.user_id) AS total_orders
               FROM orders
               INNER JOIN users ON orders.user_id = users.id 
               GROUP BY orders.user_id
               ORDER BY total_orders DESC
               LIMIT 3";

            $waiters_dataset = $connection->query($top_3_waiter);
            $results = $waiters_dataset->fetchAll(PDO::FETCH_ASSOC);
            $top = 1;

            foreach ($results as $row) {
            ?>
                <div class="update">
                    <div class="profile-photo">
                        <img src="../images/User/<?php echo  $row['image'] ?>" alt="<?php echo  $row['image'] ?>">
                    </div>
                    <div class="message">
                        <p><b><?php echo $row['waiter_name']; ?></b> has delivered <?php echo  $row['total_orders'];  ?> Orders</p>
                        <small class="text-muted">Top <?php echo $top; ?></small>
                    </div>
                </div>

            <?php
                $top++;
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
    $todayQuery = "SELECT SUM(total_price) AS today_revenue FROM orders WHERE order_datetime >= :today_start AND order_datetime <= :today_end";
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
    $yesterdayQuery = "SELECT SUM(total_price) AS yesterday_revenue FROM orders WHERE order_datetime >= :yesterday_start AND order_datetime <= :yesterday_end";
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

        <!-- START of add new product card  -->
        <a href="./add_menu_item.php">
            <div class="item-card add-product-card">
                <div>
                    <i class="fa-solid fa-plus"></i>
                    <h3>Add Menu Item</h3>
                </div>
            </div>
        </a>
        <!-- END of add new product card  -->

        <!-- START of add new user   -->
        <a href="./create_user.php">
            <div class="item-card create-user-card">
                <div>
                    <i class="fa-solid fa-plus"></i>
                    <h3>Create User Account</h3>
                </div>
            </div>
        </a>
        <!-- END of add new user  -->

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
 <h4 id="new-category-form-title">New Category</h4>
            <div>
                <label for="new-category">Category Name</label>
                <input type="text" id="new-category" name="category" class="category-category">
            </div>
            <div>
                <label for="new-category">Category Icon</label>
                <input type="text" id="category-icon" name="category-icon" class="updated_category" placeholder="Need to be <i> tag from https://fontawesome.com">
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