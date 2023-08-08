<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php require "./base_link_script.php";  ?>
    <link rel="stylesheet" href="./css/view-item.css">
</head>

<body>
    <div id="main-container">
        <?php
        require "./pages/sidebar.php";
        if (isset($_GET["view_item_id"])) {
            $item_id = $_GET["view_item_id"];
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
        <section class="view-product-section" id="product-section-anchor">
            <div>
                <a id="back-btn" href="./menu_item_manager.php"> <i class="fa-solid fa-backward-step"></i></a>
            </div>
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
                    <a href="./update_item.php?update_item_id=<?php echo $item_id ?>" class="edit-btn warning-border">Edit</a>
                </div>
            </div>

        </section>

        <?php require "./pages/right-dashboard-panel.php";  ?>
        <div>
            <script>
                const mainImg = document.querySelector("#main-img img");
                const smallImgs = document.querySelectorAll(".small-img-group img");

                smallImgs.forEach((img) => {
                    img.addEventListener("click", function() {
                        const imgSrc = img.getAttribute("src");
                        mainImg.setAttribute('src', imgSrc);
                    });
                });
            </script>
             <script src="./scripts/sidebar.js"></script>
    <script src="./scripts/theme-togggler.js"></script>
</body>


</html>