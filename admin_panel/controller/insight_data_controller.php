<?php
require "../../dao/connection.php";
$date = $_POST['date'];

// Extract the year, month, and day components from the selected date
$selectedYear = date('Y', strtotime($date));

$selectedMonth = date('m', strtotime($date));
$last_month = $selectedMonth - 1;

$selectedDay = date('d', strtotime($date));
$yesterday = $selectedDay - 1;

$daily_sales_qry = "SELECT COUNT(*) AS count, SUM(total_price) AS total FROM orders WHERE YEAR(order_datetime) = :year AND MONTH(order_datetime) = :month AND DAY(order_datetime) = :day";

$statement = $connection->prepare($daily_sales_qry);
$statement->execute([
  'year' => $selectedYear,
  'month' => $selectedMonth,
  'day' => $selectedDay
]);

$result = $statement->fetch(PDO::FETCH_ASSOC);

$this_day_sales = $result['total'];
$this_day_orders = $result['count'];

$statement->execute([
  'year' => $selectedYear,
  'month' => $selectedMonth,
  'day' => $yesterday
]);

$result = $statement->fetch(PDO::FETCH_ASSOC);

$yesterday_sales = $result['total'];
$yesterday_orders = $result['count'];


$monthly_sales_qry = "SELECT SUM(total_price) AS total FROM orders WHERE YEAR(order_datetime) = :year AND MONTH(order_datetime) = :month";
$statement2 = $connection->prepare($monthly_sales_qry);
$statement2->execute([
  'year' => $selectedYear,
  'month' => $selectedMonth
]);
$result2 = $statement2->fetch(PDO::FETCH_ASSOC);
$this_month_sales = $result2["total"];


$statement2->execute([
  'year' => $selectedYear,
  'month' => $last_month
]);
$result2 = $statement2->fetch(PDO::FETCH_ASSOC);
$last_month_sales = $result2["total"];


// Query to retrieve today's total revenue
$yearly_revenue_qry ="SELECT SUM(total_price) FROM orders  
                      WHERE YEAR(order_datetime) = :selected_year";

$yearly_revenue_dataset = $connection->prepare($yearly_revenue_qry);
$yearly_revenue_dataset->bindParam(':selected_year', $selectedYear);
$yearly_revenue_dataset->execute();
$this_year_revenue = $yearly_revenue_dataset->fetchColumn();

if (empty($this_year_revenue)) {
  $this_year_revenue = 0;
}

$response = array(
  'selected_year' => $selectedYear,
  'this_day_sales' => $this_day_sales,
  'yesterday_sales' => $yesterday_sales,

  'this_month_sales' => $this_month_sales,
  'last_month_sales' => $last_month_sales,

  'this_day_orders' => $this_day_orders,
  'yesterday_orders' => $yesterday_orders,

  'this_year_revenue' => $this_year_revenue
);

header('Content-Type: application/json');
echo json_encode($response);
