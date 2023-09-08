<?php
if (!isset($_SESSION)) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="./css/login.css">
</head>

<body>
    <div class='container'>
        <div>
            <h2>Ordering System For Cafe and Bakery stores</h2>
            <h3>Available functionalities of the System</h3>
            <table id="admin-function">
                <tbody>
                    <td><i class="fa-regular fa-square-check"></i></td>
                    <td>
                        <p>Admin account - setting</p>
                    </td>
                    </tr>
                    <tr>
                        <td><i class="fa-regular fa-square-check"></i></td>
                        <td>
                            <p>CRUD menu item, and user tables </p>
                        </td>
                    </tr>
                    <tr>
                        <td><i class="fa-regular fa-square-check"></i></td>
                        <td>
                            <p>Review order information</p>
                        </td>
                    </tr>
                    <tr>
                        <td><i class="fa-regular fa-square-check"></i></td>
                        <td>
                            <p>Search and sort functionalities</p>
                        </td>
                    </tr>
                    <tr>
                        <td><i class="fa-regular fa-square-check"></i></td>
                        <td>
                            <p>Daily & Monthly sales </p>
                        </td>
                    </tr>

                    <tr>
                        <td><i class="fa-regular fa-square-check"></i></td>
                        <td>
                            <p>Top 5 Popular Menu Items</p>
                        </td>
                    </tr>
                </tbody>
            </table>

            <table id="moderator-function">
                <tbody>
                    <td><i class="fa-regular fa-square-check"></i></td>
                    <td>
                        <p>Moderatorr account - setting</p>
                    </td>
                    </tr>
                    <tr>
                        <td><i class="fa-regular fa-square-check"></i></td>
                        <td>
                            <p>CRUD menu item tables </p>
                        </td>
                    </tr>
                    <tr>
                        <td><i class="fa-regular fa-square-check"></i></td>
                        <td>
                            <p>Review order information</p>
                        </td>
                    </tr>
                    <tr>
                        <td><i class="fa-regular fa-square-check"></i></td>
                        <td>
                            <p>Search and sort functionalities</p>
                        </td>
                    </tr>
                    <tr>
                        <td><i class="fa-regular fa-square-check"></i></td>
                        <td>
                            <p>Daily & Monthly sales </p>
                        </td>
                    </tr>

                    <tr>
                        <td><i class="fa-regular fa-square-check"></i></td>
                        <td>
                            <p>Top 5 Popular Menu Items</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>  
        <div class='window'>
            <div class='overlay'></div>

            <div class='content'>
                <form action="./controller/login_controller.php" method="POST">
                    <div class='welcome'>Login</div>
                    <div class='subtitle'>Please fill the required information to verify the authentication</div>

                    <label for="login-as">Login As: </label>
                    <select name="user_level_id" id="login-as">
                        <option value="1">Admin</option>
                        <option value="2">Moderator</option>
                    </select>

                    <div class='input-fields'>
                        <input type='text' placeholder='Username' name="name" class='input-line full-width' required></input>
                        <input type='password' placeholder='Password' name="password" class='input-line full-width' required></input>
                    </div>
                    <div>
                        <input type="submit" class="white-round full-width" name="sign-in-btn" value="sign in">
                    </div>
                </form>
            </div>

        </div>
    </div>
    <?php
    if (isset($_SESSION["status-login"])) {
        $status = $_SESSION["status-login"];
        echo "console.log('set')";
        if ($status == "invalid") {
    ?>
            <div id="user-authentication-noti-overlay"></div>
            <div id="user-authentication-noti-form">
                <div>
                    <i class="fa-solid fa-face-grin-beam-sweat" id="sad-emoji"></i>
                    <h2>Wrong password or username </h2>
                </div>
                <a href="./login.php" class="information-bg">OK</a>
            </div>
        <?php
        } else if ($status == "valid") {
        ?>
            <div id="user-authentication-cart-noti-overlay"></div>
            <div id="user-authentication-noti-form">
                <div>
                    <i class="fa-regular fa-face-laugh-beam" id="smilly-emoji"></i>
                    <h2>Yey!, login successfully </h2>
                </div>
                <a href="./dashboard.php" class="information-bg">OK</a>
            </div>
    <?php
        }
        // Clear the status once it's displayed
        unset($_SESSION["status-login"]);
    }
    ?>
    <script>
        $(document).ready(function() {

            $("#login-as").on("change", function() {
                var selectedValue = $(this).val();
                
                if (selectedValue === "1") {
                    $("#admin-function").show();
                    $("#moderator-function").hide();
                } else if (selectedValue === "2") {
                    $("#admin-function").hide();
                    $("#moderator-function").show();
                } else {

                    $("#admin-function").hide();
                    $("#moderator-function").hide();
                }
            });
        });
    </script>
</body>

</html>