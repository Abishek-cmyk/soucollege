<?php
include "../db.php";

$id   = (int)$_GET['id'];
$type = $_GET['type'];

$q = mysqli_query($conn,"SELECT file_link,type FROM news_events WHERE id=$id");
$row = mysqli_fetch_assoc($q);

/* safety check */
if($row && $row['type'] === $type){

    if(!empty($row['file_link']) && file_exists("../".$row['file_link'])){
        unlink("../".$row['file_link']);
    }

    mysqli_query($conn,"DELETE FROM news_events WHERE id=$id AND type='$type'");
}

header("Location: dashboard.php");
exit;
