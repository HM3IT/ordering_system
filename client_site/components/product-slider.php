<section id="product-slider-section">
  <h3 id="trends-product-title">Trends Product</h3>
  <div class="trend-product-wrapper">
    <i id="left-arrow-btn" class="fa-solid fa-angle-left"></i>
    <i id="right-arrow-btn" class="fa-solid fa-angle-right"></i>

    <ul class="carousel" id="product-section-anchor">
      <?php
      $serial = 1;
      $get_all_product_sql = "SELECT * FROM product  
       WHERE quantity <> 0
       ORDER BY sold_quantity
       DESC LIMIT 15";
      foreach ($connection->query($get_all_product_sql) as $row) {
        $id = $row["id"];
        $name =  $row["name"];
        $price = $row["price"];
        $quantity = $row["quantity"];
        $category_id =  $row["category_id"];
        $description =  $row["description"];

        $get_product_related_data = "
        SELECT i.*, c.*
        FROM images AS i
        JOIN category AS c ON c.id = $category_id
        WHERE i.product_id = $id
    ";

        $product_dataset = $connection->query($get_product_related_data);
        $data = $product_dataset->fetch();
        $primary_image = $data["primary_img"];
        $category = $data["category_name"];

        $total_rating = 0;
        $total_reviews = 0;
        $average_rating = 0;

        $get_product_reviews = "SELECT rating FROM product_review WHERE product_id = $id";
        $review_rows = $connection->query($get_product_reviews);

        // Iterate through the review rows
        while ($review = $review_rows->fetch()) {
          $rating = $review['rating'];
          $total_rating += $rating;
          $total_reviews++;
        }

        if ($total_reviews > 0) {
          $average_rating = floor($total_rating / $total_reviews);
        }

        $blank_stars = 5 - $average_rating;
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
            <img class="product-img" src="../images/Products/<?php echo $category . '/' . $primary_image ?>" alt="<?php echo  $primary_image ?>" />
            <div class="rating-scale">
              <?php
              for ($i = 0; $i < $blank_stars; $i++) {
              ?>
                <i class="fa-solid fa-star disable-text"></i>
              <?php
              }
              for ($i = 0; $i < $average_rating; $i++) {
              ?>
                <i class="fa-solid fa-star warning"></i>
              <?php
              }
              ?>
            </div>

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