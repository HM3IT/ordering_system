<?php
require "../../dao/connection.php";

if (isset($_POST["add-category"])) {
    $new_category = $_POST["category"];

    $insert_category_query = "INSERT INTO category (category_name) VALUES (:category_name)";

    $stmt = $connection->prepare($insert_category_query);

    $stmt->bindParam(':category_name', $new_category);

    $stmt->execute();

    echo "
    <script>
        alert('Category has been added');
        location.href='../category_manager.php';
    </script>";
}

if (isset($_POST["update-category"])) {


    $old_category = $_POST["old-category"];
    $new_category = $old_category;
    if (isset($_POST["new-category"]) && !empty($_POST["new-category"])) {
        $new_category = $_POST["new-category"];
        // Renaming the folder of a certain item that hold images

        $target_img_dir = "../../images/Menu_items/$old_category/";
        $new_img_dir = "../../images/Menu_items/$new_category/";

        if (is_dir($target_img_dir) && !is_dir($new_img_dir)) {
            if (rename($target_img_dir, $new_img_dir)) {
                echo "Folder renamed successfully!";
            } else {
                echo "Failed to rename folder.";
            }
        }
    }

    $update_category_query = "UPDATE category SET category_name = :new_category";
    $bind_values = array(':new_category' => $new_category);
    
    if (isset($_POST["category-icon"]) && !empty($_POST["category-icon"])) {
        $category_icon = $_POST["category-icon"];
        $update_category_query .= ", category_icon = :category_icon";
        $bind_values[':category_icon'] = $category_icon;
    }
 
    $update_category_query .= " WHERE category_name = :old_category";
    $bind_values[':old_category'] = $old_category;

    $stmt = $connection->prepare($update_category_query);

    if ($stmt->execute($bind_values)) {
        echo "Category updated successfully!";
    } else {
        echo "Failed to update category.";
    }
   
    echo "
    <script>
        alert('Category has been updated');
        // location.href='../category_manager.php';
    </script>";
    exit;
}

if (isset($_REQUEST["remove_category_id"])) {
    $remove_category_id = $_REQUEST["remove_category_id"];

    $remove_category_qry = "DELETE FROM category WHERE id = :category_id";
    $stmt = $connection->prepare($remove_category_qry);
    $stmt->bindParam(":category_id", $remove_category_id, PDO::PARAM_INT);
    $stmt->execute();

    echo "
    <script>
        alert('Category has been removed');
        location.href='../category_manager.php';
    </script>";
}
