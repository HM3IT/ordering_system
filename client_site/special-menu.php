<?php
if (!isset($_SESSION)) {
    session_start();
}
require "../dao/connection.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ordering System</title>

    <?php require "./components/base-link.php" ?>
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/search-bar.css" />
    <link rel="stylesheet" href="css/pagination-sort.css" />
    <link rel="stylesheet" href="css/product-section.css">
    <link rel="stylesheet" href="css/swiper.css">
    <link rel="stylesheet" href="css/product-slider.css">
    <link rel="stylesheet" href="css/alert-box.css" />
    <Style>
        #sidebar {
            height: 100%;
        }

        #pagination {
            display: block;
            text-align: center;
            margin: 45px auto;
        }
    </Style>
</head>

<body>

    <div id="main-container">
        <?php
        require './components/alert-box.php';
        require './components/cart-list.php';
        require './components/sidebar.php';

        $category_id = 8;
        $get_special_menu_sql = "SELECT * FROM item WHERE  category_id =$category_id";
        $all_dataset = $connection->query($get_special_menu_sql);
        $total_items = $all_dataset->rowCount();
        ?>
        <div>
            <?php

            require './components/navbar.php';

            $item_per_page = 12;
            $page_num = 1;

            if (isset($_REQUEST["page-num"])) {
                $page_num = $_REQUEST["page-num"];
            }
            $offset = ($page_num - 1) * $item_per_page;
            $get_item_per_page;

            if (isset($_POST["sort-type"])) {
                $selectedValue = $_POST['sort-type'];
                $orderBy = '';
                $_SESSION["sort-type"] = $selectedValue;

                switch ($selectedValue) {
                    case 'discount':
                        $orderBy = 'ORDER BY discount DESC';
                        break;
                    case 'price-lowest':
                        $orderBy = 'ORDER BY (price - (price * discount / 100)) ASC';
                        break;
                    case 'price-highest':
                        $orderBy = 'ORDER BY (price - (price * discount / 100)) DESC';
                        break;
                    case 'popular':
                        $orderBy = 'ORDER BY sold_quantity DESC';
                        break;
                    default:
                        $orderBy = 'ORDER BY (price - (price * discount / 100)) ASC';
                        break;
                }
                $get_item_per_page = "$get_special_menu_sql $orderBy LIMIT $offset, $item_per_page";
                $_SESSION["ordby"] = $orderBy;
            } else if (isset($_SESSION["ordby"])) {
                $orderBy = $_SESSION["ordby"];
                $get_item_per_page = "$get_special_menu_sql $orderBy LIMIT $offset, $item_per_page";
            } else {

                $get_item_per_page = "$get_special_menu_sql ORDER BY id LIMIT $offset, $item_per_page";
            }

            $dataset = $connection->query($get_item_per_page);
            ?>
            <div>
                <form action="" method="POST" id="sort-select-form">
                    <label for="sort">Sort with: </label>
                    <select name="sort-type" id="sort-select">
                        <?php
                        if (isset($_SESSION["sort-type"])) {
                        ?>
                            <option value="discount" <?php echo ($_SESSION["sort-type"] == "discount") ? "selected" : ""; ?>>Discount</option>
                            <option value="popular" <?php echo ($_SESSION["sort-type"] == "popular") ? "selected" : ""; ?>>Most popular</option>
                            <option value="price-lowest" <?php echo ($_SESSION["sort-type"] == "price-lowest") ? "selected" : ""; ?>>Price (Lowest)</option>
                            <option value="price-highest" <?php echo ($_SESSION["sort-type"] == "price-highest") ? "selected" : ""; ?>>Price (Highest)</option>
                        <?php

                        } else {
                        ?>
                            <option value="price-lowest">Price (Lowest)</option>
                            <option value="price-highest">Prrice (Highest)</option>
                            <option value="popular">Most popular </option>
                        <?php
                        }
                        ?>

                    </select>
                </form>
            </div>
            <?php require './components/product-section.php'; ?>

            <div id="pagination">

                <a href="menu.php?page-num=<?php echo ($page_num - 1); ?>#main-container" class="page-link previous-page" <?php if ($page_num == 1) {
                                                                                                                                echo 'onclick="return false;"';
                                                                                                                            } ?>>
                    <li class="page-item">Prev </li>
                </a>

                <?php
                $i = 1;

                $page_count = ceil($total_items / $item_per_page);
                while ($i <= $page_count) {

                    if ($i == $page_num) {
                ?>
                        <a href="menu.php?page-num=<?php echo $i ?>#main-container" class="page-link current-page active">
                            <li class="page-item">
                                <?php echo $i  ?>
                            </li>
                        </a>
                    <?php
                        $i++;
                        continue;
                    }
                    ?>
                    <a href="menu.php?page-num=<?php echo $i ?>#main-container" class="page-link">
                        <li class="page-item current-page">
                            <?php echo $i  ?>
                        </li>
                    </a>

                <?php
                    $i++;
                }
                ?>

                <a href="menu.php?page-num=<?php echo ($page_num + 1) ?>#main-container" class="page-link" <?php if ($page_num == $page_count) {
                                                                                                                echo 'onclick="return false;"';
                                                                                                            } ?>>
                    <li class="page-item next-page">
                        Next
                    </li>
                </a>

            </div>
            <?php
            require './components/product-slider.php';
            ?>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#sort-select').change(function() {
                $('#sort-select-form').submit();
            });
        });
    </script>

    <script src="scripts/navbar.js"> </script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
    <script src="scripts/swiper.js"> </script>
</body>

</html>