<?php
if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_SESSION["login_customer_id"])) {
    echo '
    <script> 
        alert("Please login the account first"); 
        location.href = "./login.php"; 
    </script>';
}
require "../dao/connection.php";

$customer_id = $_SESSION["login_customer_id"];
$get_customer_data = "SELECT * FROM customer WHERE id = $customer_id";
$customer_dataset = $connection->query($get_customer_data);
$customer_data = $customer_dataset->fetch();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php require "./components/base-link.php" ?>
    <link rel="stylesheet" href="css/setting.css">
    <link rel="stylesheet" href="css/feature-section.css">
    <link rel="stylesheet" href="css/newsletter.css">
</head>

<body>
    <?php

    define('COMPONENTS_PATH', './pages/');

    require COMPONENTS_PATH . 'navbar.php';
    ?>
    <section id="setting">
        <div class="user-img">
            <img src="../images/User/<?php echo $customer_data["image"] ?>" alt="user.png">
        </div>
        <div id="setting-container">
            <h2 class="title">Account Setting</h2>
            <form action="./controller/login_controller.php" method="POST" enctype="multipart/form-data">

                <div class="grid-form">

                    <div class="group">
                        <label for="name">User Name</label>
                        <input type="text" id="name" name="name" value=" <?php echo $customer_data["name"] ?>" required>
                    </div>
                    <div class="group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value=" <?php echo $customer_data["email"] ?>" required>
                    </div>
                    <div class="group">
                        <label for="phone">Phone</label>
                        <input type="tel" id="phone" name="phone" value=" <?php echo $customer_data["phone"] ?>" required>
                    </div>
                    <div class="group">
                        <label for="name">Change image</label>
                        <input type="file" id="image" name="image">
                    </div>
                    <div class="group">
                        <label for="address">Address</label>
                        <input type="text" id="address" name="address" value=" <?php echo $customer_data["address"] ?>" required>
                    </div>
                </div>
                <div id="setting-btns">
                    <a id="change-admin-password-btn" class="danger-border" onclick="openChangePasswordForm()">Change password</a>
                    <input type="submit" id="update-btn" class="information-bg" value="update" name="update-customer">
                </div>
            </form>
        </div>

        <!-- Popup form for change password -->
        <div id="popup-form-overlay" class="change-password-overlay">
            <div id="popup-form-change-password">
                <h2>Change Password</h2>
                <form action="./controller/login_controller.php" method="POST" id="popup-change-password-form" onsubmit="validateForm(event)">
                    <i class="fa-solid fa-circle-xmark" onclick="closeChangePasswordForm()"></i>
                    <label for="new-password">New Password:</label>
                    <input type="password" id="new-password" name="new-password" required>
                    <label for="confirm-password">Confirm New Password:</label>
                    <input type="password" id="confirm-password" name="confirm-password" required>
                    <p id="password-match-error" class="password-match-error"></p>
                    <input type="submit" name="change-password-submit" class="information-bg" value="Change">
                </form>
            </div>
        </div>
    </section>

    <?php

    require COMPONENTS_PATH . 'newsletter.html';
    require COMPONENTS_PATH . 'footer.html';
    ?>

    <script src="scripts/navbar.js"> </script>
    <script src="scripts/setting.js"> </script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
    <script src="scripts/swiper.js"> </script>
</body>

</html>