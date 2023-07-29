<section id="product-slider-section">
  <h3 id="trends-product-title">Special Menu</h3>
  <div class="trend-product-wrapper">
    
    <i id="left-arrow-btn" class="fa-solid fa-square-caret-left"></i>
    <i id="right-arrow-btn" class="fa-solid fa-square-caret-right"></i>
 

    <ul class="carousel" id="product-section-anchor">
      <?php
      $serial = 1;
      $get_all_product_sql = "SELECT * FROM item  
       WHERE quantity <> 0
       ORDER BY sold_quantity
       DESC LIMIT 10";
       
      foreach ($connection->query($get_all_product_sql) as $row) {
        $id = $row["id"];
        $name =  $row["name"];
        $price = $row["price"];
        $quantity = $row["quantity"];
        $category_id =  $row["category_id"];
        $description =  $row["description"];

        $get_product_related_data = "
        SELECT i.*, c.*
        FROM item_media AS i
        JOIN category AS c ON c.id = $category_id
        WHERE i.item_id = $id
    ";

        $product_dataset = $connection->query($get_product_related_data);
        $data = $product_dataset->fetch();
        $primary_image = $data["primary_img"];
        $category = $data["category_name"];
  
      ?>
        <li>
          <div class="product-card">
            <?php
            if ($quantity   > 0) {
            ?>
              <span class="stock-info in-stock"> In Stock</span>
            <?php
            } else {
            ?>
              <span class="stock-info out-stock"> Out of Stock</span>
            <?php
            }
            ?>
            <img class="product-img" src="../images/Menu_items/<?php echo $category . '/' . $primary_image ?>" alt="<?php echo  $primary_image ?>" />
            
            <div class="cart-description">
              <h4><?php echo  $name  ?></h4>
              <?php
              $discount =   $row["discount"];

              if ($discount > 0) {
                // Calculate the discount price
                $discount_price = $price - ($price * $discount / 100);

              ?>
                <h4 class="price"><span class="actual-price"><?php echo $price   ?> Ks</span>
                  <span class="discount-price"><?php echo   $discount_price ?> Ks</span>
                </h4>
              <?php
              } else {

              ?>
                <h4 class="price"><?php echo  $price ?> Ks</span>
                </h4>
              <?php
              }

              ?>
            </div>
            <div class="cart-btn-part">
              <a href="./product-detail.php?view-product-id=<?php echo $id ?>" class="view-description-link">View Details</a>

              <form action="./controller/cart_controller.php" method="POST" class="cart-form">
                <input type="hidden" name="id" id="id" value="<?php echo  $id ?>">
                <input type="hidden" name="name" id="name" value="<?php echo $name ?>">
                <input type="hidden" name="primary_img" id="image" value="<?php echo $primary_image ?>">
                <input type="hidden" name="category" id="category" value="<?php echo $category ?>">
                <input type="hidden" name="price" id="price" value="<?php echo $price ?>">
                <input type="hidden" name="description" id="description" value="<?php echo $description ?>">
                <input type="hidden" name="current_page" class="current_page">


                <button type="submit" name="add_to_cart" class="add-to-cart">
                  <i id="cart-btn" class="fa-solid fa-cart-shopping"></i>
                </button>
              </form>
            </div>
          </div>
        </li>
      <?php
      }
      ?>
    </ul>

  </div>
</section>
<script src="./scripts/product-slider.js"> </script>