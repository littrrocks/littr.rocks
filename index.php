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
            <div style='margin:auto;'>
                <img src="img/twitter-dead.jpg" alt="Dead Twitter Bird" width=200>
                <div style='display:inline-block;vertical-align:top;width:50%'>
                    <strong style='font-size:20px;'>The blue bird killer.</strong><br>
                    <span>We take our Community Guidelines very seriously - we have a zero-tolerance policy for any account that breaks our rules, and will make sure that bans are issued when needed.</span><br>
                    <ul>
                        <li>littr does not sell verification or any special priviledges that create a disadvantage to any user</li>
                        <li>littr enforces a zero-tolerance policy if a user or group violates the Community Guidelines</li>
                        <li>...and littr does not allow any pornographic content on the platform, unlike the blue bird app</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</body>
</html>