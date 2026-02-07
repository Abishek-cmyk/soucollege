<?php include 'db.php'; ?>

<?php
$res = mysqli_query($conn,
    "SELECT pdf_file FROM program_outcomes 
     ORDER BY id DESC 
     LIMIT 1"
);
$row = mysqli_fetch_assoc($res);
?>

<!DOCTYPE html>
<html>
<head>
<title>Program Outcomes</title>
</head>
<body>

<h2 style="text-align:center;">Program Outcomes</h2>

<?php if(!empty($row['pdf_file'])) { ?>

<iframe 
    src="uploads/programme/<?= $row['pdf_file']; ?>" 
    width="100%" 
    height="700px"
    style="border:none;">
</iframe>

<?php } else { ?>

<p style="text-align:center;">No Program Outcome PDF uploaded</p>

<?php } ?>

</body>
</html>
