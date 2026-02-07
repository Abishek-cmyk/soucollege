<?php include 'auth.php'; ?>
<?php
include 'db.php';

/* ===== UPLOAD ===== */
if (isset($_POST['upload_pdf'])) {

    $uploadDir = "uploads/admission/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileName = time() . "_" . $_FILES['pdf']['name'];
    $fileTmp  = $_FILES['pdf']['tmp_name'];

    if (move_uploaded_file($fileTmp, $uploadDir . $fileName)) {
        mysqli_query($conn, "INSERT INTO admission_pdf (pdf_file) VALUES ('$fileName')");
        echo "<script>alert('PDF Uploaded Successfully');</script>";
    }
}

/* ===== DELETE ===== */
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    $res = mysqli_query($conn, "SELECT pdf_file FROM admission_pdf WHERE id=$id");
    $row = mysqli_fetch_assoc($res);

    if ($row) {
        unlink("uploads/admission/" . $row['pdf_file']);
        mysqli_query($conn, "DELETE FROM admission_pdf WHERE id=$id");
        echo "<script>alert('PDF Deleted Successfully'); window.location='';</script>";
    }
}

/* ===== EDIT (UPDATE PDF) ===== */
if (isset($_POST['update_pdf'])) {
    $id = $_POST['id'];

    $uploadDir = "uploads/admission/";
    $fileName = time() . "_" . $_FILES['pdf']['name'];
    $fileTmp  = $_FILES['pdf']['tmp_name'];

    if (move_uploaded_file($fileTmp, $uploadDir . $fileName)) {

        $res = mysqli_query($conn, "SELECT pdf_file FROM admission_pdf WHERE id=$id");
        $row = mysqli_fetch_assoc($res);
        unlink("uploads/admission/" . $row['pdf_file']);

        mysqli_query($conn, "UPDATE admission_pdf SET pdf_file='$fileName' WHERE id=$id");
        echo "<script>alert('PDF Updated Successfully'); window.location='';</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin – Upload Admission PDF</title>
    <link rel="stylesheet" href="./CSS/admission.css" />
</head>
<body>

<!-- ===== Logout Button ===== -->
<div class="logout-box">
    <a href="../index.php" class="logout-btn">Logout</a>
</div>

<h2>Upload Admission Procedure PDF</h2>

<form method="POST" enctype="multipart/form-data">
    <input type="file" name="pdf" accept="application/pdf" required>
    <br><br>
    <button type="submit" name="upload_pdf">Upload PDF</button>
</form>

<hr>

<h2>Uploaded PDFs</h2>

<table>
    <tr>
        <th>ID</th>
        <th>PDF File</th>
        <th>View</th>
        <th>Edit</th>
        <th>Delete</th>
    </tr>

<?php
$result = mysqli_query($conn, "SELECT * FROM admission_pdf ORDER BY id DESC");
while ($row = mysqli_fetch_assoc($result)) {
?>
<tr>
    <td><?= $row['id']; ?></td>
    <td><?= $row['pdf_file']; ?></td>

    <td>
        <a href="uploads/admission/<?= $row['pdf_file']; ?>" target="_blank">View</a>
    </td>

    <td>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $row['id']; ?>">
            <input type="file" name="pdf" required>
            <button type="submit" name="update_pdf">Edit</button>
        </form>
    </td>

    <td>
        <a href="?delete=<?= $row['id']; ?>"
           onclick="return confirm('Are you sure you want to delete this PDF?')">
           Delete
        </a>
    </td>
</tr>
<?php } ?>
</table>

</body>
</html>
