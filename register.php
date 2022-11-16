<?php include "inc/main.php"; mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/littr.css">
    <title>littr.rocks</title>
</head>
<body>
    <?php
        $littr = new littr();

        if (isset($_POST["submit"])){
            $username = $_POST["username"];
            $password = $_POST["password"];
            $confirmPassword = $_POST["password2"];

            if (ctype_alnum($username)) {
                if ($password == $confirmPassword) {
                    $password = password_hash($password, PASSWORD_DEFAULT);

                    $idgeneration = new IdentifierGeneration();
                    $id = $idgeneration->generate_id();

                    $stmt = $conn->prepare("INSERT INTO users (`username`, `password`, `identifier`) VALUES (?, ?, ?)");
                    $stmt->bind_param("sss", $username, $password, $id);
                    $stmt->execute();
                    $stmt->close();

                    $littr->redir("login");
                } else {
                    $statusText = "1";
                }
            }else{
                $statusText = "3";
            }
        }
    ?>
    <div class="l-MainContainer">
        <div class="l-Header">
            <img src="img/littr.png" alt="littr" class="l-Logo">
        </div>
        <div class="l-displayCase a h-Increase b-NoBorder m-DecreaseMargin">
            <div class="l-divElement">
                <strong>Register</strong><br>
                <span style='font-size:12px'>Already have an account? <a href="login">Log In</a></span><br><br>
                <form method="post">
                    <input type="text" name="username" placeholder="Username" required><br>
                    <input type="password" name="password" placeholder="Password" required><br>
                    <input type="password" name="password2" placeholder="Confirm Password" required><br><br>
                    <input type="submit" name="submit" value="Register"><br><br>
                    <?php
                        if (isset($statusText)) {
                            if ($statusText == "1") {
                                echo "<span style='color:red'>Passwords do not match!</span>";
                            }else if ($statusText == "3") {
                                echo "<span style='color:red'>Username is not alphanumeric!</span>";
                            }
                        }
                    ?>
                </form><br>
                <sub>By clicking 'Register', you agree to our <a href="#">Community Guidelines</a> and <a href="#">Privacy Policy</a>.</sub>
            </div>
        </div>
    </div>
</body>
</html>