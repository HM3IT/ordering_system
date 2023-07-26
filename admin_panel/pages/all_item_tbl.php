<?php
require "../dao/connection.php";
$get_all_product_sql = "SELECT * FROM item";
?>
<section class="product-section table-container-wrap">
    <h2>Products from inventory</h2>
    <div id="table-wrapper">
        <table id='empTable' class="all-product-table display dataTable">
            <thead>
                <th>Item ID</th>
                <th>Picture</th>
                <th>Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Sold quantity</th>
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
                url: "./controller/item_tbl_controller.php",
            },
            columns: [{
                    data: "item_id"
                },
                {
                    data: "image"
                },
                {
                    data: "name"
                },
                {
                    data: "price"
                },
                {
                    data: "quantity"
                },
                {
                    data: "sold_quantity"
                },
                {
                    data: "action"
                }
            ]

        });
    });
</script>