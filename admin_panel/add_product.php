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

$get_categories_sql = "SELECT * FROM category";
$category_dataset = $connection->query($get_categories_sql);
 

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
            <h2 class="title warning">Add product Form</h2>
            <form class="product-form" action="controller/product_controller.php" method="post" enctype="multipart/form-data">
                <div>
                    <input type="hidden" name="id">

                    <label for="name">Enter product name</label>
                    <input type="text" name="name" id="name" required>

                    <label for="description">Product Specification: </label>
                    <textarea name="description" id="description" cols="30" rows="10" required></textarea>

                    <div class="inline-block">
                        <label for="price">Price:</label>
                        <input type="text" id="price" name="price" pattern="[0-9]*" inputmode="numeric" placeholder="Enter price in Kyat" required>

                        <label for="discount">Discount percent:</label>
                        <input type="number" name="discount" id="discount" min="0" max="100" placeholder="in percent">
                    </div>

                    <div class="inline-block">
                        <label for="category">Category:</label>
                        <select id="category" name="category_id" required>
                            <?php while ($category_data = $category_dataset->fetch()) { ?>
                                <option value="<?php echo $category_data["id"]; ?>">
                                    <?php echo $category_data["category_name"]; ?>
                                </option>
                            <?php } ?>
                        </select>

                        <label for="quantity">Quantity:</label>
                        <input type="number" id="quantity" name="quantity" min="1" max="100">
                    </div>
                    <div class="inline-block">
                        <label for="primary_img">Select Main Featured Image:</label>
                        <input type="file" name="primary_img" id="primary_img" required>
                    </div>

                    <div class="inline-block">
                        <label for="additional_imgs">Additional images (up to 4):</label>
                        <input type="file" name="additional_imgs[]" id="additional_imgs" multiple accept="image/*" max="4" required>
                    </div>

                    <div class="button-flex">
                        <input type="reset" value="Cancel" class="cancel-btn danger-border">
                        <input type="submit" value="submit" name="add-product-submit" id="add-product-btn" class="submit-btn success-border">
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