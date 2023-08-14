<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (empty($_SESSION["status"])) {
    echo '
    <script> 
        alert("Please confirm the user authentication"); 
        location.href = "./login.php"; 
    </script>';
}
require "../dao/connection.php";
if (isset($_GET["update_item_id"])) {
    $id = $_GET["update_item_id"];

    $get_item_and_media_sql = "SELECT item.*, item_media.*
                               FROM item
                               LEFT JOIN item_media
                               ON item.id = item_media.item_id
                               WHERE item.id = $id";
    
    $resultSet = $connection->query($get_item_and_media_sql);
    $data = $resultSet->fetch();
    $category_id = $data["category_id"];
    $item_id = $data["item_id"];

    $get_category_sql = "SELECT id, category_name FROM category";
    $category_dataset = $connection->query($get_category_sql);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php require "./base_link_script.php";  ?>
    <link rel="stylesheet" href="./css/product_form.css">
</head>

<body>
    <div id="main-container">
        <?php
        require "./pages/sidebar.php";
        ?>
        <section class="product-form-section">
        <?php echo $item_id; ?>
            <h2 class="title warning">Menu Item Information Form</h2>
            <form class="product-form" action="controller/menu_item_controller.php" method="post" enctype="multipart/form-data">
                <div>
                    <input type="hidden" name="id" value="<?php echo $item_id; ?>">

                    <label for="name">Item name</label>
                    <input type="text" name="name" id="name" value="<?php echo $data["name"] ?>" required>

                    <label for="description">Description: </label>
                    <textarea name="description" id="description" cols="30" rows="10" required><?php echo $data["description"] ?></textarea>

                    <div class="inline-block">
                        <label for="price">Price (Ks):</label>
                        <input type="text" id="price" name="price" pattern="[0-9]*" inputmode="numeric" placeholder="Enter price in Kyat" value="<?php echo $data["price"] ?>" required>

                        <label for="discount">Discount percent:</label>
                        <input type="number" name="discount" id="discount" min="0" max="100" value="<?php echo $data["discount"] ?>" placeholder="in percent">
                    </div>

                    <div class="inline-block">
                        <label for="category">Category:</label>
                        <select id="category" name="category_id" required>
                            <?php while ($category_data = $category_dataset->fetch()) { ?>
                                <option value="<?php echo $category_data["id"]; ?>" <?php if ($category_data["id"] == $category_id) echo "selected"; ?>>
                                    <?php echo $category_data["category_name"]; ?>
                                </option>
                            <?php } ?>
                        </select>

                        <label for="quantity">Quantity:</label>
                        <input type="number" id="quantity" name="quantity" min="1" max="100" value="<?php echo $data["quantity"] ?>" required>
                    </div>
                    <div class="inline-block">
                        <label for="primary_img">Select Main Featured Image:</label>
                        <input type="file" name="primary_img" id="primary_img">
                    </div>

                    <div class="inline-block">
                        <label for="additional_imgs">Additional images (up to 4):</label>
                        <input type="file" name="additional_imgs[]" id="additional_imgs" multiple accept="image/*" max="4">
                    </div>
                    <label for="youtube_URL">Enter YouTube Video URL of item (<b>optional<b>) :</label>
                    <input type="text" id="youtube_URL" name="youtube_URL" value="<?php echo $data["youtube_video_link"] ?>">

                    <div class="button-flex">
                        <!-- <input type="reset" value="Cancel" class="cancel-btn danger-border"> -->
                        <a href="./menu_item_manager.php" class="cancel-btn danger-border" id="back-btn"> Back </a>
                        <input type="submit" value="update" name="update_item_submit" id="update-product-btn" class="submit-btn success-border">
                    </div>
                </div>
            </form>
        </section>
        <?php
        require "./pages/right-dashboard-panel.php";
        ?>
    </div>
    <script src="./scripts/sidebar.js"></script>
    <script src="./scripts/theme-togggler.js"></script>
    <script>

    </script>
</body>

</html>