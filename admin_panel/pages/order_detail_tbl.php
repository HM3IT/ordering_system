<?php
if (isset($_GET["view_order_id"])) {
    $order_id = $_GET["view_order_id"];

    $get_order_basedID_qry = "SELECT * FROM orders WHERE id = $order_id ";
    $order_set = $connection->query($get_order_basedID_qry);
    $order_row = $order_set->fetch();

    $user_id = $order_row["user_id"];
    $delivery_status =   $order_row["order_status"];
    $order_submit_date = $order_row["order_datetime"];

    $get_item_based_orderID_qry = "SELECT * FROM order_item WHERE order_id = $order_id ";
    $order_item_dataset = $connection->query($get_item_based_orderID_qry);
   $order_items = $order_item_dataset->fetchAll();
}
?>
<section class="product-section">
    <div>
        <a id="back-btn" href="./order_manager.php"> <i class="fa-solid fa-backward-step"></i></a>
    </div>
    <div id="order-information">
        <div>
            <h2>Order Submit Date: <?php
                                    $formatted_date = date('Y-m-d h:i A', strtotime($order_submit_date));
                                    echo     $formatted_date  ?></h2>
        </div>
        <div>
            <h2>Order ID: <?php echo  $order_id  ?></h2>
        </div>
        <div>
            <h2>Order status:
                <span <?php if ($delivery_status === "Pending") {
                            echo "class = 'warning'";
                        } else {
                            echo "class = 'success'";
                        } ?>>
                    <?php echo    $delivery_status  ?>
                </span>
            </h2>
        </div>
    </div>
    <div id="table-wrapper">
        <table id="all-product-table">

            <thead id="order-detail-table-th">
                <tr>
                    <th>No.</th>
                    <th>Image</th>
                    <th>Item ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Action</th>
                </tr>
            </thead>
            <?php
            $serial = 1;

            foreach ($order_items as $item_row ) {
                $item_id = $item_row["item_id"];
                $num_ordered = $item_row["num_ordered"];

                $get_order_items = "
                SELECT i.*, c.category_name, im.primary_img 
                FROM item i
                INNER JOIN category c ON i.category_id = c.id
                LEFT JOIN item_media im ON i.id = im.item_id
                WHERE i.id = $item_id";


                $item_dataset = $connection->query($get_order_items);
                $item_row = $item_dataset->fetch();

                $category_id =  $item_row["category_id"];
                $category = $item_row["category_name"];
                $primary_img = $item_row["primary_img"];

            ?>
                <tr>
                    <td><?php echo  $serial++  ?></td>
                    <td>
                        <img src="../images/Menu_items/<?php echo $category . '/' . $primary_img  ?>" alt="photo" class="product-tbl-img">
                    </td>
                    <td><?php echo  $item_id;  ?></td>
                    <td><?php echo $item_row["name"]; ?></td>
                    <td><?php echo $item_row["price"]  ?></td>
                    <td><?php echo  $num_ordered  ?></td>
                    <td> 
                        <a href="view_item.php?view_item_id= <?php echo $item_id ?>" class="view-btn information-bg">View Detail</a> 
                       </td>
                </tr>
            <?php
            }
            ?>

        </table>
    </div>
</section>