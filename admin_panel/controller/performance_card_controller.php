<?php
require "../../dao/connection.php";
$date = $_POST['date'];

// Extract the year, month, and day components from the selected date
$selectedYear = date('Y', strtotime($date));
$selectedMonth = date('m', strtotime($date));
$selectedDay = date('d', strtotime($date));


$daily_sales_qry = "SELECT COUNT(*) AS count, SUM(total_price) AS total FROM orders WHERE YEAR(order_date) = :year AND MONTH(order_date) = :month AND DAY(order_date) = :day";

$statement = $connection->prepare($daily_sales_qry);
$statement->execute([
  'year' => $selectedYear,
  'month' => $selectedMonth,
  'day' => $selectedDay
]);

$result = $statement->fetch(PDO::FETCH_ASSOC);

$daily_sales = $result['total'];
$count = $result['count'];

$monthly_sales_qry = "SELECT SUM(total_price) AS total FROM orders WHERE YEAR(order_date) = :year AND MONTH(order_date) = :month";
$statement2 = $connection->prepare($monthly_sales_qry);
$statement2->execute([
  'year' => $selectedYear,
  'month' => $selectedMonth
]);
$result2 = $statement2->fetch(PDO::FETCH_ASSOC);
$monthly_sales = $result2["total"];

$response = array(
  'daily_sales' => $daily_sales,
  'count' => $count,
  'monthly_sales' => $monthly_sales
);

header('Content-Type: application/json');
echo json_encode($response);
