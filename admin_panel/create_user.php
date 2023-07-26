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
$get_user_level = "SELECT * FROM user_levels";
$dataset = $connection->query($get_user_level);
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
                    <input type="hidden" name="id">
                    <div class="inline-block">
                        <label for="name">Username</label>
                        <input type="text" name="name" id="name" placeholder="e.g  Mg Mg" required>
                    </div>

                    <div class="inline-block">
                        <label for="user_level">User level:</label>
                        <select id="user_level" name="user_level_id" required>
                            <?php while ($user_level_data = $dataset->fetch()) { ?>
                                <option value="<?php echo $user_level_data["id"]; ?>">
                                    <?php echo $user_level_data["user_level"]; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="inline-block">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" placeholder="***@gmail.com" required />

                    </div>
                    <div class="inline-block">
                        <label for="phone">Phone number:</label>
                        <input type="tel" name="phone" id="phone" placeholder="09 123 456 789" required>
                    </div>

                    <label for="password">Password:</label>
                    <input type="password" id="set_password" required />
                    <button type="button" id="togglePassword">Show</button>


                    <label for="address" class="block">Address:</label>
                    <textarea name="address" id="address" cols="30" rows="5" required></textarea>

                    <div class="inline-block">
                        <label for="profile_img">Select Profile Image:</label>
                        <input type="file" name="profile_img" id="profile_img" required>
                    </div>

                    <div class="button-flex">
                        <input type="reset" value="Cancel" class="cancel-btn danger-border">
                        <input type="submit" value="Create" name="create-user-submit" class="submit-btn success-border">
                    </div>
                </div>
            </form>


        </section>
        <?php
        require "./pages/right-dashboard-panel.php";
        ?>
    </div>
    <script>
        $(document).ready(function() {
            $("#togglePassword").click(function() {
                var passwordInput = $("#set_password");
                var togglePasswordButton = $("#togglePassword");

                if (passwordInput.attr("type") === "password") {
                    passwordInput.attr("type", "text");
                    togglePasswordButton.text("Hide");
                    togglePasswordButton.css("background-color", "orange");
                } else {
                    passwordInput.attr("type", "password");
                    togglePasswordButton.text("Show");
                    togglePasswordButton.css("background-color", "blue"); 
                }
            });
        });
    </script>
    <script src="./scripts/sidebar.js"></script>
    <script src="./scripts/theme-togggler.js"></script>
    <script>

    </script>
</body>

</html>