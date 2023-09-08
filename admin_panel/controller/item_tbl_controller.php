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
    $searchQuery = " AND (i.name LIKE '%" . $searchValue . "%' OR 
        i.added_date LIKE '%" . $searchValue . "%' OR 
        c.category_name LIKE '%" . $searchValue . "%')";
}

## Total number of records without filtering
$stmt = $connection->query("SELECT COUNT(*) AS allcount FROM item");
$records = $stmt->fetch(PDO::FETCH_ASSOC);
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $connection->query("SELECT COUNT(*) AS allcount FROM item i 
    INNER JOIN category c ON i.category_id = c.id
    WHERE 1" . $searchQuery);
$records = $stmt->fetch(PDO::FETCH_ASSOC);
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$query = "SELECT i.id as item_id, i.name, i.description, i.added_date, i.price, i.discount, i.quantity, 
    i.sold_quantity, c.category_name , im.primary_img 
    FROM item i
    INNER JOIN category c ON i.category_id = c.id
    INNER JOIN item_media im ON i.id = im.item_id
    WHERE 1" . $searchQuery . " ORDER BY " . $columnName . " " . $columnSortOrder . " LIMIT " . $row . "," . $rowperpage;

$stmt = $connection->query($query);
$data = array();

$serial = $row + 1;
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $item_id = $row['item_id'];
    $price = $row['price'] ;
    $data[] = array(
        "item_id" =>  $item_id,
        "image" => '<img src="../images/Menu_items/' . $row['category_name'] . '/' . $row['primary_img'] . '" alt="product-image" class="product-tbl-img">',
        "name" => $row['name'],
        // "description" => $row['description'],
        // "added_date" => $row['added_date'],
        // "sold_quantity" => $row['sold_quantity'],
        // "category" => $row['category_name'],
        "price" => $price ,
        "quantity" => $row['quantity'],
        "sold_quantity" => $row['sold_quantity'],
        // "discount" => $row['discount'],
        "action" => '<a href="./view_item.php?view_item_id=' .  $item_id . '" class="view-btn information-border">View</a>
        <a href="./update_item.php?update_item_id=' . $item_id . '" class="edit-btn warning-border">Edit</a>
        <a href="./controller/menu_item_controller.php?remove_item_id=' . $item_id . '" class="remove-btn danger-border">Remove</a>'
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
