<?php
include 'db.php';

// ---------- FACULTY DELETE ----------
if (isset($_POST['delete_id'])) {

    $deleteId = intval($_POST['delete_id']);

    // Old PDF delete
    $getPdf = mysqli_query($conn, "SELECT profile_pdf FROM faculty_members WHERE id=$deleteId");
    if ($getPdf && mysqli_num_rows($getPdf) > 0) {
        $row = mysqli_fetch_assoc($getPdf);
        if ($row['profile_pdf'] != "" && file_exists($row['profile_pdf'])) {
            unlink($row['profile_pdf']);
        }
    }

    mysqli_query($conn, "DELETE FROM faculty_members WHERE id=$deleteId");

    echo "<script>alert('Faculty Deleted Successfully!');window.location='admin.php';</script>";
    exit();
}

// ---------- FETCH FACULTY FOR EDIT ----------
$editData = null;
if (isset($_GET['edit_id'])) {
    $id = intval($_GET['edit_id']);
    $res = mysqli_query($conn, "SELECT * FROM faculty_members WHERE id=$id");
    if ($res && mysqli_num_rows($res) > 0) {
        $editData = mysqli_fetch_assoc($res);
    }
}

// ---------- FACULTY UPDATE ----------
if (isset($_POST['edit_id']) && $_POST['edit_id'] != "") {

    $id = intval($_POST['edit_id']);
    $name = mysqli_real_escape_string($conn, $_POST['staffName']);
    $designation = mysqli_real_escape_string($conn, $_POST['staffDesignation']);

    // old pdf
    $old = mysqli_query($conn, "SELECT profile_pdf FROM faculty_members WHERE id=$id");
    $oldRow = mysqli_fetch_assoc($old);
    $uploadPath = $oldRow['profile_pdf'];

    // new pdf upload
    if (!empty($_FILES['profileFile']['name'])) {

        if ($uploadPath != "" && file_exists($uploadPath)) {
            unlink($uploadPath);
        }

        $uploadDir = "uploads/faculty_pdf/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $pdfName = time() . "_" . basename($_FILES['profileFile']['name']);
        $uploadPath = $uploadDir . $pdfName;
        move_uploaded_file($_FILES['profileFile']['tmp_name'], $uploadPath);
    }

    mysqli_query($conn, "
        UPDATE faculty_members
        SET faculty_name='$name',
            designation='$designation',
            profile_pdf='$uploadPath'
        WHERE id=$id
    ");

    echo "<script>alert('Faculty Updated Successfully!');window.location='admin.php';</script>";
    exit();
}

// ---------- FACULTY INSERT ----------
if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['staffName']) && empty($_POST['edit_id'])) {

    $name = mysqli_real_escape_string($conn, $_POST['staffName']);
    $designation = mysqli_real_escape_string($conn, $_POST['staffDesignation']);

    // Upload PDF
    $uploadPath = "";
    if(isset($_FILES['profileFile']) && $_FILES['profileFile']['name'] != ""){

        $uploadDir = "uploads/faculty_pdf/";
        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0777, true)) {
                echo "<script>alert('Error: Failed to create upload directory!');</script>";
                exit();
            }
        }

        $cleanPdfName = basename($_FILES['profileFile']['name']);
        $pdfName = time() . "_" . $cleanPdfName;
        $uploadPath = $uploadDir . $pdfName;

        if(!move_uploaded_file($_FILES['profileFile']['tmp_name'], $uploadPath)){
            echo "<script>alert('Error: Failed to move uploaded file! Check folder permissions (777).');</script>";
            $uploadPath = "";
        }
    }

    if ($uploadPath != "" || ($uploadPath == "" && $name != "")) {
        $sql = "INSERT INTO faculty_members (faculty_name, designation, profile_pdf)
                 VALUES ('$name', '$designation', '$uploadPath')";

        if(mysqli_query($conn, $sql)){
            echo "<script>alert('Faculty Added Successfully!');</script>";
        } else {
            echo "<script>alert('Error Saving Faculty! SQL Error: " . mysqli_error($conn) . "');</script>";
        }
    } else {
        echo "<script>alert('Error: Invalid input or file upload failed.');</script>";
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Department of English - Admin</title>
    <link rel="stylesheet" href="./CSS/dept-admin.css" />
</head>
<body>

<header class="site-header">
    <div class="wrap header-flex">
        <!-- Empty space (left balance) -->
        <div class="header-spacer"></div>

        <!-- Center title -->
        <div class="header-center">
            <h1 class="title">Department of English</h1>
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
        <h2>Manage Faculty Members</h2>

        <form id="addFacultyForm" method="POST" enctype="multipart/form-data">
            <h3><?php echo ($editData) ? 'Edit Faculty' : 'Add Faculty'; ?></h3>

            <!-- HIDDEN EDIT ID -->
            <input type="hidden" name="edit_id" value="<?php echo $editData['id'] ?? ''; ?>">

            <div class="faculty-form-grid">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="staffName" value="<?php echo $editData['faculty_name'] ?? ''; ?>" required>
                </div>

                <div class="form-group">
                    <label>Designation</label>
                    <input type="text" name="staffDesignation" value="<?php echo $editData['designation'] ?? ''; ?>" required>
                </div>

                <div class="form-group">
                    <label>Profile PDF</label>
                    <input type="file" name="profileFile" accept=".pdf">
                </div>
            </div>

            <button type="submit"><?php echo ($editData) ? 'Update Faculty' : 'Add Faculty'; ?></button>
        </form>

        <div class="admin-table-container">
            <h3>Current Faculty List</h3>
            <table class="faculty-table-admin">
                <thead>
                    <tr>
                        <th>S.No.</th>
                        <th>Name</th>
                        <th>Designation</th>
                        <th>Profile</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $q = mysqli_query($conn, "SELECT * FROM faculty_members ORDER BY id ASC");
                    $i = 1;
                    if ($q) {
                        while($row = mysqli_fetch_assoc($q)){
                            $profileLink = (trim($row['profile_pdf']) != "") ? $row['profile_pdf'] : "#";
                            $linkText = ($profileLink === "#") ? "N/A" : "View";
                            echo "
<tr>
    <td>$i</td>
    <td>" . htmlspecialchars($row['faculty_name']) . "</td>
    <td>" . htmlspecialchars($row['designation']) . "</td>
    <td><a href='{$profileLink}' target='_blank'>{$linkText}</a></td>
    <td>
        <form method='GET' style='display:inline;'>
            <input type='hidden' name='edit_id' value='{$row['id']}'>
            <button type='submit'>Edit</button>
        </form>
        <form method='POST' style='display:inline;' onsubmit=\"return confirm('Delete this faculty?');\">
            <input type='hidden' name='delete_id' value='{$row['id']}'>
            <button type='submit'>Delete</button>
        </form>
    </td>
</tr>
";
                            $i++;
                        }
                    } else {
                        echo "<tr><td colspan='5'>Error fetching list: " . mysqli_error($conn) . "</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </section>

    <!-- Course content section untouched -->
    <section class="card admin-section">
        <h2>Manage Course Contents</h2>
        <form id="addCourseForm">
            <h3>Add/Update Subject</h3>
            <div class="course-grid">
                <div class="form-group">
                    <label>Select Department</label>
                    <select id="adminDeptSelect" required>
                        <option value="">-- Select Department --</option>
                        <option value="B.A. English">B.A. English</option>
                        <option value="M.A. English">M.A. English</option>
                        <option value="M.Phil.">M.Phil.</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Select Semester</label>
                    <select id="adminSemSelect" disabled required>
                        <option value="">-- Select Semester --</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Subject Title</label>
                    <input type="text" id="subjectTitle" required>
                </div>
                <div class="form-group">
                    <label>Subject Code</label>
                    <input type="text" id="subjectCode" required>
                </div>
                <input type="hidden" id="courseIndex" value="-1">
            </div>
            <button type="submit" id="submitBtn">Add Course</button>
        </form>

        <table class="faculty-table-admin">
            <thead>
                <tr>
                    <th>S.No.</th>
                    <th>Subject</th>
                    <th>Code</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="courseTableBody"></tbody>
        </table>
    </section>

</main>

<footer class="site-footer">
    <div class="wrap">
        <p>© 2025 Powered by Sourashtra College</p>
    </div>
</footer>

<script src="./js/admin.js"></script>

</body>
</html>
