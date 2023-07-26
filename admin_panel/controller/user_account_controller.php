<?php
require "../../dao/connection.php";
$valid_file_extensions = array("png", "jpeg", "jpg", "svg", "webp", "jfif");
// remove user account form TABLE users
if (isset($_GET["remove_user_id"])) {
    $id = $_GET["remove_user_id"];
    $user_acc_delete_qry = "DELETE FROM users WHERE id =  $id  ";
    $stmt =   $connection->query($user_acc_delete_qry);
    header("Location: ../user_manager.php");
    exit;
}


// creating new user account
if (isset($_POST["create-user-submit"])) {
    $name = $_POST["name"];
    $password = $_POST["password"];
    $encryptedPassword = hash('sha512', $password);

    $phone = $_POST["phone"];
    $email =  $_POST["email"];
    $address =  $_POST["address"];
    $user_level_id = $_POST["user_level_id"];

    $profile_img_file = $_FILES["profile_img"];
    $profile_img_name = $_FILES["profile_img"]["name"];
    processImageFile($profile_img_file);

    // preparting the values for the query

    $new_product_insert_qry = "INSERT INTO users 
    (name, password, phone, email, address, image, user_level_id) 
    VALUES (:name, :password, :phone, :email, :address, :image, :user_level_id)";

    $statement = $connection->prepare($new_product_insert_qry);

    // Bind the values using placeholders
    $statement->bindParam(':name', $name);
    $statement->bindParam(':password', $encryptedPassword );
    $statement->bindParam(':phone', $phone);
    $statement->bindParam(':email', $email);
    $statement->bindParam(':address', $address);
    $statement->bindParam(':image', $profile_img_name);
    $statement->bindParam(':user_level_id', $user_level_id);

    // Execute the statement
    $statement->execute();
    echo '<script> 
       alert("Successfully added the product"); 
       location.href = "../user_manager.php"; 
       </script>';
}

if (isset($_POST["update-user-submit"])) {
    $id = $_POST['id'];
    $name = $_POST["name"];
    $password = $_POST["password"];
    $encryptedPassword = hash('sha512', $password);

    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $address =  $_POST["address"];
    $user_level_id =  $_POST["user_level_id"];

    $profile_img_name = $_POST["old_profile_img"];
 
    // Image updating processes
    if (!empty($_FILES["profile_img"]["tmp_name"])) {
        $profile_img_file = $_FILES["profile_img"];
        $profile_img_name = $_FILES["profile_img"]["name"];
        processImageFile($profile_img_file, $category);
    }

    $update_user_sql = "UPDATE users SET name = :name, password = :password, email = :email, phone = :phone, address = :address, user_level_id = :user_level_id WHERE id = :id";

    $statement = $connection->prepare($update_user_sql);

    // Bind the values using placeholders
    $statement->bindParam(':name', $name);
    $statement->bindParam(':password', $encryptedPassword);
    $statement->bindParam(':email', $email);
    $statement->bindParam(':phone', $phone);
    $statement->bindParam(':address', $address);
    $statement->bindParam(':user_level_id', $user_level_id);
    $statement->bindParam(':id', $id);

    if ($statement->execute()) {
        echo '<script> 
        alert("Successfully updated the user account"); 
        location.href = "../user_manager.php"; 
        </script>';
    } else {
        // Update failed
        echo "Error updating user account ";
    }
}




function processImageFile($imageFile)
{
    $valid_file_extensions = array("jpg", "jpeg", "png", "gif");

    $image_file_name = $imageFile["name"];
    $image_file_size = $imageFile["size"];
    $image_file_tmp = $imageFile["tmp_name"];

    $file_extension = strtolower(pathinfo($image_file_name, PATHINFO_EXTENSION));

    if (!in_array($file_extension, $valid_file_extensions)) {
        echo "<script> 
            alert('The image file " . $file_extension . " extension is not supported');
            location.href = '../user_manager.php'; 
            </script>";
        exit;
    }

    // allowed up to 3MB 
    if ($image_file_size >= 1024 * 1024 * 3) {
        echo "<script> 
            alert('The image file size is too big');
            location.href = '../user_manager.php'; 
            </script>";
        return;
    }

    // path: uploaded image files 
    $target_img_dir = "../../images/User/";

    if (!is_dir($target_img_dir)) {
        // Create the directory if it doesn't exist
        mkdir($target_img_dir, 0777, true);
    }

    $target_file = $target_img_dir . basename($image_file_name);
    if (move_uploaded_file($image_file_tmp, $target_file)) {
        // Image file successfully uploaded
    } else {
        echo "Target not found";
        exit;
    }
}
