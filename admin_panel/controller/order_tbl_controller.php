<?php
require "../../dao/connection.php";

## Read value
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
    $searchQuery = " AND (orders.id LIKE '%" . $searchValue . "%' OR 
        users.name LIKE '%" . $searchValue . "%' OR 
        orders.order_datetime LIKE '%" . $searchValue . "%' OR
        orders.total_price LIKE '%" . $searchValue . "%' OR
        orders.order_status LIKE '%" . $searchValue . "%')";
}

## Total number of records without filtering
$stmt1 = $connection->prepare("SELECT COUNT(*) AS allcount FROM orders");
$stmt1->execute();
$totalRecords = $stmt1->fetchColumn();

## Total number of records with filtering
$stmt2 = $connection->prepare("SELECT COUNT(*) AS allcount FROM orders INNER JOIN users ON orders.user_id = users.id WHERE 1" . $searchQuery);
$stmt2->execute();
$totalRecordwithFilter = $stmt2->fetchColumn();

## Fetch records

$stmt3 = $connection->prepare("SELECT orders.id AS order_id, orders.*, users.name AS waiter_name FROM orders INNER JOIN users ON orders.user_id = users.id WHERE 1" . $searchQuery . " ORDER BY " . $columnName . " " . $columnSortOrder . " LIMIT " . $row . "," . $rowperpage);
$stmt3->execute();
$data = array();

while ($row = $stmt3->fetch(PDO::FETCH_ASSOC)) {

    $date = new DateTime($row["order_datetime"]);
    // Format the date as 'Y-F-d h:i a' to get '2023-August-01 03:05 pm'
    $formatted_date = $date->format('Y-F-d h:i a');

    $order_status = $row['order_status'];
    $class = 'order-status';

    if ($order_status === 'Pending') {
        $class .=' text-red';
    } elseif ($order_status === 'Completed') {
        $class .=' text-green';
    } elseif ($order_status === 'Archive') {
        $class .=' text-blue';
    }

    $data[] = array(
        "order_id" => $row['order_id'],
        "waiter_name" => $row['waiter_name'],
        "order_datetime" => $formatted_date,
        "total_price" => $row['total_price'],
        "order_status" => '<div class="' . $class . '">' . $row['order_status'] . '</div>',
        "action" => '
            <a href="./view_order.php?view_order_id=' . $row['order_id'] . '" class="view-btn information-border">View Details</a>'
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
