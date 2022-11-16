<?php?>
<?php
    include "inc/admin.php";

    if(!isset($_GET['table'])){
        $littr->redir("index");
    }
?>
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
                <strong>Database Lookup</strong><br>
                <span>in <?php echo $_GET['table']; ?></span>
            </div>
        </div>
        <strong>All data:</strong><br><br>
        <div class="wrap">
        <?php
            $stmt = $conn->prepare("SHOW COLUMNS FROM " . $_GET['table']);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $stmt2 = $conn->prepare("SELECT * FROM " . $_GET['table']);
                $stmt2->execute();
                $result2 = $stmt2->get_result();
                while ($row2 = $result2->fetch_assoc()) {
                    echo "<strong>" . $row['Field'] . "</strong>: " . $row2[$row['Field']] . "<br>";
                }
            }
        ?>
    </div></div>
</body>
</html>