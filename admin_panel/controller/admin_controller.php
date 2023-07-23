<?php
require "../../dao/connection.php";

if(!isset($_SESSION)){
    session_set_cookie_params(0);
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
}

if (isset($_POST["sign-in-btn"])) {
    $name =  $_POST["name"];
    $answer =   $_POST["answer"];
    $password = str_replace(' ', '', $_POST["password"]);

    $encryptedPassword = hash('sha512', $password);

    $get_admin_qry = "SELECT * FROM admin";
    $dataset = $connection->query($get_admin_qry);
    $data = $dataset->fetch();

    if (
        $name === $data["name"] &&
        $answer === $data["answer"] &&
        $encryptedPassword ===  $data["password"]
    ) {
        // Set session cookie parameters

        $_SESSION["name"] = $name;
        $_SESSION["email"] = $data["email"];
        $_SESSION["phone"] = $data["phone"];
        $_SESSION["image"] = $data["image"];
        $_SESSION["admin_id"] = $data["id"];

        $_SESSION["status"] = "login";
        $_SESSION["status-login"] = "valid";
    } else {
        $_SESSION["status-login"] = "invalid";
    }

    header("Location: ../login.php");
    exit;
}
// ajuthentication check pop up form
if (isset($_POST["authentication-check-submit"])) {
    $password = str_replace(' ', '', $_POST["password"]);
    $encryptedPassword = hash('sha512', $password);

    $admin_id =  $_SESSION["admin_id"];
    $get_admin_password = "SELECT password FROM admin WHERE id=$admin_id";
    $dataset = $connection->query($get_admin_password);
    $data = $dataset->fetch();

    if ($encryptedPassword ===  $data["password"]) {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        // for security
        $_SESSION["authentication"] = "checked";
        header("Location: ../setting.php");
    } else {
        echo '
    <script> 
        alert("Invalid authentication!"); 
        location.href = "../index.php"; 
    </script>';
    }
}

// admin information update
if (isset($_POST["update-admin"])) {
    $name =  trim($_POST["name"]);
    $email =   trim($_POST["email"]);
    $phone =   trim($_POST["phone"]);
    $question =   ucfirst(trim($_POST["question"]));
    $answer =   trim($_POST["answer"]);

    // default admin's image name
    $image_file_name = "admin.png";
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

    $update_admin_sql = "UPDATE admin SET 
    image = '$image_file_name',
    name = '$name', 
    phone = '$phone', 
    question = '$question', 
    answer = '$answer' 
    WHERE id = '1'";

    if ($connection->query($update_admin_sql)) {
        echo '<script> 
        alert("Successfully updated admin account"); 
        location.href = "../setting.php"; 
        </script>';
    } else {
        // Update failed
        echo "Error updating account information ";
    }
}


// change password

if (!empty($_POST["new-password"])) {
    $new_password = $_POST["new-password"];
    $encryptedPassword = hash('sha512', $new_password);

    $admin_id =  $_SESSION["admin_id"];

    $update_admin_sql = "UPDATE admin SET 
    password = '$encryptedPassword'
    WHERE id =  $admin_id";

    if ($connection->query($update_admin_sql)) {
        echo '<script> 
        alert("Successfully changed password"); 
        location.href = "../login.php"; 
        </script>';
    } else {
        // Update failed
        echo "Error updating password ";
    }
}


if (isset($_GET["logout"])) {
    // Clear all session variables & destroy the session
    session_unset();
    session_destroy();

    header("Location: ../login.php");
    exit();
}
