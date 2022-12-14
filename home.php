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
        <div class="l-displayCase a">
            <div class="l-divElement">
                <?php
                        $stmt = $conn->prepare("SELECT verified, pfp FROM users WHERE identifier = ?");
                        $stmt->bind_param("s", $_SESSION["identifier"]);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        while ($row = $result->fetch_assoc()) {
                            $pfp = $row['pfp'];
                            if ($row['verified'] == 1) {
                                $verified = " <img src='img/verified.png' width=13>";
                            }else{
                                $verified = "";
                            }
                        }

                    if($_SESSION['admin_privileges'] == true) {
                        echo "<img style='border:1px solid black;' src='" . $pfp . "' alt='Profile Picture' width=18>";
                        echo "<strong style='font-size:20px'> Welcome, @" . htmlspecialchars($_SESSION['username']) . "</strong><br><br>";
                        echo "<a class='l-boldAndRed' href='admin/index'><strong>Admin Panel</strong></a>";
                    } else {
                        echo "<img style='border:1px solid black;' src='" . $pfp . "' alt='Profile Picture' width=18>";
                        echo "<strong style='font-size:20px'> Welcome, @" . htmlspecialchars($_SESSION['username']) . "</strong>";
                        echo $verified;
                    }
                ?>
            </div>
        </div>
        <?php
            $stmt = $conn->prepare("SELECT verified FROM users WHERE identifier = ?");
            $stmt->bind_param("s", $_SESSION["identifier"]);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                if(isset($_POST['submit'])) {
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
                            $littr->redir("home");
                        }
                
                    }else{
                        $stmt3 = $conn->prepare("INSERT INTO posts (identifier, content) VALUES (?, ?)");
                        $stmt3->bind_param("ss", $_SESSION["identifier"], $_POST["content"]);
                        $stmt3->execute();
                        $littr->redir("home");
                    }
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
        </form>
    </div>
</body>
</html>