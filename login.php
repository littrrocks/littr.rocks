<?php include "inc/main.php" ?>
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

            $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $db_username = $row["username"];
                $db_password = $row["password"];

                if (password_verify($password, $db_password)) {
                    $_SESSION["identifier"] = $row["identifier"];
                    $_SESSION['id'] = $row['id'];
                    $littr->redir("home");
                }else{
                    $statusText = "1";
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
                <strong>Log In</strong><br>
                <span style='font-size:12px'>Don't have an account? <a href="register">Register</a></span><br><br>
                <form method="post">
                    <input type="text" name="username" placeholder="Username" required><br>
                    <input type="password" name="password" placeholder="Password" required><br><br>
                    <input type="submit" name="submit" value="Log In"><br><br>
                    <?php
                        if (isset($statusText)) {
                            if ($statusText == "1") {
                                echo "<span style='color:red'>Password is incorrect</span>";
                            }
                        }
                    ?>
                </form><br>
            </div>
        </div>
    </div>
</body>
</html>