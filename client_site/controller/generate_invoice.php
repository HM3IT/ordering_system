<?php
if (!isset($_SESSION)) {
    session_start();
}

require_once '../../dompdf/autoload.inc.php';
use Dompdf\Dompdf;


// Generate the invoice content here
$invoiceContent = '
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Document</title>
  <style>
  /* 1. invoice-section  */
  #invoice-pop-section {
    min-width: 400px;
    max-width: 820px;
    margin: auto;
    font-size: 1rem;
    box-shadow: 3px 4px 5px black;
  }
  /* 2. header block */
  #invoice-pop-section .header-block {
    display: flex;
    padding: 10px 35px;
    justify-content: space-between;
  }
  
  #invoice-pop-section .header-block div:first-child {
    width: 240px;
  }
  
  
  #invoice-icon {
    width: 65px;
    height: 65px;
    border: 2px solid white;
    padding: 10px;
    border-radius: 10px;
  }
  .company-info {
    text-align: right;
  }
  /* 2.1 businesss address table */
  #customer-address-tbl {
    width: 100%;
  }
  
  #customer-address-tbl tr td:nth-child(2) {
    text-align: left;
    text-indent: 1rem;
    width: 180px;
  }
  /* 3. Table coloring */
  #invoice-pop-section .header-block.block1,
  #total-cost-row {
    color: white;
    background-color: rgb(236, 101, 23);
  }
  #total-cost-row td:first-child {
    background-color: rgb(249, 168, 127);
    color: black;
  }
  /* 4. order-list table */
  /* hightlighting view-cart-btn in  navigation bar */
  
  #invoice-pop-section #order-list-table {
    width: 100%;
    margin: 0 auto;
    padding: 0px;
    text-align: center;
  }
  
  #order-list-table {
    width: 80%;
    margin: 50px auto;
    padding: 30px;
    text-align: center;
  }
   
  #order-list-table:hover {
    box-shadow: none;
  }
  #order-list-table thead{
    text-transform: uppercase;
  }
  #order-list-table tbody td {
    height: 2.8rem;
    border-bottom: 1px solid var(--color-light);
    color: var(--color-dark-variant);
  }
  
  #order-list-table .product-tbl-img {
    width: 85px;
    height: 100px;
  }
  
  #order-list-table .subtotal-col {
    text-align: right;
    width: 100px;
    margin-top: 10px;
    padding-right: 2rem;
  }
  #download-invoice{
    text-align: center;
    text-decoration: none;
    font-size: 2rem;
  }
 
  </style>
</head>

<body>
  <section id="invoice-pop-section">
    <!-- START of invoice-header-block1  -->
    <div class="header-block block1">
      <div>
       
        <h1>Invoice</h1>
      </div>
      <div class="company-info">
        <h1 class="website-name">H3IN</h1>
        <h5 style="margin: 5px 0;">Business Address</h5>
        <table id="business-address-tbl">
          <tr>
            <td>City</td>
            <td>Taunggyi</td>
          </tr>
          <tr>
            <td>Country</td>
            <td>Myanmar</td>
          </tr>
          <tr>
            <td>Zipcode</td>
            <td>36602</td>
          </tr>
        </table>
      </div>
    </div>
    <!-- END of invoice-header-block1  -->
    <div class="header-block block2">
      <div>
        <h2 style="margin:0 0 5px 0;">Customer</h2>
        <table id="customer-address-tbl">
          <tr>
            <td>Name</td>
            <td>Hein Min Min Maw</td>
          </tr>
          <tr>
            <td>City</td>
            <td>Taunggyi</td>
          </tr>
          <tr>
            <td>Country</td>
            <td>Myanmar</td>
          </tr>
          <tr>
            <td>Zipcode</td>
            <td>36602</td>
          </tr>
        </table>
      </div>
      <div class="company-info">
        <h5>Invoice ID: <span id="invoice-id">I-2303</span></h5>
        <table class="business-address-tbl">
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
        ';

// Add the dynamic content from PHP
$serial = 1;
$total_cost = 0;
foreach ($_SESSION["cart"] as $key => $value) {
  $price = $value["price"];
  $quantity = $value["Quantity"];
  $subtotal = $price * $quantity;
  $total_cost += $subtotal;
  $formattedSubtotal = number_format($subtotal, 2, ",");
  
  // Add the row for each item
  $invoiceContent .= '
  <tr>
    <td>' . $serial++ . '</td>
    <td>' . $value["name"] . '</td>
    <td>' . $value["description"] . '</td>
    <td>' . $price . '</td>
    <td>
      <span class="quantity">' . $quantity . '</span>
    </td>
    <td class="subtotal-col">' . $formattedSubtotal . '</td>
  </tr>
  ';
}

// Add the total cost row
$invoiceContent .= '
<tr id="total-cost-row" class="information">
  <td colspan="4">Total Cost</td>
  <td colspan="2">' . number_format($total_cost, 2, ",") . ' Ks</td>
</tr>
';

// Complete the invoice content
$invoiceContent .= '
    </table>
  </section>
</section>
</body>

</html>
';


// Create a new Dompdf instance
$dompdf = new Dompdf();

// Load the invoice content
$dompdf->loadHtml($invoiceContent);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'landscape');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream('invoice.pdf');
