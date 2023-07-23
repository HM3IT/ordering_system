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
      <small class="text-muted" >Sales contribution to target sales </small>
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

  <!-- START of recent-order table section-->
  <section class="recent-order">
    <h2>Recent Orders</h2>
    <table id="recent-order-table">
      <thead>
        <tr>
          <th>No.</th>
          <th>Order ID</th>
          <th>Customer</th>
          <th>Payment</th>
          <th>Status</th>
          <th></th>
        </tr>
      </thead>
      <?php

      if (!isset($connection)) {
        require "../dao/connection.php";
      }
      $get_recent_received = "SELECT `orders`.id AS order_id, `orders`.*, customer.*
      FROM `orders`
      INNER JOIN customer ON `orders`.customer_id = customer.id
      WHERE `orders`.delivery_status = 'PENDING'
      ORDER BY `orders`.order_date DESC
      LIMIT 4";



      $stmt = $connection->prepare($get_recent_received);
      $stmt->execute();
      $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $serial = 1;
      foreach ($results as $row) {

        $order_received_date = $row['order_date'];

        $current_time = time();
        $received_time = strtotime($order_received_date);

        $time_elapsed = $current_time - $received_time;

        if ($time_elapsed < 60) {
          $elapsed_time = $time_elapsed . " " . ($time_elapsed > 1 ? "s" : "") . " ago";
        } elseif ($time_elapsed >= 60 && $time_elapsed < 3600) {
          $elapsed_minutes = floor($time_elapsed / 60);
          $elapsed_time = $elapsed_minutes . " minute" . ($elapsed_minutes > 1 ? "s" : "") . " ago";
        } elseif ($time_elapsed >= 3600 && $time_elapsed < 86400) {
          $elapsed_hours = floor($time_elapsed / 3600);
          $elapsed_time = $elapsed_hours . " hour" . ($elapsed_hours > 1 ? "s" : "") . " ago";
        } elseif ($time_elapsed >= 86400) {
          $elapsed_days = floor($time_elapsed / 86400);
          $elapsed_time = $elapsed_days . " day" . ($elapsed_days > 1 ? "s" : "") . " ago";
        }


      ?>
        <tr>
          <td><?php echo  $serial++; ?></td>
          <td>ORD <?php echo $row["order_id"] ?></td>
          <td><?php echo $row["name"] ?></td>
          <td><?php echo $row["payment_method"] ?></td>
          <td class="warning">Pending</td>
          <td class="primary">
            <a href="./view_order.php?view_order_id=<?php echo $row['order_id'] ?>">View Details</a>
          </td>
        </tr>

      <?php
      }
      ?>
      </tbody>
    </table>
    <a href="./order_manager.php">Show All</a>
  </section>
  <!-- END of recent-order table section-->
</main>
