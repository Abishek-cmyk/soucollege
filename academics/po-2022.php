<?php include 'db.php'; ?>
<?php
$res = mysqli_query($conn,
    "SELECT pdf_file FROM program_outcomes_pdf WHERE year='2022-2023'"
);
$row = mysqli_fetch_assoc($res);
?>
<iframe src="uploads/programme/<?= $row['pdf_file']; ?>"
        width="100%" height="600px"></iframe>
