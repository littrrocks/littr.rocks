<?php
    session_start();
    include "../vendor/autoload.php";
    include '../inc/littr-functions.php';

    $conn = new mysqli("localhost", "root", "root", "littr");
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 

    $littr = new littr();

    if(!isset($_SESSION['admin_privileges'])) {
        $littr->redir("../index");
    }else if($_SESSION['admin_privileges'] == false) {
        $littr->redir("../index");
    }else if(!isset($_SESSION['identifier'])) {
        $littr->redir("../index");
    };
?>