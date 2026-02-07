<?php include 'auth.php'; ?>
<?php
include 'db.php';

$q = mysqli_query($conn,"SELECT * FROM admission_pdf ORDER BY id DESC LIMIT 1");
$data = mysqli_fetch_assoc($q);

if($data){
    $pdfPath = "uploads/admission/".$data['pdf_file'];
    header("Location: $pdfPath");
    exit;
}else{
    echo "Admission PDF not uploaded yet.";
}
?>
