<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$get_low_quantity_item = "SELECT * FROM item WHERE quantity <= 10 ORDER BY quantity ASC LIMIT 4";
$low_quantity_dataset = $connection->query($get_low_quantity_item);
$low_instock_item_count = $low_quantity_dataset->rowCount();
$low_quantity_data = $low_quantity_dataset->fetchAll();
?>
<!-- START of the right-side-panel -->
<section id="right-side-panel">
    <!-- START of Top div  -->
    <div class="top">
        <button id="menu-btn">
            <i class="fa-solid fa-bars"></i>
        </button>
        <button id="noti-btn">
            <span id="noti-btn-text"><?php echo $low_instock_item_count; ?></span>
            <i class="fa-solid fa-bell"></i>
            <div id="low-quantity-noti">
                <div class="noti-header">
                    <div class="item-name">  Name  </div>
                    <div class="sold-quantity"> Sold  </div>
                    <div class="in-stock">  Instock   </div>
                </div>
                <?php

                foreach ($low_quantity_data as $data) {
                ?>
                    <a href="./view_item.php?view_item_id=<?php echo $data["id"]; ?>">
                        <div class="noti-card">
                            <div class="item-name">
                                <?php echo $data["name"]; ?>
                            </div>
                            <div class="sold-quantity">
                                <?php echo $data["sold_quantity"] ?>
                            </div>
                            <div class="in-stock">
                                <?php echo $data["quantity"] ?>
                            </div>
                        </div>
                    </a>
                <?php
                }
                ?>
            </div>
        </button>

        <div class="theme-toggler">
            <i class="fa-solid fa-sun" id="light-mode-toggle"></i>
            <i class="fa-solid fa-moon" id="dark-mode-toggle"></i>
        </div>


        <div class="profile">

            <p class="admin-info"> <span id="admin-name">
                    <?php echo $_SESSION["admin_name"];  ?>
                </span>
                <small class="text-muted">(Admin)</small>
            </p>
            <div class="profile-photo">
                <img src="../images/User/<?php echo $_SESSION["admin_image"] ?>" alt="admin.png" id="admin-img">
            </div>
        </div>
    </div>
    <!-- END of Top div  -->

    <!-- START of RECENT UPDATES section  -->
    <section id="top3-highly-performed-waiter">
        <h2>Top 3 Performing Waiters</h2>
        <div class="update-container">
            <?php


            $top_3_waiter =
                "SELECT users.name AS waiter_name, users.*, COUNT(orders.user_id) AS total_orders
                 FROM orders
                 INNER JOIN users ON orders.user_id = users.id 
                 WHERE MONTH(orders.order_datetime) = MONTH(CURRENT_DATE()) AND YEAR(orders.order_datetime) = YEAR(CURRENT_DATE())
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


    <section id="sales-analytics">

        <?php
        $current_page = $_SERVER['PHP_SELF']; // or $_SERVER['REQUEST_URI'];

        if (strpos($current_page, 'dashboard.php') !== false) {
        ?>
            <h2>Performance</h2>
            <!-- START TEST card  -->
            <div id="yearly-sales-kpi-card">
                <i class="fa-solid fa-magnifying-glass-dollar"></i>
                <div class="middle">
                    <div class="left">
                        <h3> <span id="kpi-year"> Revenue KPI</h3>
                        <h2 id="yearly-kpi-sales"> Ks</h2>
                    </div>
                    <div class="progress">
                        <svg>
                            <circle cx="38" cy="38" r="36"></circle>
                        </svg>
                        <div class="number">
                            <p id="yearly-kpi-sales-percent">%</p>
                        </div>
                    </div>
                </div>
                <small class="text-muted" id="target-kpi-sales"> </small>
            </div>
            <!-- END TEST card  -->
        <?php
        }
        ?>
        <?php
        $date = date('Y-m-d H:i:s');
        $thisYear = date('Y', strtotime($date));
        $thisMonth = date('m', strtotime($date));

        $monthly_order_qry = "SELECT COUNT(*) AS total_orders
                              FROM orders WHERE YEAR(order_datetime) = $thisYear
                              AND MONTH(order_datetime) = :selected_month";

        $monthly_orders = $connection->prepare($monthly_order_qry);
        $monthly_orders->bindParam(':selected_month', $thisMonth, PDO::PARAM_INT);
        $monthly_orders->execute();
        $this_month_order_dataset = $monthly_orders->fetch();
        $this_month_order_count = $this_month_order_dataset["total_orders"];


        // Calculate last month
        $lastMonth = date('m', strtotime('-1 month', strtotime($date)));

        $monthly_orders->bindParam(':selected_month', $lastMonth, PDO::PARAM_INT);
        $monthly_orders->execute();
        $last_month_order_dataset = $monthly_orders->fetch();
        $last_month_order_count = $last_month_order_dataset["total_orders"];

        $orderDifference = $this_month_order_count - $last_month_order_count;
        $orderDiffPercentage = 0;

        // Avoid division by zero
        if ($last_month_order_count !== 0) {
            $orderDiffPercentage = ($orderDifference / $last_month_order_count) * 100;
        }

        ?>
        <!-- START of the item-online card  -->

        <div class="item-card online-card">
            <div class="icon">
                <i class="fa-brands fa-shopify"></i>
            </div>
            <div class="info">
                <div>
                    <h3>Total Orders: <?php echo $this_month_order_count; ?></h3>
                    <small class="text-muted">This Month</small>
                </div>


            </div>
            <div style="text-align: center;">
                <h5 class="<?php echo ($orderDifference >= 0) ? 'success' : 'danger'; ?>">
                    <?php
                    echo ($orderDifference >= 0) ? '+' : '-';
                    echo number_format(abs($orderDiffPercentage), 2); ?>% </h5>
                <h4>than last month</h4>
            </div>
        </div>
        <!-- END of the item-online card  -->


        <?php
        $thisYear = date('Y');
        $total_expense = 12_50_0;
        $formatted_expense = number_format($total_expense);

        $current_employee_qry = "SELECT COUNT(*) FROM users WHERE user_status = 'Active'";
        $employee_count_dataset = $connection->query($current_employee_qry);
        $employee_count = $employee_count_dataset->fetchColumn();

        $signout_employee_qry = "SELECT COUNT(*) FROM users WHERE user_status = 'Delete' AND YEAR(created_date) = $thisYear";
        $signout_employee_count_dataset = $connection->query($signout_employee_qry);
        $signout_employee_count = $signout_employee_count_dataset->fetchColumn();
        $employee = $signout_employee_count;


        // Avoid division by zero
        $employee_turnover_rate = 0;
        if ($signout_employee_count != 0) {
            $average_employee_count = ($employee_count + $signout_employee_count) / 2;
            $employee_turnover_rate = ($signout_employee_count / $average_employee_count) * 100;
        }

        ?>

        <!-- START of the employee card  -->
        <div class="item-card user-acc-card">
            <div class="icon">
                <i class="fa-solid fa-user"></i>
            </div>
            <div class="info">
                <div>
                    <h3>Number of employees: <?php echo $employee_count; ?></h3>
                    <small class="text-muted">Total Expense: <?php echo  $formatted_expense; ?> Ks</small>
                </div>

            </div>
            <div style="text-align: center;">
                <h5 class="<?php echo ($employee_turnover_rate <= 50) ? 'success' : 'danger'; ?>">
                    Turnover rate:
                    <?php
                    echo ($employee_turnover_rate >= 0) ? '+' : '-';
                    echo number_format(abs($employee_turnover_rate), 2); ?>%

                </h5>
                <h6 class="danger">Left Employee: <?php echo $signout_employee_count; ?></h6>
            </div>
        </div>

        <!-- END of the employee card  -->


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
        let isNotiClose = true;
        // Add category popup
        $("#add-category-btn").click(function() {
            $("#overlay").show();
            $("#add-category-form").show();
        });

        $("#close-add-category-form").click(function() {
            $("#overlay").hide();
            $("#add-category-form").hide();
        });
        $("#noti-btn").click(function() {
            var $notification = $("#low-quantity-noti");
            let timeTaken = 600;

            if ($notification.is(":hidden")) {
                // Sidedown Animation
                $notification.slideDown(timeTaken);
            } else {
                $notification.slideUp(timeTaken, function() {
                    // Perform other actions if needed
                });
            }



        });


    });
</script>