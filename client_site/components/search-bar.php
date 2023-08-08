<section class="product-search-bar-container">
    <form action="">
      <i class="fas fa-search"></i>
      <input type="text" id="search-item" placeholder="Search Menu" />
    </form>
    <div id="search-bar-result">
      <ul class="matched-item-lists">
        <?php

 if(isset($all_dataset)){

        foreach ( $all_dataset  as $row) {
  
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
            <a href="./item_detail.php?view-item-id=<?php echo $id ?>">
              <div class="matched-item-box">
                <img src="../images/Menu_items/<?php echo $category . '/' . $primary_image ?>" alt="<?php echo  $primary_image ?>" />

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
 }
        ?>
      </ul>
    </div>
  </section>
  <script src="./scripts/search-bar.js">

  </script>