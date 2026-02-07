<?php include 'auth.php'; ?>
<?php
include 'db.php';

/* ================= UPLOAD ACTIVITIES PDF ================= */
if (isset($_POST['upload_activity'])) {

    $title = mysqli_real_escape_string($conn, $_POST['activity_title']);

    $uploadDir = "uploads/tamil_activities/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $pdfName = time() . "_" . basename($_FILES['activity_pdf']['name']);
    $uploadPath = $uploadDir . $pdfName;

    if (move_uploaded_file($_FILES['activity_pdf']['tmp_name'], $uploadPath)) {

        mysqli_query($conn, "
            INSERT INTO tamil_activities (title, activity_pdf)
            VALUES ('$title', '$uploadPath')
        ");

        echo "<script>alert('Activity PDF Uploaded Successfully');</script>";
    } else {
        echo "<script>alert('PDF upload failed');</script>";
    }
}


/* ================= DELETE FACULTY ================= */
if (isset($_POST['delete_id'])) {

    $deleteId = intval($_POST['delete_id']);

    // delete old pdf
    $getPdf = mysqli_query($conn, "SELECT profile_pdf FROM tamil_faculty WHERE id=$deleteId");
    if ($getPdf && mysqli_num_rows($getPdf) > 0) {
        $row = mysqli_fetch_assoc($getPdf);
        if ($row['profile_pdf'] != "" && file_exists($row['profile_pdf'])) {
            unlink($row['profile_pdf']);
        }
    }

    mysqli_query($conn, "DELETE FROM tamil_faculty WHERE id=$deleteId");

    echo "<script>alert('Tamil Faculty Deleted Successfully');window.location='dept-tamil-admin.php';</script>";
    exit();
}

/* ================= FETCH FOR EDIT ================= */
$editData = null;
if (isset($_GET['edit_id'])) {
    $id = intval($_GET['edit_id']);
    $res = mysqli_query($conn, "SELECT * FROM tamil_faculty WHERE id=$id");
    if ($res && mysqli_num_rows($res) > 0) {
        $editData = mysqli_fetch_assoc($res);
    }
}

/* ================= UPDATE FACULTY ================= */
if (isset($_POST['edit_id']) && $_POST['edit_id'] != "") {

    $id = intval($_POST['edit_id']);
    $name = mysqli_real_escape_string($conn, $_POST['staffName']);
    $designation = mysqli_real_escape_string($conn, $_POST['staffDesignation']);

    // old pdf
    $old = mysqli_query($conn, "SELECT profile_pdf FROM tamil_faculty WHERE id=$id");
    $oldRow = mysqli_fetch_assoc($old);
    $uploadPath = $oldRow['profile_pdf'];

    // new pdf upload
    if (!empty($_FILES['profileFile']['name'])) {

        if ($uploadPath != "" && file_exists($uploadPath)) {
            unlink($uploadPath);
        }

        $uploadDir = "uploads/tamil_faculty_pdf/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $pdfName = time() . "_" . basename($_FILES['profileFile']['name']);
        $uploadPath = $uploadDir . $pdfName;
        move_uploaded_file($_FILES['profileFile']['tmp_name'], $uploadPath);
    }

    mysqli_query($conn, "
        UPDATE tamil_faculty SET
            faculty_name='$name',
            designation='$designation',
            profile_pdf='$uploadPath'
        WHERE id=$id
    ");

    echo "<script>alert('Tamil Faculty Updated Successfully');window.location='dept-tamil-admin.php';</script>";
    exit();
}

/* ================= INSERT FACULTY ================= */
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['staffName']) && empty($_POST['edit_id'])) {

    $name = mysqli_real_escape_string($conn, $_POST['staffName']);
    $designation = mysqli_real_escape_string($conn, $_POST['staffDesignation']);
    $uploadPath = "";

    if (!empty($_FILES['profileFile']['name'])) {

        $uploadDir = "uploads/tamil_faculty_pdf/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $pdfName = time() . "_" . basename($_FILES['profileFile']['name']);
        $uploadPath = $uploadDir . $pdfName;
        move_uploaded_file($_FILES['profileFile']['tmp_name'], $uploadPath);
    }

    mysqli_query($conn, "
        INSERT INTO tamil_faculty (faculty_name, designation, profile_pdf)
        VALUES ('$name', '$designation', '$uploadPath')
    ");

    echo "<script>alert('Tamil Faculty Added Successfully');</script>";
}
?>

<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Department of Tamil - Admin</title>
<link rel="stylesheet" href="./CSS/dept-admin.css">
</head>

<body>

<header class="site-header">
    <div class="wrap header-flex">
        <!-- Empty space (left balance) -->
        <div class="header-spacer"></div>

        <!-- Center title -->
        <div class="header-center">
            <h1 class="title">Department of Tamil</h1>
            <p class="subtitle">Admin Panel</p>
        </div>

        <!-- Logout -->
        <form method="post" action="../index.php" class="logout-form">
            <button type="submit" name="logout" class="logout-btn">
                Logout
            </button>
        </form>
    </div>
</header>


<main class="wrap main-grid">

<section class="card admin-section">
    <section class="card admin-section">
  <h2>Upload Activities (PDF)</h2>

  <form method="POST" enctype="multipart/form-data">
      <div class="faculty-form-grid">
          <div class="form-group">
              <label>Activity Title</label>
              <input type="text" name="activity_title" required>
          </div>

          <div class="form-group">
              <label>Upload PDF</label>
              <input type="file" name="activity_pdf" accept=".pdf" required>
          </div>
      </div>

      <button type="submit" name="upload_activity">Upload Activity</button>
  </form>
</section>

<h2>Manage Tamil Faculty Members</h2>

<form method="POST" enctype="multipart/form-data">
<h3><?php echo ($editData) ? 'Edit Faculty' : 'Add Faculty'; ?></h3>

<input type="hidden" name="edit_id" value="<?php echo $editData['id'] ?? ''; ?>">

<div class="faculty-form-grid">
    <div class="form-group">
        <label>Name</label>
        <input type="text" name="staffName" required value="<?php echo $editData['faculty_name'] ?? ''; ?>">
    </div>

    <div class="form-group">
        <label>Designation</label>
        <input type="text" name="staffDesignation" required value="<?php echo $editData['designation'] ?? ''; ?>">
    </div>

    <div class="form-group">
        <label>Profile PDF</label>
        <input type="file" name="profileFile" accept=".pdf">
    </div>
</div>

<button type="submit">
<?php echo ($editData) ? 'Update Faculty' : 'Add Faculty'; ?>
</button>
</form>

<div class="admin-table-container">
<h3>Current Tamil Faculty List</h3>

<table class="faculty-table-admin">
<thead>
<tr>
    <th>S.No</th>
    <th>Name</th>
    <th>Designation</th>
    <th>Profile</th>
    <th>Action</th>
</tr>
</thead>
<tbody>

<?php
$q = mysqli_query($conn, "SELECT * FROM tamil_faculty ORDER BY id ASC");
$i = 1;
while ($row = mysqli_fetch_assoc($q)) {

    $link = ($row['profile_pdf'] != "") ? $row['profile_pdf'] : "#";
    $text = ($row['profile_pdf'] != "") ? "View" : "N/A";

    echo "
    <tr>
        <td>$i</td>
        <td>".htmlspecialchars($row['faculty_name'])."</td>
        <td>".htmlspecialchars($row['designation'])."</td>
        <td><a href='$link' target='_blank'>$text</a></td>
        <td>
            <form method='GET' style='display:inline'>
                <input type='hidden' name='edit_id' value='{$row['id']}'>
                <button>Edit</button>
            </form>
            <form method='POST' style='display:inline' onsubmit=\"return confirm('Delete this faculty?');\">
                <input type='hidden' name='delete_id' value='{$row['id']}'>
                <button>Delete</button>
            </form>
        </td>
    </tr>";
    $i++;
}
?>

</tbody>
</table>
</div>

</section>


</main>

<footer class="site-footer">
<p>© 2025 Powered by Sourashtra College</p>
</footer>

</body>
</html>
