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
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>

    <?php require "./base_link_script.php";  ?>
    <link rel="stylesheet" href="./css/all_category_tbl.css" />
</head>

<body>
    <div id="main-container">
        <?php
        require "./pages/sidebar.php";
        ?>
        <?php
        require "../dao/connection.php";
        $get_all_product_sql = "SELECT * FROM category";
        ?>
        <section id="category-tbl-section" class="table-container-wrap">
            <h2>Product categories</h2>
            <table id="all-category-table">
                <tr>
                    <th>No.</th>
                    <th>Category name</th>
                    <th>Button Icon</th>
                    <th>Number of products</th>
                    <th>Action</th>
                </tr>

                <?php
                $serial = 1;
                foreach ($connection->query($get_all_product_sql) as $row) {
                    $category_id = $row['id'];
                    $category_name = $row['category_name'];
                    $get_all_order_sql = "SELECT COUNT(*) AS row_count FROM item WHERE category_id =  $category_id";
                    $result = $connection->query($get_all_order_sql);
                    $row_count = $result->fetchColumn();
                ?>
                    <tr>
                        <td><?php echo  $serial++  ?></td>
                        <td class="category-name-col">
                            <?php echo $row["category_name"]  ?>
                        </td>
                        <td>
                        <?php echo $row["category_icon"]  ?>
                        </td>
                        <td><?php echo $row_count ?></td>
                        <td>
                            <button type="button" class="edit-btn warning-border" data-category-name="<?php echo $category_name; ?>">Edit category</button>

                            <a href="./controller/category_controller.php?remove_category_id=<?php echo $row['id']; ?>" class="remove-btn danger-border <?php if ($row_count > 0 || $category_id == 8) echo 'disableLink'; ?>">
                                Remove
                            </a>


                        </td>
                    </tr>
                <?php
                }
                ?>

            </table>
        </section>
        <div id="overlay"></div>
        <div id="update-category-form">

            <div id="close-btn-relative">
                <i class="fa-solid fa-circle-xmark" id="close-update-category-form"></i>
                <i class="fa-regular fa-face-laugh-beam smilly-emoji"></i>

            </div>
            <p>Please proceed with Caution (Will affect the existing product that tag with this category) </p>
            <div>
                <form action="./controller/category_controller.php" method="post">
                    <div class="group">
                        <label for="old-category">Old category Name</label>
                        <input type="hidden" name="old-category" class="old-category-name">
                        <input type="text" name="old-category" class="old-category-name" disabled>
                    </div>
                    <div>
                        <label for="new-category">New category Name</label>
                        <input type="text" id="new-category" name="new-category" class="updated_category">
                    </div>
                    <div>
                        <label for="new-category">Category Icon</label>
                        <input type="text" id="category-icon" name="category-icon" class="updated_category">
                    </div>
                    <input type="submit" class="information-bg ask-login-btn" name="update-category" value="Update">
                </form>
            </div>
        </div>


        <?php
        require "./pages/right-dashboard-panel.php";
        ?>
    </div>
    <script src="./scripts/sidebar.js"></script>
    <script src="./scripts/theme-togggler.js"></script>
    <script>
        $(document).ready(function() {
            $(".disableLink").css({
                "background-color": "#e4d6d6",
                "border-color": "#fff5f5",
                "color": "#676464"
            });
            $(".disableLink").hover(
                function() {
                    $(this).addClass("no-pointer");
                },
                function() {
                    $(this).removeClass("no-pointer");
                }
            );
            $(".disableLink").click(function() {
                event.preventDefault();
                alert("cannot remove the category");
            });
            // update category popup
            $(".edit-btn").click(function() {
                $("#overlay").show();
                $("#update-category-form").show();

                var categoryName = $(this).attr("data-category-name");

                $(".old-category-name").val(categoryName);

            });

            $("#close-update-category-form").click(function() {
                $("#overlay").hide();
                $("#update-category-form").hide();
            });

            // Add category popup
            $("#add-category-btn").click(function() {
                $("#overlay").show();
                $("#add-category-form").show();
            });

            $("#close-add-category-form").click(function() {
                $("#overlay").hide();
                $("#add-category-form").hide();
            })
        });
    </script>
</body>


</html>