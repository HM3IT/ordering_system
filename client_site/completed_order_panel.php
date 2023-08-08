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
    <style>
        .order-card-head {
            height: 120px;
        }


        .order-card-body {
            max-height: unset; 
             overflow-y: unset; 
        }
    </style>
</head>

<body>

    <div id="main-container">
        <?php
        require './components/alert-box.php';
        require './components/sidebar.php';
        ?>


        <div>
            <?php
            $order_card_per_page = 2;
            $page_num = 1;
            if (isset($_REQUEST["page-num"])) {
                $page_num = $_REQUEST["page-num"];
            }
            $offset = ($page_num - 1) * $order_card_per_page;

            $filter_type = 'Completed';
            if (isset($_REQUEST["order_status"])) {
                $filter_type = $_REQUEST["order_status"];
            }

            $get_order_per_page_qry;

            if (isset($_POST["filter-type"])) {
                $filter_type = $_POST['filter-type'];
                $_SESSION["filter-type"] = $filter_type;

                $get_order_per_page_qry = "SELECT * FROM orders WHERE order_status ='$filter_type' ORDER BY order_datetime ASC LIMIT $offset, $order_card_per_page";
            } else {
                if (isset($_SESSION["filter-type"])) {
                    $filter_type = $_SESSION["filter-type"];
                }
                $get_order_per_page_qry = "SELECT * FROM orders WHERE order_status ='$filter_type' LIMIT $offset, $order_card_per_page";
            }

            $dataset = $connection->query($get_order_per_page_qry);

            $rows = $dataset->fetchAll();
            ?>
            <div>
                <form method="POST" id="filter-select-form">
                    <label for="sort">Filter: </label>
                    <select name="filter-type" id="filter-select">
                        <option value="Completed" <?php echo ($filter_type  == "Completed") ? "selected" : ""; ?>>Completed</option>
                        <option value="Archive" <?php echo ($filter_type  == "Archive") ? "selected" : ""; ?>>Archive</option>
                    </select>
                </form>
            </div>
            <section id="order-display-panel">
                <?php
                $prefix_order_id = "IR ";
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
                                    <td><?php echo $prefix_order_id . ':' . $row["id"]; ?></td>
                                </tr>
                                <tr>
                                    <td>Table Number:</td>
                                    <td><?php echo $row["table_number"]; ?></td>
                                </tr>

                                <tr>
                                    <td>Due Time</td>
                                    <td><?php echo $formatted_date; ?></td>
                                </tr>
                                <tr>
                                    <td>Request</td>
                                    <td><span class="danger"><?php echo $row["additional_request"]; ?></span></td>
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
                                <a href="./controller/order_controller.php?archive_order_id=<?php echo $id; ?>" class="order-card-btns warning-bg">Remove</a>
                                <a href="./invoice.php?invoice_order_id=<?php echo $id; ?>" class="order-card-btns success-bg">Print Invoice</a>
                            </div>
                        </div>
                    </div>
                <?php

                }
                ?>

            </section>
            <div id="pagination">
                <?php
                $get_order_count_qry = "SELECT COUNT(*)  FROM orders WHERE order_status ='$filter_type'";
                $count = $connection->query($get_order_count_qry);
                $total_orders = $count->fetchColumn();
                ?>
                <a href="shop-page.php?page-num=<?php echo ($page_num - 1); ?>" class="page-link previous-page" <?php if ($page_num == 1) {
                                                                                                                    echo 'onclick="return false;"';
                                                                                                                } ?>>
                    <li class="page-item">Prev </li>
                </a>

                <?php
                $i = 1;

                $page_count = ceil($total_orders / $order_card_per_page);
                while ($i <= $page_count) {
                ?>

                    <?php
                    if ($i == $page_num) {
                    ?>
                        <a href="completed_order_page.php?page-num=<?php echo $i ?>" class="page-link current-page active">
                            <li class="page-item">
                                <?php echo $i  ?>
                            </li>
                        </a>
                    <?php
                        $i++;
                        continue;
                    }
                    ?>
                    <a href="completed_order_page.php?page-num=<?php echo $i ?>" class="page-link">
                        <li class="page-item current-page">
                            <?php echo $i  ?>
                        </li>
                    </a>

                <?php
                    $i++;
                }
                ?>

                <a href="completed_order_page.php?page-num=<?php echo ($page_num + 1) ?>" class="page-link" <?php if ($page_num == $page_count) {
                                                                                                                echo 'onclick="return false;"';
                                                                                                            } ?>>
                    <li class="page-item next-page">
                        Next
                    </li>
                </a>

            </div>


        </div>

        <script>
            $(document).ready(function() {
                $('#filter-select').change(function() {
                    $('#filter-select-form').submit();
                });
            });
        </script>
</body>

</html>