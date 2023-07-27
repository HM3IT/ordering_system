<?php
if (!isset($_SESSION)) {
  session_start();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <?php require "./components/base-link.php" ?>
  <link rel="stylesheet" href="./css/invoice.css">
  <link rel="stylesheet" href="css/newsletter.css">
</head>

<body>
  <?php

  define('COMPONENTS_PATH', './pages/');

  require COMPONENTS_PATH . 'navbar.php';
  ?>
  <section id="invoice-popup-section">
    <!-- START of invoice-header-block1  -->
    <div class="header-block block1">
      <div>
        <img src="../../images/logo.png" alt="" id="invoice-icon">
        <h6>No. 24D, Nar Nat Taw Road, Kamayut Township, Yangon</h6>
        <h6>Gmail: heinmin2maw.it@gmail.com</h6>
      </div>
      <div class="company-info">
        <h1 class="website-name">HM3</h1>
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
    <div class="header-block block2">
      <div>
        <h2>Customer</h2>
        <table id="customer-address-tbl">
          <tr>
            <td>Name</td>
            <td><?php echo $_SESSION["customer_name"] ?></td>
          </tr>
          <tr>
            <td>Address</td>
            <td><?php echo $_SESSION["ship_address"] ?></td>
          </tr>

          <td>Postal Code</td>
          <td>11011</td>
          </tr>
        </table>
      </div>
      <div class="company-info">

        <h5>Invoice ID: I-<span id="invoice-id"> <?php echo  $_SESSION["recent_order_id"]?><span></h5>
        <table id="invoice-date">
          <tr>
            <td>Date</td>
            <td>29-May-2023</td>
          </tr>

        </table>
      </div>

    </div>
    <!-- START of invoice-header-block2 -->

    <hr>
    <!-- END of invoice-header-block2 -->

    <section class="order-list-section">
      <table id="order-list-table">
        <thead>
          <th>No.</th>
          <th>Name</th>
          <th>Description</th>
          <th>Price</th>
          <th>Quantity</th>
          <th>Amount (ks)</th>
        </thead>
        <?php
        $serial = 1;
        $total_cost = 0;
        foreach ($_SESSION["cart"] as $key => $value) {
          $price = $value["price"];
          $quantity = $value["Quantity"];
          $subtotal = $price * $quantity;
          $total_cost += $subtotal;
          $formattedSubtotal = number_format($subtotal, 2, ',');
        ?>
          <tr>
            <td><?php echo  $serial++  ?></td>

            <td><?php echo $value["name"]  ?></td>
            <td class="description text-left"> <?php echo $value["description"]  ?></td>
            <td><?php echo  $price   ?></td>
            <td>
              <span class="quantity"><?php echo $quantity ?></span>
              </div>
            </td>
            <td class="subtotal-col"><?php echo  $formattedSubtotal  ?></td>
          </tr>
        <?php
        }
        ?>
        <tr id="total-cost-row" class="information">
          <td colspan="4">Total Cost</td>
          <td colspan="2"><?php
                          $_SESSION["total_cost"] =  $total_cost;
                          echo  number_format($total_cost, 2, ',') . ' Ks';
                          ?>
          </td>

        </tr>
      </table>


    </section>

  </section>

  <div id="checkout-overlay"></div>
  <div id="checkout-noti-form">
    <div>
      <i class="fa-regular fa-face-laugh-beam" id="smilly-emoji"></i>
      <h2>Order has submitted. Please stay tuned for the reply within one hour.</h2>
    </div>
    <a class="information-bg" id="check-out-noti-form-btn">OK</a>
  </div>

  <button onclick="window.print();" id="print-btn">Print the invoice section</button>

  <?php
  require COMPONENTS_PATH . 'footer.html';
  foreach ($_SESSION["cart"] as $key => $value) {
    // Remove the product from the session
    unset($_SESSION["cart"][$key]);
  }
  ?>
  <script src="scripts/navbar.js"> </script>
  <script>
    document
      .getElementById("check-out-noti-form-btn")
      .addEventListener("click", function(e) {
        document.getElementById("checkout-overlay").style.display = "none";
        document.getElementById("checkout-noti-form").style.display = "none";
      });
  </script>

</body>
</html>