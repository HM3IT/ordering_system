<?php
require "../../dao/connection.php";

$draw = $_POST['draw'];
$row = $_POST['start'];

// Rows displayed per page
$rowperpage = $_POST['length'];
$columnIndex = $_POST['order'][0]['column'];

// Column name
$columnName = $_POST['columns'][$columnIndex]['data'];

// asc or desc
$columnSortOrder = $_POST['order'][0]['dir'];

// Search value
$searchValue = $_POST['search']['value'];

## Search
$searchQuery = "";
if ($searchValue != '') {
    $searchQuery = " AND (name LIKE '%" . $searchValue . "%' OR 
        phone LIKE '%" . $searchValue . "%' OR 
        email LIKE '%" . $searchValue . "%')";
}

## Total number of records without filtering
$stmt = $connection->query("SELECT COUNT(*) AS allcount FROM users WHERE user_status = 'Active'");
$records = $stmt->fetch(PDO::FETCH_ASSOC);
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $connection->query("SELECT COUNT(*) AS allcount FROM users WHERE user_status = 'Active'" . $searchQuery);
$records = $stmt->fetch(PDO::FETCH_ASSOC);
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$empQuery = "SELECT u.*, ul.user_level
             FROM users u
             LEFT JOIN user_levels ul ON u.user_level_id = ul.id
             WHERE user_status = 'Active'" . $searchQuery . "
             ORDER BY " . $columnName . " " . $columnSortOrder . "
             LIMIT " . $row . "," . $rowperpage;

$stmt = $connection->query($empQuery);
$data = array();


$serial = $row + 1;
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

    $data[] = array(
        "image" =>
        '<img src="../images/User/' . $row['image'] . '" alt="photo" class="customer-tbl-img">',
        "name" => $row['name'],
        "phone" => $row['phone'],
        "email" => $row['email'],
        "user_level" => $row['user_level'],
        "action" => '<a href="./view_user.php?view_user_id=' . $row['id'] . '" class="view-btn success-border">View</a> <a href="./update_user.php?update_user_id=' . $row['id'] . '" class="edit-btn information-border">Edit</a>
        <a href="./controller/user_account_controller.php?remove_user_id=' . $row['id'] . '" class="remove-btn danger-border">Remove</a>
        '
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
