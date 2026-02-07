<?php
include 'db.php';
$res = mysqli_query($conn,"SELECT pdf_file FROM fee_structure_pdf WHERE type='self'");
$row = mysqli_fetch_assoc($res);
?>
<iframe src="uploads/fee/<?= $row['pdf_file']; ?>" width="100%" height="600px"></iframe>
