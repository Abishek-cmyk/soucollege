<?php include "db.php"; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Aided Awards</title>
    <link rel="stylesheet" href="CSS/medals.css">
</head>
<body>

<h1 class="page-title">List of Medals <span>(Aided)</span></h1>

<table class="medal-table">
<tr>
    <th>S.No</th>
    <th>Medal Name</th>
    <th>Instituted By</th>
    <th>No</th>
</tr>

<?php
$i = 1;
$q = mysqli_query($conn, "SELECT * FROM medals_aided ORDER BY id ASC");
while ($row = mysqli_fetch_assoc($q)) {
?>
<tr>
    <td><?= $i++; ?></td>
    <td><?= $row['medal_name']; ?></td>
    <td><?= $row['instituted_by']; ?></td>
    <td><?= $row['code']; ?></td>
</tr>
<?php } ?>
</table>

<footer class="site-footer">
    <p>© 2025 Powered by Sourashtra College</p>
</footer>

</body>
</html>
