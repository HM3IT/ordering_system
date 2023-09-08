<?php
require "../../dao/connection.php";

if (isset($_REQUEST["chart-bar-data"])) {

  // Get the current year, month, and week number
  $this_year = date('Y');
  $this_month = date('m');

  // Subtracting 1 to get the last month and week
  $last_month = date('m', strtotime('last month'));
  $last_week = date('W', strtotime('-1 week'));

  // Calculate the first day (Monday) of the last week
  $first_day_of_last_week = date('Y-m-d', strtotime("$this_year-W$last_week-1"));

  // Calculate the last day (Sunday) of the last week
  $last_day_of_last_week = date('Y-m-d', strtotime("$this_year-W$last_week-7"));

  // Prepare the query to retrieve order counts for each weekday in the last week
  $get_order_count_last_week = "
      SELECT DAYOFWEEK(order_datetime) AS weekday, COUNT(*) AS order_count, SUM(total_price) as total_sales
      FROM orders
      WHERE order_datetime >= :first_day
        AND order_datetime <= :last_day
      GROUP BY weekday
    ";

  // Prepare and execute the query using PDO
  $stmt = $connection->prepare($get_order_count_last_week);
  $stmt->bindValue(':first_day', $first_day_of_last_week, PDO::PARAM_STR);
  $stmt->bindValue(':last_day', $last_day_of_last_week, PDO::PARAM_STR);
  $stmt->execute();

  // Fetch the results into an associative array
  $order_counts = $stmt->fetchAll(PDO::FETCH_ASSOC);

  //  an array to hold the order counts for each weekday
  $weekday_order_counts = array_fill(1, 7, 0);
  $weekday_sales = array_fill(1, 7, 0);

  // Populate the array with the retrieved order counts
  foreach ($order_counts as $row) {
    $weekday = $row['weekday'];
    $order_count = $row['order_count'];
    $sales = $row["total_sales"];
    $weekday_order_counts[$weekday] = $order_count;
    $weekday_sales[$weekday] = $sales;
  }


  $get_this_month_sales = "SELECT DAY(order_datetime) AS day, SUM(total_price) AS total_sales FROM orders WHERE YEAR(order_datetime) = YEAR(CURRENT_DATE()) AND MONTH(order_datetime) = $this_month GROUP BY DAY(order_datetime)";
  $get_last_month_sales = "SELECT DAY(order_datetime) AS day, SUM(total_price) AS total_sales FROM orders WHERE YEAR(order_datetime) = YEAR(CURRENT_DATE() - INTERVAL 1 MONTH) AND MONTH(order_datetime) = $last_month GROUP BY DAY(order_datetime)";

  $stmt1 = $connection->query($get_this_month_sales);
  $this_month_sales = array_fill(1, 31, 0);

  while ($row = $stmt1->fetch(PDO::FETCH_ASSOC)) {
    $this_month_sales[$row['day']] = $row['total_sales'];
  }

  $stmt2 = $connection->query($get_last_month_sales);
  $last_month_sales = array_fill(1, 31, 0);

  while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)) {
    $last_month_sales[$row['day']] = $row['total_sales'];
  }


  $response = array(
    "orderCountData" => $weekday_order_counts,
    "weeklySales" =>  $weekday_sales,
    "thisMonthSales" => $this_month_sales,
    "lastMonthSales" => $last_month_sales,
    "message" => "Weekday order counts data, $weekday_order_counts[1] corresponds to Monday, ..."
  );
  echo json_encode($response);
}
