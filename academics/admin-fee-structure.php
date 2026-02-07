<?php include 'auth.php'; ?>
<?php include 'db.php'; ?>

<?php
/* ===== UPLOAD / UPDATE ===== */
if (isset($_POST['upload_pdf']) || isset($_POST['update_pdf'])) {

    $type = $_POST['type']; // aided / self
    $uploadDir = "uploads/fee/";

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileName = time() . "_" . $_FILES['pdf']['name'];
    $tmp = $_FILES['pdf']['tmp_name'];

    if (move_uploaded_file($tmp, $uploadDir . $fileName)) {

        // check old pdf
        $old = mysqli_query($conn,
            "SELECT pdf_file FROM fee_structure_pdf WHERE type='$type'"
        );

        if (mysqli_num_rows($old) > 0) {
            $row = mysqli_fetch_assoc($old);
            if (file_exists("uploads/fee/" . $row['pdf_file'])) {
                unlink("uploads/fee/" . $row['pdf_file']);
            }

            mysqli_query($conn,
                "UPDATE fee_structure_pdf
                 SET pdf_file='$fileName'
                 WHERE type='$type'"
            );
        } else {
            mysqli_query($conn,
                "INSERT INTO fee_structure_pdf (type,pdf_file)
                 VALUES ('$type','$fileName')"
            );
        }

        echo "<script>alert('PDF Saved Successfully');</script>";
    }
}

/* ===== DELETE ===== */
if (isset($_GET['delete'])) {

    $type = $_GET['delete'];

    $res = mysqli_query($conn,
        "SELECT pdf_file FROM fee_structure_pdf WHERE type='$type'"
    );

    if ($row = mysqli_fetch_assoc($res)) {

        if (file_exists("uploads/fee/" . $row['pdf_file'])) {
            unlink("uploads/fee/" . $row['pdf_file']);
        }

        mysqli_query($conn,
            "DELETE FROM fee_structure_pdf WHERE type='$type'"
        );

        echo "<script>alert('PDF Deleted Successfully'); window.location='';</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin – Fee Structure Upload</title>
<link rel="stylesheet" href="./CSS/fee-structure.css">
</head>
<body>
    
<div class="logout-box">
    <a href="../index.php" class="logout-btn">Logout</a>
</div>

<h2>Upload Fee Structure PDF</h2>

<form method="POST" enctype="multipart/form-data">
    <label>Select Fee Type</label><br><br>

    <select name="type" required>
        <option value="">-- Select --</option>
        <option value="aided">Aided</option>
        <option value="self">Self Finance</option>
    </select>
    <br><br>

    <input type="file" name="pdf" accept="application/pdf" required>
    <br><br>

    <button type="submit" name="upload_pdf">Upload / Update PDF</button>
</form>

<hr>

<h3>Uploaded PDFs</h3>

<table border="1" cellpadding="10">
<tr>
    <th>Type</th>
    <th>PDF</th>
    <th>View</th>
    <th>Edit</th>
    <th>Delete</th>
</tr>

<?php
$res = mysqli_query($conn, "SELECT * FROM fee_structure_pdf");
while ($row = mysqli_fetch_assoc($res)) {
?>
<tr>
    <td><?= ucfirst($row['type']); ?></td>
    <td><?= $row['pdf_file']; ?></td>

    <td>
        <a href="uploads/fee/<?= $row['pdf_file']; ?>" target="_blank">View</a>
    </td>

    <!-- EDIT -->
    <td>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="type" value="<?= $row['type']; ?>">
            <input type="file" name="pdf" required>
            <button type="submit" name="update_pdf">Edit</button>
        </form>
    </td>

    <!-- DELETE -->
    <td>
        <a href="?delete=<?= $row['type']; ?>"
           onclick="return confirm('Are you sure you want to delete this PDF?')">
           Delete
        </a>
    </td>
</tr>
<?php } ?>
</table>

</body>
</html>
