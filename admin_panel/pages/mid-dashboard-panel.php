<main>
  <h1>Dashboard</h1>
  <div class="date">
    <input type="date" id="dateInput" max="<?php echo date('Y-m-d'); ?>" onchange="compareDates()" />
  </div>


  <!-- START of insights section-->
  <section class="insights">
    <!-- START of sales-card -->
    <div class="card sales-card">
      <i class="fa-solid fa-chart-line"></i>
      <div class="middle">
        <div class="left">
          <h3>Daily Sales</h3>
          <h2 id="daily-sales">350,00K ks</h2>
        </div>
        <div class="progress">
          <svg>
            <circle cx="38" cy="38" r="36"></circle>
          </svg>
          <div class="number">
            <p id="daily-sales-percent">81%</p>
          </div>
        </div>
      </div>
      <small class="text-muted">Sales contribution to target sales </small>
    </div>
    <!-- END of sales-card -->

    <!-- START of order-card -->
    <div class="card order-card">
      <i class="fa-solid fa-chart-simple"></i>
      <div class="middle">
        <div class="left">
          <h3>Daily Total orders</h3>
          <h2 id="total-order">6</h2>
        </div>
        <div class="progress">
          <svg>
            <circle cx="38" cy="38" r="36"></circle>
          </svg>
          <div class="number">
            <p id="total-order-percent">44%</p>
          </div>
        </div>
      </div>
      <small class="text-muted" id="target-order"> Last 24 Hours </small>
    </div>
    <!-- END of order-card -->

    <!-- START of income-card -->
    <div class="card montly-sales-card">
      <i class="fa-solid fa-magnifying-glass-dollar"></i>
      <div class="middle">
        <div class="left">
          <h3>Monly Sales KPI</h3>
          <h2 id="monthly-sales-kpi">350,00K ks</h2>
        </div>
        <div class="progress">
          <svg>
            <circle cx="38" cy="38" r="36"></circle>
          </svg>
          <div class="number">
            <p id="monthly-sales-percent">60%</p>
          </div>
        </div>
      </div>
      <small class="text-muted" id="target-sales"> Monthly performance insights</small>
    </div>
    <!-- END of income-card -->
  </section>

  <!-- END of insights section -->

  <!-- START of top sale item table section-->
  <section class="recent-order">
    <h2>Top sales menu item</h2>
    <table id="recent-order-table">
      <thead>
        <tr>
          <th>Top</th>
          <th>Item ID</th>
          <th>Name</th>
          <th>Sales</th>
          <th>Sold quantity</th>
          <th>Action</th>
        </tr>
      </thead>
      <?php
     $get_top_sale_item = "SELECT *
     FROM item
     INNER JOIN item_media ON item.id = item_media.item_id 
     ORDER BY (item.sold_quantity * item.price) DESC
     LIMIT 5";

      $top_sale_dataset = $connection->query($get_top_sale_item);
      $top_sales_data = $top_sale_dataset->fetchAll();

      $serial = 1;
      foreach ($top_sales_data as $row) {
        $item_id = $row["item_id"];
        $sold_quantity = $row["sold_quantity"];
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
          <td class="warning"><?php echo $sales ?></td>
          <td class="primary"><?php echo $sold_quantity  ?></td>
          <td class="primary"><a href="./view_item.php?view-item-id=<?php echo $item_id  ?>">View</a></td>
          </td>
        </tr>

      <?php
      }
      ?>
      </tbody>
    </table>
    <a href="./menu_item_manager.php">See All items</a>
  </section>
  <!-- END of recent-order table section-->
</main>