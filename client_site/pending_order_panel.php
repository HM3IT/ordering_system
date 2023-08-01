<?php

if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_SESSION["login_user_id"])) {
    echo '
    <script> 
        alert("Please login the account first"); 
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
    <title>Ordering System</title>

    <?php require "./components/base-link.php" ?>
    <link rel="stylesheet" href="css/search-bar.css" />
    <link rel="stylesheet" href="css/alert-box.css" />
    <link rel="stylesheet" href="css/order-panel.css" />
    <link rel="stylesheet" href="css/pagination-sort.css" />
</head>

<body>

    <div id="main-container">
        <?php
        require './components/alert-box.php';
        require './components/sidebar.php';
        ?>
        <div>
            <section id="order-display-panel">
                <?php
                $order_card_per_page = 3;
                $page_num = 1;
                if (isset($_REQUEST["page-num"])) {
                    $page_num = $_REQUEST["page-num"];
                }
                $offset = ($page_num - 1) * $order_card_per_page;
                $prefix_order_id = "IR ";

                $get_all_order_sql = "SELECT * FROM orders WHERE order_status ='Pending' LIMIT $offset, $order_card_per_page";

                $dataset = $connection->query($get_all_order_sql);
                $rows = $dataset->fetchAll();
                foreach ($rows as $row) {
                    $id = $row["id"];
                    $date = new DateTime($row["order_datetime"]);

                    // Format the date as 'Y-F-d h:i a' to get '2023-August-01 03:05 pm'
                    $formatted_date = $date->format('Y-F-d h:i a');
                ?>
                    <div class="order-card">
                        <div class="order-card-head">
                            <table>
                                <tr>
                                    <td>Order Number:</td>
                                    <td><?php echo $id; ?></td>
                                </tr>
                                <tr>
                                    <td>Table Number:</td>
                                    <td><?php echo $row["table_number"]; ?></td>
                                </tr>
                                <tr>
                                    <td>Waiter:</td>
                                    <td><?php echo $row["user_id"]; ?></td>
                                </tr>
                                <tr>
                                    <td>Due Time</td>
                                    <td><?php echo $formatted_date; ?></td>
                                </tr>
                                <tr>
                                    <td>Request</td>
                                    <td><span class="danger"><?php echo $row["additional_request"]; ?></span></td>
                                </tr>
                                <tr>
                                    <td>Order Number</td>
                                    <td><?php echo $prefix_order_id . ':' . $row["id"]; ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="order-card-body">
                            <table>
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Food</th>
                                        <th>Quantity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $get_order_item_sql = "SELECT * FROM order_item 
                                                  INNER JOIN item ON order_item.item_id = item.id 
                                                  WHERE order_item.order_id = $id ";
                                    $dataset = $connection->query($get_order_item_sql);
                                    $rows = $dataset->fetchAll();
                                    $serial_num = 1;
                                    foreach ($rows as $row) {
                                    ?>
                                        <tr>
                                            <td>
                                                <li><?php echo  $serial_num; ?> </li>
                                            </td>
                                            <td><?php echo  $row['name']; ?> </td>
                                            <td><?php echo  $row['num_ordered']; ?> </td>
                                        </tr>
                                    <?php
                                        $serial_num++;
                                    }
                                    ?>
                                </tbody>
                            </table>
                            <div class="button-flex">
                                <a href="./controller/order_controller.php?completed_order_id=<?php echo $id; ?>" class="order-card-btns information-bg">Complete</a>
                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>
            </section>
            <div id="pagination">

                <a href="pending_order_panel.php?page-num=<?php echo ($page_num - 1); ?>" class="page-link previous-page" <?php if ($page_num == 1) {
                                                                                                                                echo 'onclick="return false;"';
                                                                                                                            } ?>>
                    <li class="page-item">Prev </li>
                </a>

                <?php
                $i = 1;
                $page_count = ceil($total_pending_orders / $order_card_per_page);
                while ($i <= $page_count) {
                ?>

                    <?php
                    if ($i == $page_num) {
                    ?>
                        <a href="pending_order_panel.php?page-num=<?php echo $i ?>" class="page-link current-page active">
                            <li class="page-item">
                                <?php echo $i  ?>
                            </li>
                        </a>
                    <?php
                        $i++;
                        continue;
                    }
                    ?>
                    <a href="pending_order_panel.php?page-num=<?php echo $i ?>" class="page-link">
                        <li class="page-item current-page">
                            <?php echo $i  ?>
                        </li>
                    </a>

                <?php
                    $i++;
                }
                ?>

                <a href="pending_order_panel.php?page-num=<?php echo ($page_num + 1) ?>" class="page-link" <?php if ($page_num == $page_count) {
                                                                                                                echo 'onclick="return false;"';
                                                                                                            } ?>>
                    <li class="page-item next-page">
                        Next
                    </li>
                </a>

            </div>
        </div>
    </div>
</body>

</html>