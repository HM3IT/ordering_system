<?php
require "../../dao/connection.php";

// create a new user account into TABLE user
if (isset($_POST["create-account-submit"])) {
    $name = $_POST["name"];
    $price = $_POST["price"];
    $quantity =  $_POST["quantity"];
    $description =  $_POST["description"];
    $category = $_POST["category"];

    $image_file_name = $_FILES["image"]["name"];
    $image_file_size = $_FILES["image"]["size"];
    $image_file_tmp =  $_FILES["image"]["tmp_name"];
    $image_file_type = $_FILES["image"]["type"];
    $valid_file_extensions = array("png", "jpeg", "jpg", "svg", "jfif");
    $file_extension = strtolower(pathinfo($image_file_name, PATHINFO_EXTENSION));


    if (!in_array($file_extension, $valid_file_extensions)) {

        echo "<script> 
             alert('The image file " . $file_extension . " extension is not supported');
             location.href = '../add_product.php'; 
            </script>";
        // allowed up to 3MB 
        if ($image_file_size >= 1024 * 1024 * 3) {

            echo "<script> 
             alert('File size is too big');
             location.href = '../add_product.php'; 
            </script>";
        }
    }
    // path: uploaded image files 
    $target_img_dir = "../../images/Products/$category/";
    $target_file = $target_img_dir . basename($_FILES["image"]["name"]);

    if (move_uploaded_file($image_file_tmp, $target_file)) {
        $insert_product_sql = "INSERT INTO product (
            name,
            description,
            price,
            quantity,
            image,
            category
        )
    VALUES (
            '$name',
            '$description',
            $price,
            $quantity,
            '$image_file_name',
            '$category'
        );";
        $stmt =   $connection->query($insert_product_sql);
        echo '<script> 
            location.href = "../product_manager.php"; 
            </script>';
    } else {
        echo "target not found";
    }
}
