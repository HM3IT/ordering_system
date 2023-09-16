<main>
  <div id="dashbaord-head">
    <h1>Dashboard</h1>
    <div class="date">
      <label for="dateInput">Choose date: </label>
      <input type="date" id="dateInput" max="<?php echo date('Y-m-d'); ?>" onchange="compareDates()" />
    </div>
  </div>

  <!-- START of insights section-->
  <section class="insights">

    <!-- START of daily sales-card -->
    <div class="card sales-card">
      <div class="card-content">
        <i class="fa-solid fa-chart-line"></i>
        <div class="card-info">

          <h3>Daily Sales</h3>
          <h2 id="daily-sales">350,00K ks</h2>
        </div>
      </div>
      <div class="performance-percent">
        <p class="text-muted"><span id="daily-sales-percent">81% </span> than yesterday</p>
      </div>

    </div>
    <!-- END of daily sales-card -->

    <!-- START of order-card -->
    <div class="card order-card">
      <div class="card-content">
        <i class="fa-solid fa-chart-simple"></i>
        <div class="card-info">
          <h3>Daily Total orders</h3>
          <h2 id="total-order">6</h2>
        </div>
      </div>
      <div class="performance-percent">
        <p class="text-muted"><span id="total-order-percent">44% </span> than yesterday</p>
      </div>
    </div>

    <!-- END of order-card -->

    <!-- START of monthly-sale-card -->
    <div class="card montly-sales-card">
      <div class="card-content">
        <i class="fa-solid fa-dollar-sign"></i>
        <div class="card-info">
          <h3>This Month Sales</h3>
          <h2 id="monthly-sales">350,00K ks</h2>
        </div>
      </div>
      <div class="performance-percent">
        <p class="text-muted"><span id="monthly-sales-percent">44% </span> than last month</p>
      </div>
    </div>
    <!-- END of monthly-sale-card -->

  </section>
  <section id="bar-chart-section">
    <div class="card">

      <div id="order-count-bar-chart">
        <canvas id="chart-bars" class="chart-canvas" height="170" style="width:380px"></canvas>
        <h2 id="no-order-info">No Order In the Last Week</h2>
      </div>
      <div class="performance-percent">
        <p class="text-muted"><i class="fa-regular fa-clock"></i>Last week order frequency</p>
      </div>

    </div>

    <div class="card">
      <div id="daily-sale-chart">
        <canvas id="line-chart" class="chart-canvas" height="170" style="width:380px"></canvas>
        <h2 id="no-sales-info">No Slaes In the Past Week</h2>
      </div>
      <div class="performance-percent">
        <p class="text-muted"><i class="fa-regular fa-clock"></i>Last week sales</p>
      </div>
    </div>
  </section>

  <!-- END of insights section --> 

  <section>
    <!-- Testing -->
    <div class="card">
      <div id="monthly-sale-chart">
        <canvas id="monthly-line-chart" class="chart-canvas" height="280"></canvas>
        <h2 id="no-sales-info">No Sales In the Past Week</h2>
      </div>
      <div class="performance-percent">
        <p class="text-muted"><i class="fa-regular fa-clock"></i>Monthly sales Comparison</p>
      </div>
    </div>

    <!-- Testing -->
  </section>

  <!-- START of top sale item table section-->
  <section class="top-sale-item">
    <h2>Top sales menu item</h2>
    <table id="top-sale-table">
      <thead>
        <tr>
          <th>Top</th>
          <th>Item ID</th>
          <th>Name</th>
          <th>Sales</th>
          <th>Sold quantity</th>
          <th>Instock quantity</th>
          <th>Action</th>
        </tr>
      </thead>

      <?php
      // $get_top_sale_item = "SELECT *FROM item
      // ORDER BY (item.sold_quantity * item.price) DESC
      // LIMIT 5";

      $get_top_sale_item =   
      "SELECT * 
      FROM (
          SELECT i.*, SUM(oi.num_ordered) AS total_sold_quantity
          FROM item AS i
          JOIN order_item AS oi ON i.id = oi.item_id
          JOIN orders AS o ON oi.order_id = o.id
          WHERE MONTH(o.order_datetime) = MONTH(CURDATE()) AND YEAR(o.order_datetime) = YEAR(CURDATE())
          GROUP BY i.id
      ) AS subquery
      ORDER BY (total_sold_quantity * price) DESC
      LIMIT 5;
      ";
      

      $top_sale_dataset = $connection->query($get_top_sale_item);
      $top_sales_data = $top_sale_dataset->fetchAll();

      $serial = 1;
      foreach ($top_sales_data as $row) {
        $item_id = $row["id"];
        $sold_quantity = $row["total_sold_quantity"];
        $instock_quantity = $row["quantity"];
        $price = $row["price"];
        $sales = $sold_quantity * $price;
        $discount = $row["discount"];

        if ($discount > 0) {
          // Calculate the discount price
          $sales = $sales - ($sales * $discount / 100);
        }

      ?>
        <tr>
          <td><?php echo  $serial++; ?></td>
          <td>IR <?php echo $item_id  ?></td>
          <td><?php echo $row["name"] ?></td>
          <td><?php echo $sales ?></td>
          <td><?php echo $sold_quantity  ?></td>
          <td <?php if ($instock_quantity < 5) {
                echo "class='danger'";
              } ?>><?php echo $instock_quantity  ?></td>
          <td class="information"><a href="./view_item.php?view_item_id=<?php echo $item_id  ?>">View</a></td>
          </td>
        </tr>

      <?php
      }
      ?>
      </tbody>
    </table>
  </section>
  <!-- END of recent-order table section-->
</main>
<script src="scripts/chart-data.js">

</script>