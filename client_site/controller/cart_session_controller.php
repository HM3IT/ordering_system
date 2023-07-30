<?php
require "../../dao/connection.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$item_id = $_POST['id'];
$name = $_POST["name"];
$image_name = $_POST["primary_img"];
$category = $_POST["category"];
$price = $_POST["price"];
$description = $_POST["description"];

$get_instock_quantity_qry = "SELECT quantity FROM item WHERE id=$item_id";
$dataset = $connection->query($get_instock_quantity_qry);
$instock_quantity_row = $dataset->fetch();
$instock_quantity = $instock_quantity_row["quantity"];

if (!isset($_SESSION["cart"])) {
    // New session and new product
    $_SESSION["cart"][0] = array(
        "id" => $item_id,
        "name" => $name,
        "price" => $price,
        "category" => $category,
        "description" => $description,
        "image" => $image_name,
        "Quantity" => 1

    );
} else {
    $isExistingProduct = false;

    // old product checking
    foreach ($_SESSION["cart"] as $key => $value) {
        if ($item_id == $value["id"]) {
            $isExistingProduct = true;

            if ($instock_quantity <= $_SESSION["cart"][$key]["Quantity"]) {
                $response = array(
                    'status' => 'success',
                    'out_of_stock' => true,
                    'exceed_quantity' => true,
                    'message' => $instock_quantity
                );
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }

            if ($_SESSION["cart"][$key]["Quantity"] == 5) {
                $response = array(
                    'status' => 'success',
                    'out_of_stock' => false,
                    'exceed_quantity' => true,
                    'message' => 'Item ' . $item_id . ' added to cart successfully'
                );
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }
            $_SESSION["cart"][$key]["Quantity"]++;
            break;
        }
    }

    if (!$isExistingProduct) {
        // New product
        $count = count($_SESSION["cart"]);
        $_SESSION["cart"][$count] = array(
            "id" => $item_id,
            "name" => $name,
            "price" => $price,
            "category" => $category,
            "description" => $description,
            "image" => $image_name,
            "Quantity" => 1
        );
    }
}

$response = array(
    'status' => 'success',
    'out_of_stock' => false,
    'exceed_quantity' => false,
    'message' => 'Item ' . $item_id . ' added to cart successfully'
);
header('Content-Type: application/json');
echo json_encode($response);
exit;