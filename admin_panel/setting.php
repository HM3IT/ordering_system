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
if (empty($_SESSION["authentication"])) {
    echo '
    <script> 
        alert("Please navigate with the provided setting button"); 
        location.href = "../login.php"; 
    </script>';
}
require "../dao/connection.php";

$get_admin_password = "SELECT * FROM admin WHERE id='1'";
$dataset = $connection->query($get_admin_password);
$data = $dataset->fetch();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <?php require "./base_link_script.php";  ?>
    
    <link rel="stylesheet" href="./css/mid-dashboard-panel.css" />
    <link rel="stylesheet" href="./css/setting.css" />
</head>

<body>
    <div id="main-container">
        <?php
        require "./pages/sidebar.php";
        ?>
        <section id="setting">
            <div>
                <h2 class="title">Account Setting</h2>
                <form action="./controller/admin_controller.php" method="POST" enctype="multipart/form-data">
                    <div class="admin-img">
                        <img src="../images/User/<?php echo $data["image"] ?>" alt="admin.png">
                    </div>
                    <div class="grid-form">

                        <div class="group">
                            <label for="name">Admin Name</label>
                            <input type="text" id="name" name="name" value=" <?php echo $data["name"] ?>" required>
                        </div>
                        <div class="group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" value=" <?php echo $data["email"] ?>" required>
                        </div>
                        <div class="group">
                            <label for="phone">Phone</label>
                            <input type="tel" id="phone" name="phone" value=" <?php echo $data["phone"] ?>" required>
                        </div>
                        <div class="group">
                            <label for="name">Change image</label>
                            <input type="file" id="image" name="image">
                        </div>
                        <div class="group">
                            <label for="question" class="danger">Question</label>
                            <input type="text" id="question" name="question" value=" <?php echo $data["question"] ?>" required>
                        </div>
                        <div class="group">
                            <label for="answer" class="danger">Answer</label>
                            <input type="text" id="answer" name="answer" value=" <?php echo $data["answer"] ?>" required>
                        </div>
                    </div>
                    <a id="change-admin-password-btn" class="danger-border" onclick="openChangePasswordForm()">Change password</a>
                    <input type="submit" id="update-admin-btn" class="warning-bg" value="update" name="update-admin">
                </form>
            </div>

            <!-- Popup form for change password -->
            <div id="popup-form-change-password" class="change-password-overlay">
                <div class="popup-form-change-password">
                    <h2>Change Password</h2>
                    <form action="./controller/admin_controller.php" method="POST" id="popup-change-password-form" onsubmit="validateForm(event)">
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
        require "./pages/right-dashboard-panel.php";
        ?>
    </div>
</body>
<script src="./scripts/change_password.js"></script>
<script src="./scripts/sidebar.js"></script>
<script src="./scripts/theme-togggler.js"></script>

</html>