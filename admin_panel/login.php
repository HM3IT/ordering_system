<?php
if (!isset($_SESSION)) {
    session_start();
}
require "../dao/connection.php";
$get_admin_qry = "SELECT question FROM admin WHERE id='1'";
$dataset = $connection->query($get_admin_qry);
$data = $dataset->fetch();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="./css/login.css">
</head>

<body>
    <div class='container'>
        <div class='window'>
            <div class='overlay'></div>

            <div class='content'>
                <form action="./controller/admin_controller.php" method="POST">
                    <div class='welcome'>Login</div>
                    <div class='subtitle'>Please fill the required information to verify the admin authentication</div>

                    <div class='input-fields'>
                        <input type='text' placeholder='Username' name="name" class='input-line full-width' required></input>
                        <input type='text' placeholder='<?php echo $data["question"] ?>' name="answer" class='input-line full-width' required></input>
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
                <a href="./index.php" class="information-bg">OK</a>
            </div>
    <?php
        }
        // Clear the status once it's displayed
        unset($_SESSION["status-login"]);
    }
    ?>
</body>

</html>