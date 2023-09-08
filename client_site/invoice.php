<?php

if (!isset($_SESSION)) {
  session_start();
}
if (!isset($_SESSION["login_user_id"])) {
  echo '
    <script> 
        alert("Please login the account first"); 
        location.href = "./login.php"; 
    </script>';
}
require "../dao/connection.php";

if (!isset($_REQUEST["invoice_order_id"])) {
  echo "<script>  alert('System error')</script>";
  exit;
}
$order_id = $_REQUEST["invoice_order_id"];
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ordering System</title>

  <?php require "./components/base-link.php" ?>
  <link rel="stylesheet" href="css/search-bar.css" />
  <link rel="stylesheet" href="css/alert-box.css" />
  <link rel="stylesheet" href="./css/invoice.css">
  <style>
    @media print {

      #header,
      #sidebar {
        display: none;
      }

      #main-container {
        display: block;
        width: 100%;
        margin: 0 auto;
      }


    }
  </style>
</head>

<body>

  <div id="main-container">
    <?php require './components/sidebar.php';  ?>
    <div>
      <div>
        <?php require './components/navbar.php';  ?>
        <section id="invoice-popup-section">
          <!-- START of invoice-header-block1  -->
          <div class="header-block block1">
            <div>
              <img src="../../images/logo.png" alt="" id="invoice-icon">
              <h6>No. 24D, Nar Nat Taw Road, Kamayut Township, Yangon</h6>
              <h6>Gmail: heinmin2maw.it@gmail.com</h6>
            </div>
            <div class="company-info">
              <h1 class="website-name">SMART Cafe & Bakery Bar</h1>
              <h5 style="margin: 5px 0;">Business Address</h5>
              <table id="business-address-tbl">
                <tr>
                  <td>City</td>
                  <td>Yangon</td>
                </tr>
                <tr>
                  <td>Country</td>
                  <td>Myanmar</td>
                </tr>
                <tr>
                  <td>Postal Code</td>
                  <td>11011</td>
                </tr>
              </table>
            </div>
          </div>
          <!-- END of invoice-header-block1  -->
          <?php
          $get_order_customer_data_qry = "SELECT o.*, o.id as order_id, u.*
FROM orders AS o
JOIN users AS u ON o.user_id = u.id
WHERE o.id = :order_id";

          $stmt = $connection->prepare($get_order_customer_data_qry);
          $stmt->bindParam(':order_id', $order_id);
          $stmt->execute();

          $data = $stmt->fetch(PDO::FETCH_ASSOC);

          ?>
          <!-- START of invoice-header-block2 -->
          <div class="header-block block2">
            <div>
              <h2>Detail</h2>
              <table id="customer-address-tbl">
                <tr>
                  <td>Waiter Name</td>
                  <td><?php echo $data["name"] ?></td>
                </tr>
                <tr>
                  <td>Checkout Date</td>
                  <td><?php
                      $date = new DateTime($data["order_datetime"]);

                      // Format the date as 'Y-F-d h:i a' to get '2023-August-01 03:05 pm'
                      $formatted_date = $date->format('Y-F-d h:i a');
                      echo $formatted_date; ?></td>
                </tr>

              </table>
            </div>
            <div class="company-info">

              <h5>Invoice ID: I-<span id="invoice-id"> <?php echo  $order_id; ?><span></h5>
              <table id="invoice-date">
                <tr>
                  <td>Checkout Date</td>
                  <td><?php echo $data["order_datetime"] ?></td>
                </tr>

              </table>
            </div>

          </div>
          <!-- END of invoice-header-block2 -->

          <hr>

          <section class="order-list-section">
            <table id="order-list-table">
              <thead>
                <th>No.</th>
                <th>Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th >Amount (ks)</th>
              </thead>
              <?php
              $serial = 1;
              $get_order_items_qry = "SELECT order_item.*, item.*, item_media.* 
            FROM order_item 
            JOIN item ON order_item.item_id = item.id 
            JOIN item_media ON item.id = item_media.item_id 
            WHERE order_item.order_id = $order_id";

              $item_dataset = $connection->query($get_order_items_qry);
              $item_data = $item_dataset->fetchAll();
              $total_cost = 0;
              foreach ($item_data as $key => $value) {
                $price = $value["price"];
                $quantity = $value["num_ordered"];
                $subtotal = $price * $quantity;
                $total_cost += $subtotal;
                $formattedSubtotal = $subtotal ." Ks";
              ?>
                <tr>
                  <td><?php echo  $serial++  ?></td>

                  <td><?php echo $value["name"]  ?></td>
                  <td><?php echo  $price   ?></td>
                  <td>
                    <span class="quantity"><?php echo $quantity ?></span>
      </div>
      </td>
      <td class="subtotal-col" style=" text-align: right;"><?php echo  $formattedSubtotal  ?></td>
      </tr>
    <?php
              }
    ?>
    <tr id="total-cost-data" class="information">
      <td colspan="4" id="total-cost-text-col">Total Cost</td>
      <td colspan="2" id="total-cost-col"><?php
       //  echo  number_format($total_cost, 2, ',') . ' Ks'; 
        echo $total_cost . ' Ks'; 
       ?>
      </td>
    </tr>
    </table>
    </section>

    </section>
    <button onclick="window.print();" id="print-btn">Print the invoice</button>
    </div>



  </div>
</body>

</html>