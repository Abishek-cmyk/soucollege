<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Handbook – Self</title>

    <!-- USER SIDE HANDBOOK CSS -->
    <link rel="stylesheet" href="css/handbook.css">
</head>
<body>

<div class="handbook-box">
    <div class="handbook-title">HANDBOOK - SELF</div>

    <div class="handbook-list">
        <?php
        $res = mysqli_query($conn,
            "SELECT * FROM handbooks 
             WHERE type='self' 
             ORDER BY year ASC"
        );

        if(mysqli_num_rows($res) > 0){
            while($row = mysqli_fetch_assoc($res)){
        ?>
            <a href="../uploads/handbook/self/<?= htmlspecialchars($row['pdf_file']); ?>"
               target="_blank">
               College Handbook <?= htmlspecialchars($row['year']); ?>
            </a>
        <?php 
            }
        } else {
            echo "<p style='text-align:center;color:#555;'>No handbooks uploaded</p>";
        }
        ?>
    </div>
</div>

</body>
</html>
