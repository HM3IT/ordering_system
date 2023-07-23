<?php
if (isset($_GET["view_order_id"])) {
    $order_id = $_GET["view_order_id"];

    $get_order_basedID_qry = "SELECT * FROM orders WHERE id = $order_id ";
    $order_set = $connection->query($get_order_basedID_qry);
    $order_row = $order_set->fetch();

    $order_submit_date = $order_row["order_date"];
    $customer_id = $order_row["customer_id"];
    $payment_method = $order_row["payment_method"];
    $delivery_status =   $order_row["delivery_status"];
    $transaction_slip = $order_row["transaction_slip"];

    $get_product_basedOrderID_qry = "SELECT * FROM order_product WHERE order_id = $order_id ";
    $product_set = $connection->query($get_product_basedOrderID_qry);
    $order_products = array();
    $index = 0;
    while ($product_row = $product_set->fetch()) {
        $productId = $product_row["product_id"];
        $num_ordered = $product_row["num_ordered"];
        $get_order_products = "SELECT * FROM product WHERE id =  $productId ";

        $product_dataset = $connection->query($get_order_products);
        $product_row = $product_dataset->fetch();
        $order_products[$index] = $product_row;
        $index++;
    }
}
?>
<section class="product-section">
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
            <h2 >Delivery status: 
            <span 
            <?php if($delivery_status === "PENDING"){
                echo "class = 'warning'";}else{
                    echo "class = 'success'"; 
                } ?>
                >
                <?php echo    $delivery_status  ?>
            </span>
            </h2>
        </div>
    </div>
    <div id="table-wrapper">
        <table id="all-product-table">

            <thead>
                <tr>
                    <th>No.</th>
                    <th>Image</th>
                    <th>Product ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Quantity</th>
                </tr>
            </thead>
            <?php
            $serial = 1;

            foreach ($order_products  as $row) {
                $id = $row["id"];

                $category_id =  $row["category_id"];
                $get_category_sql = "SELECT category_name FROM category WHERE id=$category_id";
                $get_product_images_sql = "SELECT primary_img FROM images WHERE product_id=$id";

                $dataset = $connection->query($get_category_sql);
                $data = $dataset->fetch();
                $category = $data["category_name"];

                $image_set = $connection->query($get_product_images_sql);
                $images = $image_set->fetch();
                $primary_img = $images["primary_img"];

            ?>
                <tr>
                    <td><?php echo  $serial++  ?></td>
                    <td>
                        <img src="../images/Products/<?php echo $category . '/' . $primary_img  ?>" alt="photo" class="product-tbl-img">
                    </td>
                    <td><?php echo  $id;  ?></td>
                    <td><?php echo $row["name"]; ?></td>
                    <td class="description text-justify"><?php echo $row["description"]  ?></td>
                    <td><?php echo $row["price"]  ?></td>
                    <td><?php echo  $num_ordered  ?></td>
                    <!-- <td>
           <?php
                //echo $category 
            ?>
        </td> -->
                </tr>
            <?php
            }
            ?>

        </table>
    </div>

    <?php
    if ($payment_method != "Cash on delivery") {

    ?>
        <div id="payment-check">
            <div>
                <img src="../images/Pay/transaction_slips/<?php echo $transaction_slip; ?>" alt="k-pay" id="k-pay-img">
            </div>
            <div>
                <form>
                    <label>Customer Name</label>
                    <input type="text" value="<?php
                                                $qry = "SELECT name FROM customer WHERE id = $customer_id";
                                                $customer_dataset = $connection->query($qry);
                                                $customer = $customer_dataset->fetch();

                                                echo $customer["name"] ?>" disabled>
                    <label>Order submitted date</label>
                    <input type="text" value="<?php echo $order_submit_date ?>" disabled>

                    <a href="./view_customer.php?view_customer_id=<?php echo  $customer_id; ?>" id="check-customer-btn">Check customer</a>

                </form>
            </div>
        </div>

    <?php

    }
    ?>
</section>
<script>
</script>