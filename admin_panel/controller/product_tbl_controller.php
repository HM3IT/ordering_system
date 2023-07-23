<?php
require "../../dao/connection.php";

$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows displayed per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value

## Search
$searchQuery = "";
if ($searchValue != '') {
    $searchQuery = " AND (p.name LIKE '%" . $searchValue . "%' OR 
        p.added_date LIKE '%" . $searchValue . "%' OR 
        c.category_name LIKE '%" . $searchValue . "%')";
}

## Total number of records without filtering
$stmt = $connection->query("SELECT COUNT(*) AS allcount FROM product");
$records = $stmt->fetch(PDO::FETCH_ASSOC);
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $connection->query("SELECT COUNT(*) AS allcount FROM product p 
    INNER JOIN category c ON p.category_id = c.id
    WHERE 1" . $searchQuery);
$records = $stmt->fetch(PDO::FETCH_ASSOC);
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$query = "SELECT p.id as product_id, p.name, p.description, p.added_date, p.price, p.discount, p.quantity, 
    p.sold_quantity, c.category_name , i.primary_img 
    FROM product p
    INNER JOIN category c ON p.category_id = c.id
    INNER JOIN images i ON p.id = i.product_id
    WHERE 1" . $searchQuery . " ORDER BY " . $columnName . " " . $columnSortOrder . " LIMIT " . $row . "," . $rowperpage;

$stmt = $connection->query($query);
$data = array();

$serial = $row + 1;
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $data[] = array(
        "product_id" => $row["product_id"],
        "image" => '<img src="../images/Products/' . $row['category_name'] . '/' . $row['primary_img'] . '" alt="product-image" class="product-tbl-img">',
        "name" => $row['name'],
        // "description" => $row['description'],
        // "added_date" => $row['added_date'],
        // "sold_quantity" => $row['sold_quantity'],
        // "category" => $row['category_name'],
        "price" => $row['price'],
        "quantity" => $row['quantity'],
        "discount" => $row['discount'],
        "action" => '<a href="./update_product.php?update_product_id=' . $row['product_id'] . '" class="edit-btn warning-border">Edit</a><a href="./controller/product_controller.php?remove_product_id=' . $row['product_id'] . '" class="remove-btn danger-border">Remove</a>'
    );
}


## Response
$response = array(
    "draw" => intval($draw),
    "iTotalRecords" => $totalRecords,
    "iTotalDisplayRecords" => $totalRecordwithFilter,
    "aaData" => $data
);

echo json_encode($response);
