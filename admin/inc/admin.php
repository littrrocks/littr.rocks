<?php
    session_start();

    $conn = new mysqli("localhost", "root", "", "littr");

    require "../inc/littr-functions.php";

    $littr = new littr();

    if(!isset($_SESSION['admin_privileges'])) {
        $littr->redir("../index");
    }else if($_SESSION['admin_privileges'] == false) {
        $littr->redir("../index");
    }else if(!isset($_SESSION['identifier'])) {
        $littr->redir("../index");
    };
?>