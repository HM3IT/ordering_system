<?php
require "../dao/connection.php";
$get_all_user_sql = "SELECT * FROM customer";

?>
<section class="customer-section table-container-wrap">
    <h2>Customers</h2>
    <div id="table-wrapper">
        <table id='empTable' class="all-customer-table display dataTable">
            <thead>
                <!-- <th>No.</th> -->
                <th>Profile</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Email</th>
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
                url: "./controller/customer_tbl_controller.php",
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
                    data: "action"
                }
            ]
        });
    });
</script>