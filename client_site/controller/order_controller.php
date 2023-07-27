<?php
require "../../dao/connection.php";
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $_SERVER['REQUEST_URI'])) {
    header("Location: ../404_page.php");
    exit();
}
$valid_file_extensions = array("png", "jpeg", "jpg", "svg", "jfif");

if (isset($_SESSION["cart"])) {

    $street = $_POST["street"];
    $township = $_POST["township"];
    $city = $_POST["city"];
    $zipcode =  $_POST["zipcode"];
    $total_price = $_SESSION["total_cost"];
    $customer_id =  $_SESSION["login_customer_id"];
   
    $today =  date('Y-m-d H:i:s');

    $ship_address = $street . ", " . $township . ", " . $city;

    $_SESSION["ship_address"] = $ship_address;

    $additional_request = "";
    if (!empty($_POST["add-req"])) {
        $additional_request = $_POST["add-req"];
    }

    $payment_method = $_POST["payment"];
  
 
    $transaction_img = "";
    if ($payment_method === "K-pay") {
        $image_file_name = $_FILES["transaction-img"]["name"];
        $image_file_size = $_FILES["transaction-img"]["size"];
        $image_file_tmp =  $_FILES["transaction-img"]["tmp_name"];
        $image_file_type = $_FILES["transaction-img"]["type"];
      
        $transaction_img = $image_file_name;
      
        $file_extension = strtolower(pathinfo($image_file_name, PATHINFO_EXTENSION));

        if (!in_array($file_extension, $valid_file_extensions)) {

            echo "<script> 
            alert('The image file " . $file_extension . " extension is not supported');
            location.href = '../checkout.php'; 
            </script>";
            return;
        }
        // allowed up to 3MB 
        if ($image_file_size >= 1024 * 1024 * 3) {

            echo "<script> 
                    alert('The image file size is too big');
                     location.href = '../checkout.php'; 
                    </script>";
            return;
        }
   
        // path: uploaded image files 
        $target_img_dir = "../../images/Pay/transaction_slips/";
        $target_file = $target_img_dir . basename($transaction_img);

        if (move_uploaded_file($image_file_tmp, $target_file)) {
        } else {
            echo "Target folder (image holder) is not found";
        }
    }
 
    $insert_order_qry = "INSERT INTO orders (order_date, total_price, customer_id, ship_address,additional_request,payment_method,transaction_slip) VALUES(?,?,?,?,?,?,?)";
    $statement1 = $connection->prepare($insert_order_qry);
    $statement1->execute(array($today, $total_price, $customer_id, $ship_address, $additional_request, $payment_method, $transaction_img));

    $order_id = $connection->lastInsertId();
    $_SESSION["recent_order_id"] = $order_id ;

    $insert_order_product_qry = "INSERT INTO order_product (order_id, product_id,num_ordered,quoted_price) VALUES (?,?,?,?)";
    $statement2 = $connection->prepare($insert_order_product_qry);


    $get_org_quantity = "SELECT quantity FROM product WHERE id = ?";

    $stmt = $connection->prepare($get_org_quantity);

    foreach ($_SESSION['cart'] as $key => $value) {
        $quantity = $value["Quantity"];
        $quoted_price = $value["price"] * $quantity;
        $statement2->execute(array($order_id, $value["id"], $quantity, $quoted_price));

        $product_id = $value["id"];

        $stmt->execute([$product_id]);
        $original_quantity = $stmt->fetchColumn();
        $updated_quantity = $original_quantity - $quantity;
        $update_product_quantity = "UPDATE product SET quantity = $updated_quantity WHERE id = $product_id";

        $connection->query($update_product_quantity);
    }
    header("Location: ../invoice.php");
    exit;
 
}
