<?php
require "../../dao/connection.php";
$valid_file_extensions = array("png", "jpeg", "jpg", "svg", "webp", "jfif");
// removing product form TABLE product
if (isset($_GET["remove_product_id"])) {
    $id = $_GET["remove_product_id"];
    $product_delete_qry = "DELETE FROM product WHERE id =  $id  ";
    // $delete = mysqli_query($connection, $product_delete_qry);
    $stmt =   $connection->query($product_delete_qry);
    header("Location: ../product_manager.php");
    exit;
}

// updating the old product from TABLE product where id (product)
if (isset($_POST["update-product-submit"])) {
    $id = $_POST['id'];
    $name = $_POST["name"];
    $price = $_POST["price"];
    $discount = $_POST["discount"];
    $quantity =  $_POST["quantity"];
    $description =  $_POST["description"];
    $category_id = $_POST["category_id"];
    $today = date("Y-m-d");


    $get_category_sql = "SELECT * FROM category WHERE id = $category_id";
    $category_dataset = $connection->query($get_category_sql);
    $data =  $category_dataset->fetch();
    $category = $data["category_name"];


    // Image uploading processes
    $primary_img;
    $additional_images = array();
    $add_img_limit = 4;

    if (!empty($_FILES["primary_img"]["tmp_name"])) {
        $primary_image_file = $_FILES["primary_img"];
        $primary_img = $_FILES["primary_img"]["name"];
        processImageFile($primary_image_file, $category);
    }

    if (!empty($_FILES["additional_imgs"]["tmp_name"])) {
        $additional_image_files = $_FILES["additional_imgs"];
        for ($i = 0; $i < $add_img_limit; $i++) {
            if (!empty($additional_image_files["tmp_name"][$i])) {
                $additional_image_file = array(
                    "name" => $additional_image_files["name"][$i],
                    "size" => $additional_image_files["size"][$i],
                    "tmp_name" => $additional_image_files["tmp_name"][$i],
                    "type" => $additional_image_files["type"][$i]
                );
                $additional_images[$i] = $additional_image_files["name"][$i];
                processImageFile($additional_image_file, $category);
            }
        }
    }

    // Dynamically preparing the SET clause for the MySQL query
    $set_clause = "";
    if (isset($primary_img) && !empty($primary_img)) {
        $set_clause .= "primary_img = '$primary_img', ";
    }

    $additional_images_count = count($additional_images);
    for ($i = 0; $i < $add_img_limit; $i++) {
        if ($i < $additional_images_count) {
            $set_clause .= "additional_image" . ($i + 1) . " = '" . $additional_images[$i] . "', ";
        } else {
            $set_clause .= "additional_image" . ($i + 1) . " = additional_image" . ($i + 1) . ", ";
        }
    }
    // Remove the trailing comma and space from the SET clause
    $set_clause = rtrim($set_clause, ", ");
    $update_product_img_sql = "UPDATE images SET $set_clause WHERE product_id = '$id'";

    $connection->query($update_product_img_sql);

    $update_product_sql = "UPDATE product SET name = :name, description = :description, added_date = :today, price = :price, discount = :discount, quantity = :quantity, category_id = :category_id WHERE id = :id";

    $statement = $connection->prepare($update_product_sql);

    // Bind the values using placeholders
    $statement->bindParam(':name', $name);
    $statement->bindParam(':description', $description);
    $statement->bindParam(':today', $today);
    $statement->bindParam(':price', $price);
    $statement->bindParam(':discount', $discount);
    $statement->bindParam(':quantity', $quantity);
    $statement->bindParam(':category_id', $category_id);
    $statement->bindParam(':id', $id);

    if ($statement->execute()) {
        echo '<script> 
        alert("Successfully updated the product"); 
        location.href = "../product_manager.php"; 
        </script>';
    } else {
        // Update failed
        echo "Error updating product ";
    }
}



