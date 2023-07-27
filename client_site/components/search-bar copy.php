  <section class="product-search-bar-container">
    <form action="">
      <i class="fas fa-search"></i>
      <input type="text" id="search-item" placeholder="search products" onkeyup="searchItem()" />
    </form>
    <div id="search-bar-result">
      <ul class="matched-item-lists">
        <?php
        $get_product_images_sql = "SELECT p.*, i.primary_img, i.additional_image1, i.additional_image2, i.additional_image3, i.additional_image4, c.category_name
        FROM product p 
        LEFT JOIN images i ON p.id = i.product_id
        LEFT JOIN category c ON p.category_id = c.id";
        $stmt = $connection->query($get_product_images_sql);
        $search_data_source = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($search_data_source as $data) {
          $id = $data["id"];
          $name = $data["name"];
          $price = $data["price"];
          $quantity = $data["quantity"];
          $description =  $data["description"];
          $primary_image = $data["primary_img"];
          $category = $data["category_name"];
          $quantity = $data["quantity"];
        ?>
          <li>
            <a href="./product-detail.php?view-product-id=<?php echo $id ?>">
              <div class="matched-item-box">
                <img src="../images/Products/<?php echo $category . '/' . $primary_image ?>" alt="<?php echo  $primary_image ?>" />

                <div class="item-description">
                  <?php
                  if ($quantity > 0) {
                  ?>
                    <span class="stock-info in-stock"> In stock</span>
                  <?php
                  } else {
                  ?>
                    <span class="stock-info out-stock">out-of-stock</span>
                  <?php
                  }
                  ?>
                  <h2 class="item-title"><?php echo $name ?></h2>
                  <p class="item-detail">
                    <?php echo  $description  ?>
                  </p>

                </div>
                <h4 class="item-price"> <?php echo  $price  ?> Ks</h4>
              </div>
              <hr>
            </a>
          </li>
        <?php
        }
        ?>
      </ul>
    </div>
  </section>