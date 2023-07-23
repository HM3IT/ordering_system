<?php
require "../dao/connection.php";
$get_all_product_sql = "SELECT * FROM product";
?>
<section class="product-section table-container-wrap">
    <h2>Products from inventory</h2>
    <div id="table-wrapper">
        <table id='empTable' class="all-product-table display dataTable">
            <thead>
                <th>Product ID</th>
                <th>Image</th>
                <th>Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>discount</th>
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
                url: "./controller/product_tbl_controller.php",
            },
            columns: [{
                    data: "product_id"
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
                    data: "discount"
                },
                {
                    data: "action"
                }
            ]

        });
    });
</script>