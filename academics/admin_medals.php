<?php
include 'auth.php';
include 'db.php';

/* ---------------- DELETE ---------------- */
if (isset($_GET['delete']) && isset($_GET['cat'])) {

    $id = (int)$_GET['delete'];
    $cat = $_GET['cat'];

    if ($cat == 'aided') {
        mysqli_query($conn, "DELETE FROM medals_aided WHERE id=$id");
    } else {
        mysqli_query($conn, "DELETE FROM medals_self WHERE id=$id");
    }

    echo "<script>alert('Medal Deleted');window.location='admin-medals.php';</script>";
}

/* ---------------- EDIT FETCH ---------------- */
$editData = null;
$editCat = "";

if (isset($_GET['edit']) && isset($_GET['cat'])) {

    $id = (int)$_GET['edit'];
    $editCat = $_GET['cat'];

    if ($editCat == 'aided') {
        $q = mysqli_query($conn, "SELECT * FROM medals_aided WHERE id=$id");
    } else {
        $q = mysqli_query($conn, "SELECT * FROM medals_self WHERE id=$id");
    }

    $editData = mysqli_fetch_assoc($q);
}

/* ---------------- UPDATE ---------------- */
if (isset($_POST['update'])) {

    $id = (int)$_POST['id'];
    $category = $_POST['category'];
    $medal_name = $_POST['medal_name'];
    $instituted_by = $_POST['instituted_by'];
    $code = $_POST['code'];

    if ($category == "aided") {
        $sql = "UPDATE medals_aided SET
                medal_name='$medal_name',
                instituted_by='$instituted_by',
                code='$code'
                WHERE id=$id";
    } else {
        $sql = "UPDATE medals_self SET
                medal_name='$medal_name',
                instituted_by='$instituted_by',
                code='$code'
                WHERE id=$id";
    }

    mysqli_query($conn, $sql);
    echo "<script>alert('Medal Updated');window.location='admin-medals.php';</script>";
}

/* ---------------- INSERT ---------------- */
if (isset($_POST['save'])) {

    $category = $_POST['category'];
    $medal_name = $_POST['medal_name'];
    $instituted_by = $_POST['instituted_by'];
    $code = $_POST['code'];

    if ($category == "aided") {
        $sql = "INSERT INTO medals_aided (medal_name, instituted_by, code)
                VALUES ('$medal_name','$instituted_by','$code')";
    } else {
        $sql = "INSERT INTO medals_self (medal_name, instituted_by, code)
                VALUES ('$medal_name','$instituted_by','$code')";
    }

    mysqli_query($conn, $sql);
    echo "<script>alert('Medal Uploaded Successfully');</script>";
}
?>

<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>List of Medals - Admin</title>
<link rel="stylesheet" href="./CSS/dept-admin.css">
</head>

<body>

<header class="site-header">
    <div class="wrap header-flex">
        <div class="header-spacer"></div>

        <div class="header-center">
            <h1 class="title">List of Medals</h1>
            <p class="subtitle">Admin Panel</p>
        </div>

        <form method="post" action="../index.php" class="logout-form">
            <button type="submit" name="logout" class="logout-btn">Logout</button>
        </form>
    </div>
</header>

<!-- ================= FORM ================= -->
<div class="wrap">
<div class="card">
<h2><?= $editData ? 'Edit Medal' : 'Upload Medal'; ?></h2>

<form method="POST">

<input type="hidden" name="id" value="<?= $editData['id'] ?? '' ?>">

<div class="admin-form-grid">

<div class="form-group">
<label>Category</label>
<select name="category" required>
    <option value="">-- Select --</option>
    <option value="aided" <?= ($editCat=='aided')?'selected':'' ?>>Aided</option>
    <option value="self" <?= ($editCat=='self')?'selected':'' ?>>Self Finance</option>
</select>
</div>

<div class="form-group">
<label>Medal Name</label>
<input type="text" name="medal_name"
value="<?= $editData['medal_name'] ?? '' ?>" required>
</div>

<div class="form-group">
<label>Instituted By</label>
<input type="text" name="instituted_by"
value="<?= $editData['instituted_by'] ?? '' ?>" required>
</div>

<div class="form-group">
<label>No / Code</label>
<input type="text" name="code"
value="<?= $editData['code'] ?? '' ?>">
</div>

</div>

<?php if ($editData) { ?>
<button type="submit" name="update">Update Medal</button>
<?php } else { ?>
<button type="submit" name="save">Upload Medal</button>
<?php } ?>

</form>
</div>
</div>

<!-- ================= AIDED TABLE ================= -->
<div class="wrap admin-table-container">
<h3>Aided Medals</h3>

<table class="faculty-table-admin">
<tr>
<th>S.No</th>
<th>Medal Name</th>
<th>Instituted By</th>
<th>Code</th>
<th>Action</th>
</tr>

<?php
$i=1;
$q = mysqli_query($conn,"SELECT * FROM medals_aided ORDER BY id ASC");
while($r=mysqli_fetch_assoc($q)){
?>
<tr>
<td><?= $i++ ?></td>
<td><?= $r['medal_name'] ?></td>
<td><?= $r['instituted_by'] ?></td>
<td><?= $r['code'] ?></td>
<td>
<a href="?edit=<?= $r['id'] ?>&cat=aided">Edit</a> |
<a href="?delete=<?= $r['id'] ?>&cat=aided"
onclick="return confirm('Delete this medal?')">Delete</a>
</td>
</tr>
<?php } ?>
</table>
</div>

<!-- ================= SELF TABLE ================= -->
<div class="wrap admin-table-container">
<h3>Self Finance Medals</h3>

<table class="faculty-table-admin">
<tr>
<th>S.No</th>
<th>Medal Name</th>
<th>Instituted By</th>
<th>Code</th>
<th>Action</th>
</tr>

<?php
$i=1;
$q = mysqli_query($conn,"SELECT * FROM medals_self ORDER BY id ASC");
while($r=mysqli_fetch_assoc($q)){
?>
<tr>
<td><?= $i++ ?></td>
<td><?= $r['medal_name'] ?></td>
<td><?= $r['instituted_by'] ?></td>
<td><?= $r['code'] ?></td>
<td>
<a href="?edit=<?= $r['id'] ?>&cat=self">Edit</a> |
<a href="?delete=<?= $r['id'] ?>&cat=self"
onclick="return confirm('Delete this medal?')">Delete</a>
</td>
</tr>
<?php } ?>
</table>
</div>

<footer class="site-footer">
<p>© 2025 Powered by Sourashtra College</p>
</footer>

</body>
</html>
