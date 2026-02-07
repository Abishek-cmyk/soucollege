<?php include 'db.php'; ?>

<!DOCTYPE html>
<html>
<head>
<title>Value Based – Professional Ethics</title>
<link rel="stylesheet" href="./CSS/program.css">
</head>
<body>

<h1 class="main-heading">Value Based – Professional Ethics</h1>

<h2 class="sub-heading">Value Based Courses</h2>

<table class="po-table">
<tr>
    <th>Programme Name</th>
    <th>Course Name</th>
</tr>

<?php
$res = mysqli_query($conn,
    "SELECT * FROM value_based WHERE category='Value Based Courses'"
);
while ($row = mysqli_fetch_assoc($res)) {
?>
<tr>
    <td><?= htmlspecialchars($row['programme_name']); ?></td>
    <td><?= nl2br(htmlspecialchars($row['course_name'])); ?></td>
</tr>
<?php } ?>
</table>

<br><br>

<h2 class="sub-heading">Professional Ethics Courses</h2>

<table class="po-table">
<tr>
    <th>Programme Name</th>
    <th>Course Name</th>
</tr>

<?php
$res = mysqli_query($conn,
    "SELECT * FROM value_based WHERE category='Professional Ethics Courses'"
);
while ($row = mysqli_fetch_assoc($res)) {
?>
<tr>
    <td><?= htmlspecialchars($row['programme_name']); ?></td>
    <td><?= nl2br(htmlspecialchars($row['course_name'])); ?></td>
</tr>
<?php } ?>
</table>

</body>
</html>
