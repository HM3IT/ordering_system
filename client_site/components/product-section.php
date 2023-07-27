
<section class="product-section" id="product-section-anchor">
  <h2>
    <?php 
    if( isset($_GET["food-type"])){
      echo $_GET["food-type"];
    }else{
      echo "Food";
    }
    ?>
  </h2>

  <div class="product-container">

    <?php
    $item_per_page = 12;
    $counter = 0;
    foreach ($dataset  as $row) {
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
      <div class="product-card">
        <form method="POST" class="cart-form">
          <input type="hidden" name="id" id="id" value="<?php echo  $id ?>">
          <input type="hidden" name="name" id="name" value="<?php echo $name ?>">
          <input type="hidden" name="primary_img" id="image" value="<?php echo $primary_image ?>">
          <input type="hidden" name="category" id="category" value="<?php echo $category ?>">
          <input type="hidden" name="price" id="price" value="<?php echo $price ?>">
          <input type="hidden" name="description" id="description" value="<?php echo $description ?>">
          <input type="hidden" name="current_page" class="current_page">
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
          <div class="flex-box">
            <img class="product-image" src="../images/Menu_items/<?php echo $category . '/' . $primary_image ?>" alt="<?php echo  $primary_image ?>" />

            <div class="product-info-div">
               <div class="cart-description">
                <h3 class="item-title"><?php echo $name  ?></h3>

                <div class="rating-star"></div>

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
                <?php
                if ($quantity   > 0) {
                ?>
                  <button type="submit" name="add_to_cart" class="add-to-cart">
                    <i id="cart-btn" class="fa-solid fa-cart-shopping"></i>
                  </button>
                <?php
                }
                ?>
              </div>
            </div>
          </div>
        </form>
      </div>
    <?php
      $counter++;
      if ($counter >= $item_per_page) {
        break;
      }
    }
    ?>
  </div>
</section>