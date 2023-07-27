<?php
require "../../dao/connection.php";

if (session_status() == PHP_SESSION_NONE) {
    session_set_cookie_params(0);
    session_start();
}

if (isset($_POST["Sign-Up"])) {
    $name = $_POST["name"];
    $password = $_POST["password"];
    $encryptedPassword = hash('sha512', $password);
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $address = $_POST["address"];
    $image_name = 'default-user-img.jpg';
    $created_date = time();

    // Prepare the statement
    $statement = $connection->prepare("INSERT INTO customer (image, name, phone, email, password, address, created_date) VALUES (?, ?, ?, ?, ?, ?, ?)");

    // Bind the parameters
    $statement->bindParam(1,  $image_name);
    $statement->bindParam(2, $name);
    $statement->bindParam(3, $phone);
    $statement->bindParam(4, $email);
    $statement->bindParam(5, $encryptedPassword);
    $statement->bindParam(6, $address);
    $statement->bindParam(7, $created_date);
    $statement->execute();

    $lastInsertedId = $connection->lastInsertId();
    $_SESSION["login_customer_id"] = $lastInsertedId;

    $_SESSION["customer_name"] = $name;
    $_SESSION["email"] = $email;
    // $_SESSION["password"] = $password;

    if (isset($_POST["current_page"])) {
        $redirectPage = $_POST["current_page"];
    } else {
        $redirectPage = "index.php";
    }

    echo '<script> 
            alert("Account is successfully created"); 
            location.href = "../' . $redirectPage . '"; 
        </script>';
}

if (isset($_POST["Sign-In"])) {
    $name = $_POST["name"];
    $user_password = $_POST["password"];
    $encryptedPassword = hash('sha512', $user_password);

    $get_all_user_qry = "SELECT * from customer";
    $dataset = $connection->query($get_all_user_qry);

    foreach ($dataset as $data) {

        if ($data["name"] === $name && $data["password"] === $encryptedPassword) {
            $_SESSION["login_customer_id"] = $data["id"];
            $_SESSION["customer_name"] = $name;
            // $_SESSION["password"] = $password;
            $_SESSION["status-login"] = "valid";

            if (isset($_POST["current_page"])) {
                $redirectPage = $_POST["current_page"];   
                header("Location: ../" . $redirectPage);
                exit;
            }
            header("Location: ../login.php");
            exit;
        }
    }
    $_SESSION["status-login"] = "invalid";
    header("Location: ../login.php");
    exit;
}

if (isset($_GET["logout"])) {
    // Clear all session variables & destroy the session
    $_SESSION = array();
    session_destroy();
    header("Cache-Control: no-cache, no-store, must-revalidate");
    header("Pragma: no-cache");
    header("Expires: 0");
    header("Location: ../index.php");
    exit();
}

if (isset($_POST["update-customer"])) {
    $customer_id = $_SESSION["login_customer_id"];
    $old_customer_data = "SELECT * FROM customer WHERE id = $customer_id";
    $customer_dataset = $connection->query($old_customer_data);
    $customer_data = $customer_dataset->fetch();

    // customer's old data
    $name = $customer_data["name"];
    $email =   $customer_data["email"];
    $phone =   $customer_data["phone"];
    $address =  $customer_data["address"];
    $image_file_name =  $customer_data["image"];

    // if there has value, the old values will be overriden
    $name =  trim($_POST["name"]);
    $email =   trim($_POST["email"]);
    $phone =   trim($_POST["phone"]);
    $address =   trim($_POST["address"]);


    //checking whether the user upload new image or not
    if (!empty($_FILES["image"]["tmp_name"])) {
        $image_file_name = $_FILES["image"]["name"];
        $image_file_size = $_FILES["image"]["size"];
        $image_file_tmp =  $_FILES["image"]["tmp_name"];
        $image_file_type = $_FILES["image"]["type"];
        $valid_file_extensions = array("png", "jpeg", "jpg", "svg", "jfif", "webp");

        $file_extension = strtolower(pathinfo($image_file_name, PATHINFO_EXTENSION));

        if (!in_array($file_extension, $valid_file_extensions)) {

            echo '<script> 
                 alert("The image file " . $file_extension . " extension is not supported");
                 location.href = "../setting.php"; 
                </script>';
            // allowed up to 3MB 
            if ($image_file_size >= 1024 * 1024 * 3) {

                echo '<script> 
                 alert("File size is too big  (allowed up to 3 MB)");
                 location.href = "../setting.php"; 
                </script>';
            }
        }
        // path: uploaded image files 
        $target_img_dir = "../../images/User/";
        $target_file = $target_img_dir . basename($image_file_name);
        if (move_uploaded_file($image_file_tmp, $target_file)) {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            //improve performance by caching image file name
            $_SESSION["image"] = $image_file_name;
        } else {
            echo "target folder not found";
        }
    }

    $update_customer_sql = "UPDATE customer SET 
        image = '$image_file_name',
        name = '$name', 
        phone = '$phone', 
        address = '$address'
        WHERE id = '$customer_id'";

    if ($connection->query($update_customer_sql)) {
        header("Location:../setting.php");
    } else {
        // Update failed
        echo "Error updating account information ";
    }
}

// change password
if (!empty($_POST["new-password"])) {
    $new_password = $_POST["new-password"];
    $current_customer_id = $_SESSION["login_customer_id"];
    $encryptedPassword = hash('sha512', $new_password);

    $update_customer_sql = "UPDATE customer SET 
    password = '$encryptedPassword'
    WHERE id = $current_customer_id";

    if ($connection->query($update_customer_sql)) {
        echo '<script> 
        alert("Successfully changed password"); 
        location.href = "../setting.php"; 
        </script>';
    } else {
        // Update failed
        echo "Error updating password ";
    }
}
