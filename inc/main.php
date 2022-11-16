<?php
    session_start();
    include "vendor/autoload.php";
    include 'inc/littr-functions.php';

    $conn = new mysqli("localhost", "root", "root", "littr");
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 
?>