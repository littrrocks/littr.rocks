<?php
    include "inc/main.php";

    if(isset($_SESSION['username'])){
        $littr = new littr();
        $littr->redir("home");
    }
?>
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
    <div class="l-MainContainer">
        <div class="l-Header">
            <img src="img/littr.png" alt="littr" class="l-Logo">
        </div>
        <div class="l-displayCase a">
            <div class="l-divElement">
                <strong>A place to call home.</strong><span> littr is a free online service that allows anyone in the world to communicate with others. We've been around since November of 2022 under affc. Now, we're expanding from anonymity, and allowing everyone to be their free self.</span><br>
                <br><a class="l-boldAndRed" href="register">Register</a> or <a href="login">Log In</a>
            </div>
        </div>
        <?php
            $stmt = $conn->prepare("SELECT COUNT(id) FROM users");
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $count = mysqli_num_rows($result);
            }
        ?>
        <div class="l-displayCase" style='text-align:center;height:auto;'>
            <strong>Join the <?php echo $count; ?> people discussing on littr.</strong><br><br>
            <span class='circle-num'>1</span>
            <div style='display:inline-block;width:50%;vertical-align:top;text-align:left;'>
                <strong>Get a key</strong><br>
                <span><a href="https://discord.gg/littr">Join our Discord server</a> for key giveaways everyday!</span>
            </div>
        </div>
    </div>
</body>
</html>