// inserting a new product into TABLE product
if (isset($_POST["add-product-submit"])) {
    $name = $_POST["name"];
    $price = $_POST["price"];
    $discount = $_POST["discount"];
    $quantity =  $_POST["quantity"];
    $description =  $_POST["description"];
    $category_id = $_POST["category_id"];
    $today = date("Y-m-d");

    $get_category_sql = "SELECT * FROM category WHERE id = $category_id";
    $category_dataset = $connection->query($get_category_sql);
    $data =  $category_dataset->fetch();
    $category = $data["category_name"];

    $primary_image_file = $_FILES["primary_img"];
    $primary_img = $_FILES["primary_img"]["name"];
    processImageFile($primary_image_file, $category);

    // preparting the values for the query
    $values = array("primary_img" => $primary_img);

    $additional_image_files = $_FILES["additional_imgs"];
    $additional_images = array();
    $add_img_limit = 4;

    for ($i = 0; $i < $add_img_limit; $i++) {
        if (!empty($additional_image_files["tmp_name"][$i])) {
            $additional_image_file = array(
                "name" => $additional_image_files["name"][$i],
                "size" => $additional_image_files["size"][$i],
                "tmp_name" => $additional_image_files["tmp_name"][$i],
                "type" => $additional_image_files["type"][$i]
            );
            $values["additional_image" . ($i + 1)]  = $additional_image_files["name"][$i];
            processImageFile($additional_image_file, $category);
        }
    }

    $new_product_insert_qry = "INSERT INTO product 
    (name, description, added_date, price, discount, quantity, category_id) 
    VALUES (:name, :description, :today, :price, :discount, :quantity, :category_id)";

    $statement = $connection->prepare($new_product_insert_qry);

    // Bind the values using placeholders
    $statement->bindParam(':name', $name);
    $statement->bindParam(':description', $description);
    $statement->bindParam(':today', $today);
    $statement->bindParam(':price', $price);
    $statement->bindParam(':discount', $discount);
    $statement->bindParam(':quantity', $quantity);
    $statement->bindParam(':category_id', $category_id);

    // Execute the statement
    $statement->execute();

    $newProductId = $connection->lastInsertId();

    $values["product_id"] = $newProductId;

    // Dynamically preparing the SET clause for the MySQL query
    $columns = implode(", ", array_keys($values));
    $placeholders = "'" . implode("', '", array_values($values)) . "'";

    $image_insert_query = "INSERT INTO images ($columns) VALUES ($placeholders)";


    if ($connection->query($image_insert_query)) {
        echo '<script> 
       alert("Successfully added the product"); 
       location.href = "../product_manager.php"; 
       </script>';
    } else {
        // Update failed
        echo "Adding product Error   ";
    }
}

function processImageFile($imageFile, $category)
{
    $valid_file_extensions = array("jpg", "jpeg", "png", "gif");

    $image_file_name = $imageFile["name"];
    $image_file_size = $imageFile["size"];
    $image_file_tmp = $imageFile["tmp_name"];
    $image_file_type = $imageFile["type"];

    $file_extension = strtolower(pathinfo($image_file_name, PATHINFO_EXTENSION));

    if (!in_array($file_extension, $valid_file_extensions)) {
        echo "<script> 
            alert('The image file " . $file_extension . " extension is not supported');
            location.href = '../add_product.php'; 
            </script>";
        return;
    }

    // allowed up to 3MB 
    if ($image_file_size >= 1024 * 1024 * 3) {
        echo "<script> 
            alert('The image file size is too big');
            location.href = '../add_product.php'; 
            </script>";
        return;
    }

    // path: uploaded image files 
    $target_img_dir = "../../images/Products/$category/";

    if (!is_dir($target_img_dir)) {
        // Create the directory if it doesn't exist
        mkdir($target_img_dir, 0777, true);
    }

    $target_file = $target_img_dir . basename($image_file_name);
    if (move_uploaded_file($image_file_tmp, $target_file)) {
        // Image file successfully uploaded
    } else {
        echo "Target not found";
    }
}
