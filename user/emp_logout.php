<?php
    session_start();
    unset($_SESSION['emp_id']);
    session_destroy();

    header("Location:../home.php");
    exit;