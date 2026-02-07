<?php include 'auth.php'; ?>
<?php include 'db.php'; ?>

<?php
/* ================= ADD ================= */
if (isset($_POST['add_po'])) {
    mysqli_query($conn,
        "INSERT INTO value_based (category, programme_name, course_name)
         VALUES (
            '{$_POST['category']}',
            '{$_POST['programme']}',
            '{$_POST['course']}'
         )"
    );
    echo "<script>alert('Value Based Added');</script>";
}

/* ================= UPDATE ================= */
if (isset($_POST['update_po'])) {
    mysqli_query($conn,
        "UPDATE value_based SET
            category='{$_POST['category']}',
            programme_name='{$_POST['programme']}',
            course_name='{$_POST['course']}'
         WHERE id='{$_POST['id']}'"
    );
    echo "<script>alert('Value Based Updated');</script>";
}

/* ================= DELETE ================= */
if (isset($_GET['delete'])) {
    mysqli_query($conn,
        "DELETE FROM value_based WHERE id='{$_GET['delete']}'"
    );
    echo "<script>alert('Value Based Deleted');</script>";
}

/* ================= EDIT FETCH ================= */
$editData = null;
if (isset($_GET['edit'])) {
    $res = mysqli_query($conn,
        "SELECT * FROM value_based WHERE id='{$_GET['edit']}'"
    );
    $editData = mysqli_fetch_assoc($res);
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin – Value Based</title>
<link rel="stylesheet" href="./CSS/admin-vb.css">
</head>
<body>

<h1 class="main-heading">Admin – Value Based - Professional Ethics</h1>

<div class="logout-box">
    <a href="../index.php" class="logout-btn">Logout</a>
</div>

<!-- ================= FORM ================= -->
<div class="form-box">
<h2><?= $editData ? "Edit Value Based" : "Add Value Based"; ?></h2>

<form method="POST">

<?php if ($editData) { ?>
    <input type="hidden" name="id" value="<?= $editData['id']; ?>">
<?php } ?>

<label>Category</label>
<select name="category" required>
    <option value="">-- Select --</option>
    <option value="Value Based Courses"
        <?= ($editData && $editData['category']=="Value Based Courses")?"selected":""; ?>>
        Value Based Courses
    </option>
    <option value="Professional Ethics Courses"
        <?= ($editData && $editData['category']=="Professional Ethics Courses")?"selected":""; ?>>
        Professional Ethics Courses
    </option>
</select><br><br>

<label>Programme Name</label>
<input type="text" name="programme"
       value="<?= $editData['programme_name'] ?? ''; ?>" required><br><br>

<label>Course Name</label>
<textarea name="course" rows="4" required><?= $editData['course_name'] ?? ''; ?></textarea><br><br>

<?php if ($editData) { ?>
    <button type="submit" name="update_po">Update</button>
    <a href="admin-po.php" class="delete-btn">Cancel</a>
<?php } else { ?>
    <button type="submit" name="add_po">Add</button>
<?php } ?>

</form>
</div>

<hr>

<h2 class="sub-heading">Program Outcomes List</h2>

<!-- ================= TABLE ================= -->
<table class="po-table">
    <thead>
        <tr>
            <th>Category</th>
            <th>Programme</th>
            <th>Course Name</th>
            <th>Action</th>
        </tr>
    </thead>

    <tbody>
    <?php
    $res = mysqli_query($conn, "SELECT * FROM value_based");
    while ($row = mysqli_fetch_assoc($res)) {
    ?>
        <tr>
            <td><?= htmlspecialchars($row['category']); ?></td>

            <td><?= htmlspecialchars($row['programme_name']); ?></td>

            <td><?= nl2br(htmlspecialchars($row['course_name'])); ?></td>

            <td class="action-col">
                <a href="?edit=<?= $row['id']; ?>" class="edit-btn">Edit</a>
                <a href="?delete=<?= $row['id']; ?>"
                   class="delete-btn"
                   onclick="return confirm('Are you sure you want to delete?');">
                   Delete
                </a>
            </td>
        </tr>
    <?php } ?>
    </tbody>
</table>

</body>
</html>
