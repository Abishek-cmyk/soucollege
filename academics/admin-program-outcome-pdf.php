<?php include 'auth.php'; ?>
<?php include 'db.php'; ?>

<?php
/* ===== UPLOAD / UPDATE ===== */
if (isset($_POST['upload_pdf']) || isset($_POST['update_pdf'])) {

    $year = $_POST['year'];
    $uploadDir = "uploads/programme/";

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileName = time() . "_" . $_FILES['pdf']['name'];
    $tmp = $_FILES['pdf']['tmp_name'];

    if (move_uploaded_file($tmp, $uploadDir . $fileName)) {

        // check old pdf
        $old = mysqli_query($conn,
            "SELECT pdf_file FROM program_outcomes_pdf WHERE year='$year'"
        );

        if (mysqli_num_rows($old) > 0) {
            $row = mysqli_fetch_assoc($old);
            if (file_exists("uploads/programme/" . $row['pdf_file'])) {
                unlink("uploads/programme/" . $row['pdf_file']);
            }

            mysqli_query($conn,
                "UPDATE program_outcomes_pdf
                 SET pdf_file='$fileName'
                 WHERE year='$year'"
            );
        } else {
            mysqli_query($conn,
                "INSERT INTO program_outcomes_pdf (year,pdf_file)
                 VALUES ('$year','$fileName')"
            );
        }

        echo "<script>alert('PDF Saved Successfully');</script>";
    }
}

/* ===== DELETE ===== */
if (isset($_GET['delete'])) {

    $year = $_GET['delete'];

    $res = mysqli_query($conn,
        "SELECT pdf_file FROM program_outcomes_pdf WHERE year='$year'"
    );

    if ($row = mysqli_fetch_assoc($res)) {

        if (file_exists("uploads/programme/" . $row['pdf_file'])) {
            unlink("uploads/programme/" . $row['pdf_file']);
        }

        mysqli_query($conn,
            "DELETE FROM program_outcomes_pdf WHERE year='$year'"
        );

        echo "<script>alert('PDF Deleted Successfully'); window.location='';</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin – Program Outcome PDF</title>
<link rel="stylesheet" href="./CSS/program-outcome.css">
</head>
<body>

<div class="logout-box">
    <a href="../index.php" class="logout-btn">Logout</a>
</div>

<h2>Upload Program Outcome (Year-wise)</h2>


<form method="POST" enctype="multipart/form-data">
    <label>Select Academic Year</label><br><br>

    <select name="year" required>
        <option value="">-- Select --</option>
        <option value="2019-2020">2019 – 2020</option>
        <option value="2020-2021">2020 – 2021</option>
        <option value="2021-2022">2021 – 2022</option>
        <option value="2022-2023">2022 – 2023</option>
    </select><br><br>

    <input type="file" name="pdf" accept="application/pdf" required><br><br>

    <button type="submit" name="upload_pdf">Upload / Update PDF</button>
</form>

<hr>

<h3>Uploaded PDFs</h3>

<table border="1" cellpadding="10">
<tr>
    <th>Year</th>
    <th>PDF</th>
    <th>View</th>
    <th>Edit</th>
    <th>Delete</th>
</tr>

<?php
$res = mysqli_query($conn, "SELECT * FROM program_outcomes_pdf");
while ($row = mysqli_fetch_assoc($res)) {
?>
<tr>
    <td><?= $row['year']; ?></td>
    <td><?= $row['pdf_file']; ?></td>

    <td>
        <a href="uploads/programme/<?= $row['pdf_file']; ?>" target="_blank">View</a>
    </td>

    <!-- EDIT -->
    <td>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="year" value="<?= $row['year']; ?>">
            <input type="file" name="pdf" required>
            <button type="submit" name="update_pdf">Edit</button>
        </form>
    </td>

    <!-- DELETE -->
    <td>
        <a href="?delete=<?= $row['year']; ?>"
           onclick="return confirm('Are you sure you want to delete this PDF?')">
           Delete
        </a>
    </td>
</tr>
<?php } ?>
</table>

</body>
</html>


<?php
if(isset($_POST['upload'])){

    $uploadDir = "uploads/programme/";

    if(!is_dir($uploadDir)){
        mkdir($uploadDir, 0777, true);
    }

    $fileName = time() . "_" . $_FILES['pdf']['name'];
    $tmp = $_FILES['pdf']['tmp_name'];

    if(move_uploaded_file($tmp, $uploadDir.$fileName)){

        // delete old PDF
        $old = mysqli_query($conn,"SELECT pdf_file FROM program_outcomes LIMIT 1");
        if($row = mysqli_fetch_assoc($old)){
            if(file_exists($uploadDir.$row['pdf_file'])){
                unlink($uploadDir.$row['pdf_file']);
            }
            mysqli_query($conn,"DELETE FROM program_outcomes");
        }

        // insert new PDF
        mysqli_query($conn,
            "INSERT INTO program_outcomes (year,pdf_file)
             VALUES ('Program Outcome','$fileName')"
        );

        echo "<script>alert('PDF Uploaded Successfully');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Upload Program Outcome PDF</title>
</head>
<body>

<h2>Upload Program Outcomes PDF</h2>

<form method="post" enctype="multipart/form-data">
    <input type="file" name="pdf" accept="application/pdf" required><br><br>
    <button type="submit" name="upload">Upload PDF</button>
</form>

</body>
</html>
