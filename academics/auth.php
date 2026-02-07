<?php
session_start();
if(!isset($_SESSION['dept_user'])){
    header("Location: login.php");
    exit();
}
?>
