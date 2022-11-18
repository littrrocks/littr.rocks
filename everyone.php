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
        ?>
        <div class="l-displayCase a h-Decrease">
            <div class="l-divElement">
                <strong style='font-size:30px;'>Everyone</strong>
            </div>
        </div>
        <?php
            $stmt = $conn->prepare("SELECT verified FROM users WHERE identifier = ?");
            $stmt->bind_param("s", $_SESSION["identifier"]);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                if(isset($_POST['submit'])) {
                    $stmt = $conn->prepare("SELECT rate_limit FROM users WHERE username = ?");
                    $stmt->bind_param("s", $_SESSION['username']);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    while ($row = $result->fetch_assoc()) {
                        $rltime = strtotime($row['rate_limit']);

                        if($rltime > time()) {
                            $littr->redir("everyone?error=rate_limit");
                        }else{
                            $time = date("Y-m-d H:i:s", strtotime("+1 minute"));
                            $stmt = $conn->prepare("UPDATE users SET rate_limit = ? WHERE identifier = ?");
                            $stmt->bind_param("ss", $time, $_SESSION["identifier"]);
                            $stmt->execute();
                            $stmt->close();

                            if ($row['verified'] == 1) {
                                if($_FILES['file']['name'] != ''){
                                    $ID = new IdentifierGeneration();
                                    $tempID = $ID->generate_id($length = 30);
            
                                    $target_dir = "media/";
                                    $target_file = $target_dir . time() . $tempID . basename($_FILES["file"]["name"]);
                                    $uploadOk = 1;
                                    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        
                                    $check = getimagesize($_FILES["file"]["tmp_name"]);
                                    if($check !== false) {
                                      echo "File is an image - " . $check["mime"] . ".";
                                      $uploadOk = 1;
                                    } else {
                                      echo "File is not an image.";
                                      $uploadOk = 0;
                                    }
        
                                    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "mp4") {
                                        $uploadOk = 0;
                                        echo "no hablo extenstion ";
                                    }
                                    
            
                                    // Check if file already exists
                                    if (file_exists($target_file)) {
                                        $uploadOk = 0;
                                        echo "fyle exiss ";
                                    }
            
                                    // Check file size
                                    if ($_FILES["file"]["size"] > 100000000) {
                                        $uploadOk = 0;
                                        echo "ma u file power";
                                    }
            
                                    // Allow certain file formats
            
                                    // Check if $uploadOk is set to 0 by an error
                                    if ($uploadOk == 0) {
                                        echo "Sorry, your file was not uploaded.";
                                    // if everything is ok, try to upload file
                                    } else {
                                        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
                                            if($imageFileType != "mp4"){
                                                // generate an identifier
                                                $ID = new IdentifierGeneration();
                                                $set = $ID->generate_id($length = 50);
        
                                                $image = new \Gumlet\ImageResize($target_file);
                                                $output = $target_dir . $set . "." . $imageFileType;
                                                $image->save($output);
        
                                                unlink($target_file);
        
                                                $stmt = $conn->prepare("INSERT INTO posts (identifier, content, media_path) VALUES (?, ?, ?)");
                                                $stmt->bind_param("sss", $_SESSION["identifier"], $_POST['content'], $output);
                                                $stmt->execute();
                                            }else{
                                                $ID = new IdentifierGeneration();
                                                $set = $ID->generate_id($length = 50);
        
                                                $output = $target_dir . $set . "." . $imageFileType;
                                                rename($target_file, $output);
        
                                                $stmt = $conn->prepare("INSERT INTO posts (identifier, content, media_path) VALUES (?, ?, ?)");
                                                $stmt->bind_param("sss", $_SESSION["identifier"], $_POST['content'], $output);
                                                $stmt->execute();
                                            }
                                        } else {
                                            echo "Sorry, there was an error uploading your file.";
                                        }
                                    }
                                }else{
                                    $stmt = $conn->prepare("INSERT INTO posts (identifier, content) VALUES (?, ?)");
                                    $stmt->bind_param("ss", $_SESSION["identifier"], $_POST["content"]);
                                    $stmt->execute();
                                    $littr->redir("everyone");
                                }
                        
                            }else{
                                $stmt3 = $conn->prepare("INSERT INTO posts (identifier, content) VALUES (?, ?)");
                                $stmt3->bind_param("ss", $_SESSION["identifier"], $_POST["content"]);
                                $stmt3->execute();
                                $littr->redir("everyone");
                            }
                        }
                    }
                }
            }

            $error = $_GET['error'];

            if(isset($error)){
                if($error == "rate_limit"){
                    echo "<div class='l-Msg t-MsgError'>
                            <div class='l-divElement'>
                                <strong class='l-MHeader'>You are posting too fast!</strong>
                            </div>
                        </div><br>";
                }
            }
        ?>
        <form method="post" enctype="multipart/form-data">
            <textarea maxlength="300" name="content" onkeyup="textCounter(this,'counter',300);" id="content" style="resize:none;width:99%;font-family:Arial;height:54px"></textarea>
            <div>
                <span id='charactersRemaining'>300</span>
                <?php
                    $stmt = $conn->prepare("SELECT verified FROM users WHERE identifier = ?");
                    $stmt->bind_param("s", $_SESSION['identifier']);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    while ($row = $result->fetch_assoc()) {
                        if($row['verified'] == 1) {
                            echo "<span style='color:lightgray'>|</span>";
                            echo "<span> Attach media: </span>";
                            echo "<sup style='color:gray;font-size:10px'>PNG, JPEG/JPG, MP4</sup>";
                            echo "<input type='file' name='file' id='file'>";
                        }
                    }
                ?>
                <input type="submit" name="submit" value="Post" style="float:right">
            </div>
            <script>
                var el;                                                    

                function countCharacters(e) {                                    
                    var textEntered, countRemaining, counter;          
                    textEntered = document.getElementById('content').value;  
                    counter = (300 - (textEntered.length));
                    countRemaining = document.getElementById('charactersRemaining'); 
                    countRemaining.textContent = counter;       
                }
                el = document.getElementById('content');                   
                el.addEventListener('keyup', countCharacters, false);
            </script>
        </form><br>
        <?php
            $stmt = $conn->prepare("SELECT * FROM posts ORDER BY statusid DESC");
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $stmt2 = $conn->prepare("SELECT * FROM users WHERE identifier = ?");
                $stmt2->bind_param("s", $row['identifier']);
                $stmt2->execute();
                $result2 = $stmt2->get_result();
                while ($row2 = $result2->fetch_assoc()) {
                    echo "<div class='post'>";
                    echo "<img class='pfp' src='" . $row2['pfp'] . "'>";
                    echo "<div id='content'>";
                    echo "<strong>" . htmlspecialchars($row2['name']) . "</strong> ";
                    if($row2['verified'] == 1){
                        echo "<img src='img/verified.png' style='width:13px;height:13px;'> ";
                    }
                    echo "<span id='handle'>@" . htmlspecialchars($row2['username']) . "</span><br>";
                    echo "<span id='content'>" . htmlspecialchars($row['content']) . "</span><br>";
                    // if row is not empty
                    if($row['media_path'] != ""){
                        $fileExt = explode(".", $row['media_path']);

                        if($fileExt[1] == "mp4"){
                            echo "
                            <video width=\"320\" controls style='border:1px solid black;margin:10px;'>
                                <source src=\"" . $row['media_path'] . "\" type=\"video/mp4\">
                                Your browser does not support the video tag.
                            </video><br>
                            ";
                        }else{
                            echo "<img width=\"320\" style='border:1px solid black;margin:10px;' src='" . $row['media_path'] . "'><br>";
                        }
                    }
                    echo "<span id='ago'>" . time_elapsed_string($row['born']) . "</span>";
                    echo "</div>";
                    echo "</div>";
                }
            }
        ?>
    </div>
</body>
</html>