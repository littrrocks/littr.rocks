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
    <div class="l-MainContainer">
        <?php
            $littr = new littr();
            if (find_appropriate_header() == true) {
                include "inc/headers/in.php";

                $stmt = $conn->prepare("SELECT * FROM users WHERE identifier = ?");
                $stmt->bind_param("s", $_SESSION["identifier"]);
                $stmt->execute();
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()) {
                    $_SESSION['username'] = $row['username'];
                    
                    if ($row['suspended'] == 1) {
                        $littr->redir("suspended");
                    }
                    
                    if($row['admin_privileges'] == 1) {
                        $_SESSION['admin_privileges'] = true;
                    } else {
                        $_SESSION['admin_privileges'] = false;
                    }
                }
            }else{
                $littr->redir("login");
            }

            $username = $_GET['username'];
            $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                echo "<img class='pfp' src='" . $row['pfp'] . "'>";
                echo "<div id='profile'>";
                echo "<strong id='name'>" . htmlspecialchars($row['name']) . "</strong> ";
                if($row['verified'] == 1){
                echo "<img src='img/verified.png' style='width:1em;height:1em;'> ";
                }
                echo "<br>";
                echo "<span style='color:gray;'>@" . htmlspecialchars($row['username']) . "</span>";
                if($row['category'] != ""){
                    echo "<span style='color:lightgray;'> | </span><span style='color:gray;'>" . htmlspecialchars($row['category']) . "</span><br>";
                }else{
                    echo "<br>";
                }
                echo "<span style='color:gray;font-size:0.8em'>Joined " . htmlspecialchars(time_elapsed_string($row['born'])) . "</span>";
                echo "<br>";
                echo "<br>";
                $stmt2 = $conn->prepare("SELECT * FROM posts WHERE identifier = ?");
                $stmt2->bind_param("s", $row['identifier']);
                $stmt2->execute();
                $result2 = $stmt2->get_result();
                while ($row2 = $result2->fetch_assoc()) {
                    echo "<div class='post'>";
                    echo "<img class='pfp' src='" . $row['pfp'] . "'>";
                    echo "<div id='content'>";
                    echo "<strong>" . htmlspecialchars($row['name']) . "</strong> ";
                    if($row['verified'] == 1){
                        echo "<img src='img/verified.png' style='width:13px;height:13px;'> ";
                    }
                    echo "<span id='handle'>@" . htmlspecialchars($row['username']) . "</span><br>";
                    echo "<span id='content'>" . htmlspecialchars($row2['content']) . "</span><br>";
                    if($row2['media_path'] != ""){
                        $fileExt = explode(".", $row2['media_path']);

                        if($fileExt[1] == "mp4"){
                            echo "
                            <video width=\"320\" controls style='border:1px solid black;margin:10px;'>
                                <source src=\"" . $row2['media_path'] . "\" type=\"video/mp4\">
                                Your browser does not support the video tag.
                            </video><br>
                            ";
                        }else{
                            echo "<img width=\"320\" style='border:1px solid black;margin:10px;' src='" . $row2['media_path'] . "'><br>";
                        }
                    }
                    echo "<span id='ago'>" . time_elapsed_string($row2['born']) . "</span>";
                    echo "</div>";
                    echo "</div>";
                }
            }

        ?>
    </div>
</body>
</html>