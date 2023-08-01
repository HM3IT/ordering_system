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
                <th class="tbl-col-hide">Customer</th>
                <th>Order Date</th>
                <th class="address">Shipping Address</th>
                <th>Total Price (Ks)</th>
                <th>Payment</th>
                <th>Approval</th>
                <th>Delivery Status</th>
                <th>Action</th>
            </thead>
        </table>
    </div>
</section>

<div id="confirm-status-overlay"></div>
<div id="confirm-status-box">
    <div>
        <i class="fa-solid fa-circle-info" id="information-icon"></i>
    </div>
    <h2 class="info">Are you sure to make changes</h2>
    <div>
        <button onclick="closeConfirmForm()" id="cancel-btn">Cancel</button>
        <button id="confirm-btn">Confirm</button>
    </div>
</div>

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
            columns: [{
                    data: "order_id"
                },
                {
                    data: "customer_name"
                },
                {
                    data: "order_date"
                },
                {
                    data: "ship_address"
                },
                {
                    data: "total_price"
                },
                {
                    data: "payment"
                },
                {
                    data: "order_approval"
                },
                {
                    data: "delivery_status"
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

    function updateOrderStatus(event, orderId) {
        event.preventDefault();

        const row = event.target.closest("tr");
        // Get the approval status and delivery status values from the row
        const approvalStatus = row.querySelector(".order-approval").value;
        const deliveryStatus = row.querySelector(".delivery-status").value;

        const payload = {
            order_id: orderId,
            order_approval: approvalStatus,
            delivery_status: deliveryStatus
        };

        confirmForm.querySelector("#confirm-btn").addEventListener("click", function() {
            document.getElementById("confirm-status-overlay").style.display = "none";
            document.getElementById("confirm-status-box").style.display = "none";

            const xhr = new XMLHttpRequest();
            xhr.open("POST", "./controller/order_status_controller.php", true);
            xhr.setRequestHeader("Content-Type", "application/json");
            xhr.onload = function() {
                if (xhr.status === 200) {
                    console.log("Success");
                    location.reload();

                }
            };
            xhr.send(JSON.stringify(payload));
        });
    }

    let overlay = document.getElementById("confirm-status-overlay");
    let confirmForm = document.getElementById("confirm-status-box");

    function confirmChanges(event, orderId) {
        overlay.style.display = "block";
        confirmForm.style.display = "block";
        confirmForm
            .querySelector("#confirm-btn")
            .addEventListener("click", function() {

                updateOrderStatus(event, orderId);
            });
    }

    function closeConfirmForm() {
        overlay.style.display = "none";
        confirmForm.style.display = "none";
    }
</script>