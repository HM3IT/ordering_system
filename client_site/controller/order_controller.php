<?php
require "../../dao/connection.php";
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// insert order
if (isset($_REQUEST['order-now']) && isset($_SESSION["cart"])) {

    $tbl_num = $_POST["table-num"];
    $additional_request = "";
    if (!empty($_POST["additional-req"])) {
        $additional_request = $_POST["additional-req"];
    }

    $total_price = $_SESSION["total_cost"];
    $user_id =  $_SESSION["login_user_id"];
    $today =  date('Y-m-d H:i:s');
    $order_status = "Pending";

    $insert_order_qry = "INSERT INTO orders (order_datetime, total_price, order_status, additional_request, table_number, user_id) VALUES(?,?,?,?,?,?)";
    $statement1 = $connection->prepare($insert_order_qry);
    $statement1->execute(array($today, $total_price, $order_status, $additional_request, $tbl_num, $user_id));

    $order_id = $connection->lastInsertId();

    $insert_order_product_qry = "INSERT INTO order_item (order_id, item_id, num_ordered, subtotal) VALUES (?,?,?,?)";
    $statement2 = $connection->prepare($insert_order_product_qry);


    $get_item_quantity = "SELECT quantity, sold_quantity FROM item WHERE id = ?";

    $stmt = $connection->prepare($get_item_quantity);

    foreach ($_SESSION['cart'] as $key => $value) {
        $item_id = $value["id"];
        $bought_quantity = $value["Quantity"];
        $subtotal = $value["price"] * $bought_quantity;

        $statement2->execute(array($order_id, $item_id, $bought_quantity, $subtotal));
        $stmt->execute(array($item_id));

        $item_quantity_data = $stmt->fetch();
        $original_quantity = $item_quantity_data["quantity"];
        $sold_quantity = $item_quantity_data["sold_quantity"];

        $updated_quantity = $original_quantity - $bought_quantity;

        $sold_quantity = $sold_quantity + $bought_quantity;


        $update_item_quantity = "UPDATE item SET quantity = ?, sold_quantity = ? WHERE id = ?";
        $statement3 = $connection->prepare($update_item_quantity);
        $statement3->execute([$updated_quantity, $sold_quantity, $item_id]);

        // Remove the product from the session
        unset($_SESSION['cart'][$key]);
    }

    $response = array(
        'status' => 'success',
        'message' => 'Order has submitted '.$additional_request
    );
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// update order status

if (isset($_REQUEST['completed_order_id']) && isset($_SESSION["cart"])) {
    $order_id = $_REQUEST['completed_order_id'];
    $update_order_status = "UPDATE orders SET order_status = 'Completed' WHERE id=?";
    $update_status_stmt = $connection->prepare($update_order_status);
    $update_status_stmt->bindValue(1, $order_id);
    $update_status_stmt->execute();
    header("Location: ../pending_order_panel.php");
    exit;
}

if (isset($_REQUEST['archive_order_id']) && isset($_SESSION["cart"])) {
    $order_id = $_REQUEST['archive_order_id'];
    $update_order_status = "UPDATE orders SET order_status = 'Archive' WHERE id=?";
    $update_status_stmt = $connection->prepare($update_order_status);
    $update_status_stmt->bindValue(1, $order_id);
    $update_status_stmt->execute();
    header("Location: ../completed_order_panel.php");
    exit;
}
