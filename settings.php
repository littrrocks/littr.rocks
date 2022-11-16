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
                <strong style='font-size:30px;'>Settings</strong>
            </div>
        </div>
        <?php
            if(isset($_POST['uploadPFP'])){
                $target_dir = "pfp/";
                $target_file = $target_dir . time() . "1-5" . rand(11111,99999) .  basename($_FILES["pfp"]["name"]);
                $uploadOk = 1;
                $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
                $check = getimagesize($_FILES["pfp"]["tmp_name"]);
                if($check !== false) {
                    $uploadOk = 1;
                } else {
                    $uploadOk = 0;
                }
                if ($_FILES["pfp"]["size"] > 500000) {
                    $uploadOk = 0;
                }
                if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
                    $uploadOk = 0;
                }
                if($_FILES["pfp"]["error"] != 0) {
                    //stands for any kind of errors happen during the uploading
                    $uploadOk = 0;
                } 
                if ($uploadOk == 0) {
                    echo "Sorry, your file was not uploaded.";
                } else {
                    if (move_uploaded_file($_FILES["pfp"]["tmp_name"], $target_file)) {
                        $image = new \Gumlet\ImageResize($target_file);
                        $image->resize(100, 100);
                        $new = $target_dir . $_SESSION['id'] . "." . $imageFileType;
                        $image->save($new);

                        fopen($target_file, "a");
                        unlink($target_file);

                        $stmt = $conn->prepare("UPDATE users SET pfp = ? WHERE identifier = ?");
                        $stmt->bind_param("ss", $new, $_SESSION["identifier"]);
                        $stmt->execute();
                        $littr->redir("settings");
                    } else {
                        echo "Sorry, there was an error uploading your file.";
                    }
                }
            }

            if(isset($_POST['publicinfosubmit'])){
                $required = array('display_name', 'bio', 'category');
                $error = false;
                foreach($required as $field) {
                    if (empty($_POST[$field])) {
                        $error = true;
                    }
                }
                if ($error) {
                    echo "Please fill out all fields.";
                } else {
                    $stmt = $conn->prepare("UPDATE users SET name = ?, bio = ?, category = ? WHERE identifier = ?");
                    $stmt->bind_param("ssss", $_POST['display_name'], $_POST['bio'], $_POST['category'], $_SESSION["identifier"]);
                    $stmt->execute();
                    $littr->redir("settings");
                }
            }

            $stmt = $conn->prepare("SELECT verified FROM users WHERE identifier = ?");
            $stmt->bind_param("s", $_SESSION["identifier"]);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                if ($row['verified'] == 0) {
                    $VS196F = "
                        <div>
                            <span>Your account is not verified. You must be verified through someone internally at littr.</span>
                        </div>
                    ";
                }else{
                    $VS196F = "
                        <div>
                            <strong><img src='img/verified.png' height=15> Congratulations! Your account is verified.</strong><br>
                            <span>Verified users can...</span>
                            <ul>
                                <li>Attach media alongside their post <sup>(10MB, PNG, JPG, JPEG, and MP4 files permitted)<sup></li>
                                <li>Recieve a blue checkmark that proves their authenticity</li>
                                <li>Be able to choose and display their category <sup>(journalist, public figure, musician, etc.)</sup></li>
                                <li>...and more to come in the following months</li>
                            </ul><br>
                            <strong>Remember: verification may be revoked if:</strong><br>
                            <ul>
                                <li>You are found to be impersonating someone else</li>
                                <li>You are found to post inhumane content to the platform that is misleading or violates our Community Guidelines</li>
                                <li>You are found to have \"purchased\" the verified account off of a third-party seller or website</li>
                            </ul><br>
                            <span>littr reserves the right to revoke or remove verification at any given point. There is a zero-tolerance policy if you gain an infraction from breaking the Community Guidelines.</span>
                        </div>
                    ";
                }
            }
        ?>
        <form method="post">
            <fieldset>
                <legend><strong>Public Information</strong></legend>
                <?php
                    $stmt = $conn->prepare("SELECT * FROM users WHERE identifier = ?");
                    $stmt->bind_param("s", $_SESSION["identifier"]);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    while ($row = $result->fetch_assoc()) {
                        echo "
                            <span>Display Name </span><input type='text' name='display_name' value='" . $row['name'] . "'><br>
                            <span>Category </span>
                            <select name=\"category\" id=\"category\">
                                <option value=\"Journalist\">Journalist</option>
                                <option value=\"Public Figure\">Public Figure</option>
                                <option value=\"Musician\">Musician</option>
                                <option value=\"Artist\">Artist</option>
                                <option value=\"Business\">Business</option>
                                <option value=\"Corporation\">Corporation</option>
                                <option value=\"Service\">Service</option>
                                <option value=\"Art Gallery\">Art Gallery</option>
                                <option value=\"Restaurant\">Restaurant</option>
                                <option value=\"Government\">Government</option>
                                <option value=\"Military\">Military</option>
                                <option value=\"Bakery\">Bakery</option>
                                <option value=\"Bar\">Bar</option>
                                <option value=\"Bookstore\">Bookstore</option>
                                <option value=\"Clothing Store\">Clothing Store</option>
                                <option value=\"Convenience Store\">Convenience Store</option>
                                <option value=\"Department Store\">Department Store</option>
                                <option value=\"Electronics Store\">Electronics Store</option>
                                <option value=\"Furniture Store\">Furniture Store</option>
                                <option value=\"Grocery Store\">Grocery Store</option>
                                <option value=\"Hardware Store\">Hardware Store</option>
                            </select>
                            <br>
                            <span style='vertical-align:top'>Biography </span><textarea style='resize:none;' name='bio' rows='4' cols='50'>" . $row['bio'] . "</textarea><br>
                            <input type='submit' name='publicinfosubmit' value='Save'>
                        ";
                    }
                ?>
            </fieldset>
        </form><br>
        <form method="post" enctype="multipart/form-data">
            <fieldset>
                <legend><strong>Profile Picture</strong></legend>
                <input type="file" name="pfp" id="pfp">
                <input type="submit" value="Upload" name='uploadPFP'>
            </fieldset>
        </form><br>
        <fieldset>
            <legend><strong>Verification</strong></legend>
            <?php echo $VS196F; ?>
        </fieldset>
    </div>
</body>
</html>