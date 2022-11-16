<?php
    include "inc/main.php";
    $littr = new littr();

    session_destroy();
    session_unset();

    $littr->redir("index");
?>