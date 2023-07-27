<div id="popup-info-box">
  <i class="fa-regular fa-face-smile-beam"></i>
  <p>Product is added! </p>
</div>

<?php
if (isset($_SESSION["cart"])) {
?>
  <div class="card-list-open-btn">
    <span id="cart-items-counter">
      <?php
      $count = count($_SESSION["cart"]);
      echo  $count ?>
    </span>
    <i class="fa-solid fa-basket-shopping"></i>
  </div>
<?php
}
?>
<div class="card-list">
  <h2 class="card-list-title">Your Cart</h2>
  <ul id="card-list-ul">
    <?php
    if (isset($_SESSION["cart"])) {

      if ($count <= 0) {
    ?>
        <li class="card-list-items">
          <div class="card-list-box">
            <i class="fa-solid fa-face-grin-beam-sweat" id="sad-emoji"></i>
            <div class="card-list-box-description1">
              <h3 style="text-align:center">You have not added any order yet! </h3>
            </div>
          </div>
        <li>

          <?php
        } else {

          foreach ($_SESSION["cart"] as $key => $value) {

          ?>
        <li class="card-list-items">
          <div class="card-list-box">
            <img src="../images/Products/<?php echo $value['category'] . '/' . $value['image'] ?>" alt="<?php echo $value['image'] ?>" />
            <div class="card-list-box-description1">
              <p> <?php echo $value["description"] ?></p>

              <div class="product-quantity-wrapper">
                <input type="hidden" class="cart-id" data-product-id="<?php echo $value['id']; ?>">
                <span class="minus" data-product-index="<?php echo $key; ?>">-</span>
                <span class="quantity" data-product-index="<?php echo $key; ?>">
                  <?php echo $value["Quantity"] ?>
                </span>
                <span class="plus" data-product-index="<?php echo $key; ?>">+</span>
              </div>
            </div>
            <div class="card-list-box-description2">
              <span><?php echo $value["price"] ?> Ks</span>
              <button class="product-remove-btn" data-product-id="<?php echo $value['id']; ?>">
                <i class="fa-regular fa-trash-can"></i>
              </button>
            </div>
          </div>
        </li>

  <?php
          }
        }
      }
  ?>
  </ul>
  <div class="card-list-footer">
    <a href="./view-cart-list.php" class="card-list-check-out">
      View Detail & Check Out
    </a>
    <div class="card-list-close-btn">
      <i class="fa-regular fa-circle-xmark"></i>
    </div>
  </div>
</div>
<script src="scripts/cart-list.js"></script>
<script src="scripts/add-remove-cart.js"></script>