<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require "../dao/connection.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php require "./components/base-link.php" ?>

    <link rel="stylesheet" href="css/search-bar.css" />
    <link rel="stylesheet" href="css/product-section.css">
    <link rel="stylesheet" href="css/product-slider.css">
    <link rel="stylesheet" href="css/product-detail.css">
    <!-- <link rel="stylesheet" href="css/star-scale-rating.css"> -->
    <!-- <link rel="stylesheet" href="css/review-product.css"> -->
    <link rel="stylesheet" href="css/alert-box.css" />
</head>

<body>
    <div id="main-container">
        <?php
        require './components/sidebar.php';
        require 'components/alert-box.php';

        if (isset($_GET["view-item-id"])) {
            $item_id = $_GET["view-item-id"];
            $_SESSION["current-view-item"] = $item_id;
        } else {
            $item_id = $_SESSION["current-view-item"];
        }

        $get_item_query = "SELECT item.*, category.*, item_media.* 
        FROM item
        LEFT JOIN category ON item.category_id = category.id
        LEFT JOIN item_media ON item.id = item_media.item_id
        WHERE item.id = :item_id;
    ";

        $stmt = $connection->prepare($get_item_query);

        $stmt->bindParam(':item_id', $item_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $viewed_item_data = $stmt->fetch(PDO::FETCH_ASSOC);

            $name = $viewed_item_data['name'];
            $price = $viewed_item_data['price'];
            $quantity = $viewed_item_data['quantity'];
            $description = $viewed_item_data['description'];
            $category_id =  $viewed_item_data["category_id"];
            $category_name =  $viewed_item_data["category_name"];
            $primary_image = $viewed_item_data["primary_img"];
        } else {

            echo "data retriving error! Please contact with the developer team";
            exit;
        }
        ?>

        <div>
            <?php
            require './components/navbar.php';
            ?>
            <section class="view-product-section" id="product-section-anchor">
                <div class="view-product-box">
                    <div class="image-section">
                        <div id="main-img">
                            <?php
                            if ($viewed_item_data["quantity"]  > 0) {
                            ?>
                                <span class="stock-info in-stock"> In Stock</span>
                            <?php
                            } else {
                            ?>
                                <span class="stock-info out-stock"> Out of Stock</span>
                            <?php
                            }
                            ?>
                            <img src="../images/Menu_items/<?php echo $category_name . "/" . $primary_image ?>" alt="<?php echo $primary_image ?>">
                        </div>
                        <div class="small-img-group">
                            <?php
                            $add_img_num = 1;
                            $additional_img_limit = 4;
                            for ($i = 1; $i <= $additional_img_limit; $i++) {
                                if (!empty($viewed_item_data["additional_image" . $i])) {
                            ?>
                                    <img src="../images/Menu_items/<?php echo $category_name . "/" . $viewed_item_data["additional_image" . $i] ?>" alt="<?php echo $viewed_item_data["additional_image" . $i] ?>">
                            <?php
                                }
                                $add_img_num++;
                            }
                            ?>
                        </div>
                    </div>
                    <div class="product-description">
                        <div class="product-description-head">
                            <h2 class="product-title "><?php echo $name ?></h2>
                            <h2 class="product-price "><?php echo $price ?> Ks</h2>
                            <label for="Switch-color">Select Switch</label>
                            <select name="switch-color" id="switch-color">
                                <option value="red">Red</option>
                                <option value="blue">Blue</option>
                                <option value="brown">Brown</option>
                                <option value="white">White</option>
                            </select>
                            <?php
                            if ($viewed_item_data["quantity"] > 0) {
                            ?>

                                <form method="POST" class="cart-form">
                                    <input type="hidden" name="id" id="id" value="<?php echo  $item_id ?>">
                                    <input type="hidden" name="name" id="name" value="<?php echo $name ?>">
                                    <input type="hidden" name="primary_img" id="image" value="<?php echo $primary_image ?>">
                                    <input type="hidden" name="category" id="category" value="<?php echo $category ?>">
                                    <input type="hidden" name="price" id="price" value="<?php echo $price ?>">
                                    <input type="hidden" name="description" id="description" value="<?php echo $description ?>">
                                    <input type="hidden" name="current_page" class="current_page">

                                    <!-- <a href="./controllercart_controller.php/add_to_cart" class="add-to-cart-btn">
                            Add to Cart
                        </a> -->
                                    <input type="submit" value="add to cart" name="add_to_cart" class="add-to-cart-btn">
                                </form>
                            <?php
                            }
                            ?>
                        </div>
                        <div class="product-description-body">

                            <h2 class="product-detail">Menu Item Details</h2>
                            <?php
                            if (strpos($description, "\n") !== false) {
                                // If the description contains line breaks, display as line-separated paragraphs
                                $paragraphs = explode("\n", $description);

                                // Display each paragraph
                                echo '<ul >';
                                foreach ($paragraphs as $paragraph) {
                                    $trimmedParagraph = trim($paragraph);
                                    if (!empty($trimmedParagraph)) {
                                        echo '<li style="list-style-type: disc;">' . $trimmedParagraph . '</li>';
                                    }
                                }
                                echo '</ul>';
                            } else {
                                // If the description does not contain line breaks, display as a single paragraph
                                echo '<p>' . $description . '</p>';
                            }
                            ?>

                        </div>
                    </div>
                </div>
                <?php
                // require COMPONENTS_PATH . 'reviewed_item_data.php';
                // require COMPONENTS_PATH . 'cart-list.php';
                require './components/product-slider.php';

                ?>
            </section>
        </div>
    </div>
    <script src="scripts/navbar.js"> </script>
    <script src="scripts/view-product.js"></script>
    <script src="scripts/redirect.js"> </script>
    <script src="scripts/review-submit.js"> </script>
    <script src="scripts/star-scale-rating.js"> </script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
    <script src="scripts/swiper.js"> </script>
    <!-- <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script> -->
</body>

</html>