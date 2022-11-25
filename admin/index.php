<?php include "inc/admin.php" ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/littr.css">
    <link rel="stylesheet" href="../css/silk-companion.css">
    <title>littr.rocks</title>
</head>
<body>
    <div class="l-MainContainer">
        <?php include "inc/headers/in.php" ?>
        <div class="l-displayCase a h-Decrease">
            <div class="l-divElement">
                <strong>Admin Panel</strong>
            </div>
        </div>
        <fieldset>
            <legend><strong>Statistics</strong></legend>
            <?php
                $stmt = $conn->prepare("SELECT COUNT(id), COUNT(suspended), COUNT(verified), COUNT(admin_privileges) FROM users");
                $stmt->execute();
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()) {
                    echo "<strong>Users:</strong> " . $row['COUNT(id)'] . "<br>";
                    echo "<strong>Administrators:</strong> " . $row['COUNT(admin_privileges)'] . "<br>";
                    echo "<strong>Verified Users:</strong> " . $row['COUNT(verified)'] . "<br>";
                    echo "<strong>Suspended Users:</strong> " . $row['COUNT(suspended)'] . "<br>";
                }
            ?>
        </fieldset><br>
    </div>
</body>
</html>