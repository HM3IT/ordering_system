<?php
require "../../dao/connection.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// remove product form the cart
if (isset($_POST["remove_product_id"])) {

    $productID = $_POST["remove_product_id"];

    foreach ($_SESSION["cart"] as $key => $data) {

        if ($data["id"]  == $productID) {
            unset($_SESSION["cart"][$key]);
            break;
        }
    }
    $response = array('success' => true, 'message' => $productID . "count = " . count($_SESSION["cart"]));
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}


// update-quantity based on the quantity from the cart-list
// Read the raw POST data and decode it into a PHP array

$rawData = file_get_contents('php://input');
$decodedData = json_decode($rawData, true);

if (isset($decodedData)) {
    $updatedProductAry = $decodedData;

    foreach ($updatedProductAry as $productData) {

        $productID = $productData['productID'];     
        $productIndex = $productData['productIndex'];
        $quantity = $productData['quantity'];

        $get_instock_quantity_qry = "SELECT quantity FROM product WHERE id=$productID";
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

        // Update the corresponding product quantity in the session
        $_SESSION['cart'][$productIndex]["Quantity"] = $quantity;
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
