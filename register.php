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
            $key = $_POST["invitekey"];

            $required = array('username', 'password', 'password2');
            $error = false;
            foreach($required as $field) {
                if (empty($_POST[$field])) {
                    $error = true;
                    $statusText = 4;
                }else{
                    if (ctype_alnum($username)) {
                        $stmt = $conn->prepare("SELECT * FROM inv WHERE key_encrypt = ?");
                        $stmt->bind_param("s", $key);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        while ($row = $result->fetch_assoc()) {
                            $db_key = $row["key_encrypt"];
                            $db_key_uses = $row["used"];
                            
                            if ($key == $db_key && $db_key_uses != 1) {
                                if ($password == $confirmPassword) {
                                    $password = password_hash($password, PASSWORD_DEFAULT);
                
                                    $idgeneration = new IdentifierGeneration();
                                    $id = $idgeneration->generate_id();
                
                                    $stmt = $conn->prepare("INSERT INTO users (`name`, `username`, `password`, `identifier`) VALUES (?, ?, ?, ?)");
                                    $stmt->bind_param("ssss", $username, $username, $password, $id);
                                    $stmt->execute();
                                    $stmt->close();

                                    $stmt = $conn->prepare("UPDATE inv SET used = 1 WHERE key_encrypt = ?");
                                    $stmt->bind_param("s", $key);
                                    $stmt->execute();
                                    $stmt->close();
                
                                    $littr->redir("login");
                                } else {
                                    $statusText = "1";
                                }
                            }else{
                                $statusText = "2";
                            }
                        }
                    }else{
                        $statusText = "3";
                    }
                }
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
                    <input type="password" name="password2" placeholder="Confirm Password" required><br>
                    <input type="text" name="invitekey" id="invitekey" placeholder="Invite Key" required><br><br>
                    <input type="submit" name="submit" value="Register"><br><br>
                    <?php
                        if (isset($statusText)) {
                            if ($statusText == "1") {
                                echo "<span style='color:red'>Passwords do not match!</span>";
                            }elseif ($statusText == "2") {
                                echo "<span style='color:red'>Invite key is either invalid or already used.</span>";
                            }else if ($statusText == "3") {
                                echo "<span style='color:red'>Username is not alphanumeric!</span>";
                            }else if ($statusText == "4") {
                                echo "<span style='color:red'>Please fill out all fields!</span>";
                            }
                        }
                    ?>
                </form>
                <sub>By clicking 'Register', you agree to our <a href="#">Community Guidelines</a> and <a href="#">Privacy Policy</a>.</sub>
            </div>
        </div>
    </div>
</body>
</html>