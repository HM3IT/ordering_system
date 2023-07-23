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
require "../dao/connection.php";
if (isset($_GET["update_user_id"])) {
    $id = $_GET["update_user_id"];
    $get_user_baseID_sql = "SELECT * FROM users WHERE id = $id";
    $resultSet = $connection->query($get_user_baseID_sql);
    $data = $resultSet->fetch();

    $user_level_id = $data["user_level_id"];
    $get_all_user_level_sql = "SELECT * FROM user_levels";
    $user_level_dataset = $connection->query($get_all_user_level_sql);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php require "./base_link_script.php";  ?>
    <link rel="stylesheet" href="./css/user_account_form.css">
</head>

<body>

    <div id="main-container">
        <?php
        require "./pages/sidebar.php";
        ?>
        <section class="user-form-section">
            <h2 class="title warning">create User Account</h2>
            <form class="user-form" action="controller/user_account_controller.php" method="post" enctype="multipart/form-data">
                <div>
                    <input type="hidden" name="id"  value="<?php echo $data["id"] ?>">
                    <div class="inline-block">
                        <label for="name">Username</label>
                        <input type="text" name="name" id="name" value="<?php echo $data["name"] ?>" required>
                    </div>

                    <div class="inline-block">
                        <label for="user_level">User level:</label>
                        <select id="user_level" name="user_level_id" required>
                            <?php while ($user_level_data = $user_level_dataset->fetch()) { ?>
                                <option value="<?php echo $user_level_data["id"]; ?>" <?php if ($user_level_data["id"] ==  $user_level_id) echo "selected"; ?>>
                                    <?php echo $user_level_data["user_level"]; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="inline-block">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" value="<?php echo $data["email"] ?>" required />

                    </div>
                    <div class="inline-block">
                        <label for="phone">Phone number:</label>
                        <input type="tel" name="phone" id="phone" value="<?php echo $data["phone"] ?>" required>
                    </div>
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" value="<?php echo $data["password"] ?>" required />


                    <button type="button" id="togglePassword">Show</button>

                    <label for="address" class="block">Address:</label>
                    <textarea name="address" id="address" cols="30" rows="5" required><?php echo $data["address"] ?></textarea>

                    <div class="inline-block">
                        <label for="profile_img">Select Profile Image:</label>
                        <input type="file" name="profile_img" id="profile_img">
                        <input type="hidden" name="old_profile_img" value="<?php echo $data["image"] ?>">
                    </div>

                    <div class="button-flex">
                        <input type="reset" value="Cancel" class="cancel-btn danger-border">
                        <input type="submit" value="Update" name="update-user-submit" class="submit-btn success-border">
                    </div>
                </div>
            </form>
        </section>
        <script src="./scripts/password_toggle.js">

        </script>
        <?php
        require "./pages/right-dashboard-panel.php";
        ?>
    </div>


    <script src="./scripts/sidebar.js"></script>
    <script src="./scripts/theme-togggler.js"></script>

</body>

</html>