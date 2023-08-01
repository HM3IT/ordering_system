<?php
require "../dao/connection.php";
$get_all_order_sql = "SELECT * FROM orders";
$all_product_dataset = $connection->query($get_all_order_sql);

$row_count = $all_product_dataset->rowCount();
?>
<section class="product-section table-container-wrap">
    <h2>Number of Inquiries: <?php echo  $row_count  ?></h2>
    <div id="table-wrapper">
        <table id='empTable' class="all-product-table display dataTable">
            <thead>
                <th>Order ID</th>
                <th class="tbl-col-hide">Waiter</th>
                <th>Order Date</th>
                <th>Total Price (Ks)</th>
                <th>Order Status</th>
                <th>Action</th>
            </thead>
        </table>
    </div>
</section>

<script>
    $(document).ready(function() {
        let windowWidth = $(window).width();
        $(window).resize(function() {
            windowWidth = $(window).width();
        });
      let ord_tbl =  $("#empTable").DataTable({
            processing: true,
            serverSide: true,
            scrollY: '400px',
            scrollCollapse: true,
            serverMethod: "post",
            ajax: {
                url: "./controller/order_tbl_controller.php",
            },
            // order data
            columns: [
                {
                    data: "order_id"
                },
                {
                    data: "waiter_name"
                },
                {
                    data: "order_datetime"
                },
                {
                    data: "total_price"
                },
                {
                    data: "order_status"
                },
                {
                    data: "action"
                }
            ],
        });
        if (windowWidth <= 780) {
            // Hide the "order_approval" column
            ord_tbl.column(1).visible(false);
        }

    });
</script>