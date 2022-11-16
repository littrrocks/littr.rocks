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
        <img src="img/testomonials.png" alt="Testomonials" width=200>
        <div style="justify-content:center;margin-left:10px;vertical-align:top;display:inline-block;width:50%">
            <div><strong>A place for communication.</strong><br>
            <span>Explore groups, posts, and profiles. It's all happening on littr. It's easy and free to sign up for an account.</span></div>
        </div>
    </div>
</body>
</html>