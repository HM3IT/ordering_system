<?php
require "../../dao/connection.php";


if(isset($_POST["add-category"])) {
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

if(isset($_POST["update-category"])){
 
    $old_category = $_POST["old-category"];
    $new_category =$_POST["new-category"];
    $update_category_query = "UPDATE category SET category_name = '$new_category' WHERE category_name = '$old_category'";


    $connection->query($update_category_query);
    echo "
    <script>
        alert('Category has been updaetd');
        location.href='../category_manager.php';
    </script>";
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

