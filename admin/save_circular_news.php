<?php
include "../db.php";

/* ===============================
   FORM DATA
================================ */
$id            = $_POST['id'] ?? '';
$type          = $_POST['type'];
$title         = mysqli_real_escape_string($conn,$_POST['title']);
$content       = mysqli_real_escape_string($conn,$_POST['content']);
$url           = mysqli_real_escape_string($conn,$_POST['url']);
$existing_file = $_POST['existing_file'] ?? '';

$file_link = $existing_file;

/* ===============================
   FILE UPLOAD
================================ */
$uploadDir = "../uploads/files/";

if(!is_dir($uploadDir)){
    mkdir($uploadDir,0777,true);
}

if(!empty($_FILES['file']['name'])){

    // delete old file while editing
    if(!empty($existing_file) && file_exists("../".$existing_file)){
        unlink("../".$existing_file);
    }

    $fileName = time().'_'.basename($_FILES['file']['name']);
    move_uploaded_file($_FILES['file']['tmp_name'],$uploadDir.$fileName);

    $file_link = "uploads/files/".$fileName;
    $url = ""; // PDF irundha URL remove
}

/* ===============================
   INSERT / UPDATE
================================ */
if($id){
    mysqli_query($conn,"UPDATE news_events SET
        type='$type',
        title='$title',
        content='$content',
        url='$url',
        file_link='$file_link'
        WHERE id=$id
    ");
}else{
    mysqli_query($conn,"INSERT INTO news_events
        (type,title,content,url,file_link,created_at)
        VALUES
        ('$type','$title','$content','$url','$file_link',NOW())
    ");
}

header("Location: dashboard.php");
exit;
