<?php
require "../../dao/connection.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// update-quantity based on the quantity from the cart-list
$rawData = file_get_contents('php://input');
$decodedData = json_decode($rawData, true);

if (isset($decodedData)) {
    $updatedItemStateAry = $decodedData;

    foreach ($updatedItemStateAry as $itemData) {

        $itemIndex = $itemData['itemIndex'];
        $itemID = $itemData['itemID'];     
        $quantity = $itemData['quantity'];
        $price = $itemData['price'];
     
        $get_instock_quantity_qry = "SELECT quantity FROM item WHERE id=$itemID";
        $dataset = $connection->query($get_instock_quantity_qry);
        $instock_quantity_row = $dataset->fetch();

        $instock_quantity = $instock_quantity_row["quantity"];

        if ($instock_quantity < $quantity) {
            $response = array(
                'success' => true,
                'out_of_stock' => true,
                'data' => $instock_quantity
            );
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }

        // Update the corresponding product quantity and price in the session
        $_SESSION['cart'][$itemIndex]["Quantity"] = $quantity;
    }

    $response = array(
        'success' => true,
        'out of stock' => false,
        'message' => 'Quantity is updated successfully',
    );

    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// remove item form the cart
if (isset($_POST["remove_item_id"])) {

    $itemID = $_POST["remove_item_id"];

    foreach ($_SESSION["cart"] as $key => $data) {

        if ($data["id"]  == $itemID) {
            unset($_SESSION["cart"][$key]);
            break;
        }
    }
    $response = array('success' => true, 'message' => $itemID . "count = " . count($_SESSION["cart"]));
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
