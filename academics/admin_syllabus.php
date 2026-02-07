<?php
include 'auth.php';
include 'db.php';

/* ================= ADD YEAR ================= */
if (isset($_POST['add_year'])) {
    $year = $_POST['year_name'];
    $default = isset($_POST['is_default']) ? 1 : 0;

    if ($default == 1) {
        mysqli_query($conn, "UPDATE syllabus_year SET is_default=0");
    }

    mysqli_query($conn,
        "INSERT INTO syllabus_year (year_name, is_default)
         VALUES ('$year','$default')"
    );
    echo "<script>alert('Year Added');</script>";
}

/* ================= DELETE YEAR ================= */
if (isset($_GET['delete_year'])) {
    mysqli_query($conn,"DELETE FROM syllabus_year WHERE id='{$_GET['delete_year']}'");
    echo "<script>alert('Year Deleted'); window.location='admin_syllabus.php';</script>";
}

/* ================= ADD VOLUME ================= */
if (isset($_POST['add_volume'])) {
    mysqli_query($conn,
        "INSERT INTO syllabus_volume (year_id, volume_name)
         VALUES ('{$_POST['year_id']}','{$_POST['volume_name']}')"
    );
    echo "<script>alert('Volume Added');</script>";
}

/* ================= DELETE VOLUME ================= */
if (isset($_GET['delete_volume'])) {
    mysqli_query($conn,"DELETE FROM syllabus_volume WHERE id='{$_GET['delete_volume']}'");
    echo "<script>alert('Volume Deleted'); window.location='admin_syllabus.php';</script>";
}

/* ================= ADD SUBJECT ================= */
if (isset($_POST['add_subject'])) {

    $subject_name = mysqli_real_escape_string($conn, $_POST['subject_name']);
    $volume_id = !empty($_POST['volume_id']) ? $_POST['volume_id'] : NULL;
    $year_id   = !empty($_POST['year_id']) ? $_POST['year_id'] : NULL;

    // PDF upload
    $pdf = $_FILES['pdf_file']['name'];
    $tmp = $_FILES['pdf_file']['tmp_name'];
    move_uploaded_file($tmp, "../uploads/syllabus/".$pdf);

    mysqli_query($conn,"
        INSERT INTO syllabus_subject (subject_name, volume_id, year_id, pdf_file)
        VALUES (
            '$subject_name',
            ".($volume_id ? "'$volume_id'" : "NULL").",
            ".($year_id ? "'$year_id'" : "NULL").",
            '$pdf'
        )
    ");

    echo "<script>alert('Subject PDF Uploaded Successfully');</script>";
}

/* ================= DELETE SUBJECT ================= */
if (isset($_GET['delete_subject'])) {
    mysqli_query($conn,"DELETE FROM syllabus_subject WHERE id='{$_GET['delete_subject']}'");
    echo "<script>alert('Subject Deleted'); window.location='admin_syllabus.php';</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Syllabus Panel</title>
<style>
/* =========================
   GLOBAL STYLES
========================= */
body{
    font-family: "Times New Roman", Times, serif;
    background: linear-gradient(120deg, #e6f0ff, #fff0f5);
    margin:0;
    padding:0;
    color:#222;
}

/* =========================
   HEADINGS
========================= */
h2{
    color:#00264d;
    border-left:6px solid #ff3333;
    padding-left:12px;
    margin-bottom:20px;
    font-size:24px;
    letter-spacing:1px;
}

/* =========================
   HEADER
========================= */
.header{
    background: linear-gradient(90deg, #00264d, #004080);
    color:#fff;
    padding:18px 35px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    box-shadow:0 6px 18px rgba(0,0,0,0.3);
    animation: slideDown 0.6s ease;
}
.header h1{
    margin:0;
    font-size:28px;
    letter-spacing:1.5px;
}
.logout{
    background:#ff3333;
    color:#fff;
    padding:10px 18px;
    text-decoration:none;
    border-radius:30px;
    font-weight:bold;
    transition: 0.4s;
}
.logout:hover{
    background:#cc0000;
    transform: scale(1.08);
}

/* =========================
   BOX CONTAINER
========================= */
.box{
    background:#ffffff;
    width:92%;
    max-width:950px;
    margin:30px auto;
    padding:30px;
    border-radius:14px;
    box-shadow:0 12px 30px rgba(0,0,0,0.18);
    animation: fadeUp 0.7s ease;
    transition: 0.4s;
}
.box:hover{
    transform: translateY(-6px);
    box-shadow:0 18px 40px rgba(0,0,0,0.25);
}

/* =========================
   FORM ELEMENTS
========================= */
input, select, button{
    width:100%;
    padding:13px;
    margin:10px 0;
    font-family: "Times New Roman", Times, serif;
    font-size:17px;
    border-radius:8px;
    border:1px solid #bbb;
    transition: 0.3s;
}
input:focus, select:focus{
    border-color:#004080;
    box-shadow:0 0 10px rgba(0,64,128,0.25);
    outline:none;
}
button{
    background: linear-gradient(90deg, #00264d, #004080);
    color:#fff;
    border:none;
    cursor:pointer;
    font-weight:bold;
    letter-spacing:1px;
}
button:hover{
    background: linear-gradient(90deg, #004080, #00264d);
    transform: scale(1.05);
}

/* =========================
   TABLE STYLES
========================= */
table{
    width:100%;
    border-collapse:collapse;
    margin-top:25px;
    font-size:16px;
    animation: fadeUp 0.8s ease;
}
table th{
    background: linear-gradient(90deg, #00264d, #004080);
    color:#fff;
    padding:14px;
    text-transform: uppercase;
    letter-spacing:1px;
}
table td{
    padding:12px;
    border:1px solid #ddd;
    text-align:center;
    background:#ffffff;
    transition: 0.3s;
}
table tr:nth-child(even) td{
    background:#f4f8ff;
}
table tr:hover td{
    background:#e6f0ff;
}

/* =========================
   ACTION LINKS
========================= */
.action a{
    color:#cc0000;
    text-decoration:none;
    font-weight:bold;
    transition: 0.3s;
}
.action a:hover{
    color:#ff0000;
    text-decoration:underline;
    transform: scale(1.1);
}

/* =========================
   LABELS
========================= */
label{
    font-weight:bold;
    color:#00264d;
    margin-top:12px;
    display:block;
}

/* =========================
   ANIMATIONS
========================= */
@keyframes fadeUp{
    from{opacity:0; transform: translateY(25px);}
    to{opacity:1; transform: translateY(0);}
}
@keyframes slideDown{
    from{opacity:0; transform: translateY(-30px);}
    to{opacity:1; transform: translateY(0);}
}

/* =========================
   RESPONSIVE
========================= */
@media(max-width:700px){
    .header h1{
        font-size:22px;
    }
    table th, table td{
        font-size:14px;
        padding:10px;
    }
    button, input, select{
        font-size:15px;
    }
}

</style>
</head>
<body>

<div class="header">
    <h1>Syllabus Admin Panel</h1>
    <a href="../index.php" class="logout">Logout</a>
</div>

<!-- ================= ADD YEAR ================= -->
<div class="box">
<h2>Add Syllabus Year</h2>
<form method="post">
    <input type="text" name="year_name" placeholder="2024-25" required>
    <label>
        <input type="checkbox" name="is_default"> Set as Default Year
    </label><br><br>
    <button name="add_year">Add Year</button>
</form>

<table>
<tr><th>Year</th><th>Action</th></tr>
<?php
$y=mysqli_query($conn,"SELECT * FROM syllabus_year");
while($r=mysqli_fetch_assoc($y)){
echo "<tr>
<td>{$r['year_name']}</td>
<td class='action'><a href='?delete_year={$r['id']}'>Delete</a></td>
</tr>";
}
?>
</table>
</div>

<!-- ================= ADD VOLUME ================= -->
<div class="box">
<h2>Add Volume</h2>
<form method="post">
<select name="year_id" required>
<option value="">Select Year</option>
<?php
$y=mysqli_query($conn,"SELECT * FROM syllabus_year");
while($r=mysqli_fetch_assoc($y)){
echo "<option value='{$r['id']}'>{$r['year_name']}</option>";
}
?>
</select>
<input type="text" name="volume_name" placeholder="Volume 1" required>
<button name="add_volume">Add Volume</button>
</form>

<table>
<tr><th>Year</th><th>Volume</th><th>Action</th></tr>
<?php
$v=mysqli_query($conn,"
SELECT syllabus_volume.id,year_name,volume_name
FROM syllabus_volume
JOIN syllabus_year ON syllabus_year.id=syllabus_volume.year_id
");
while($r=mysqli_fetch_assoc($v)){
echo "<tr>
<td>{$r['year_name']}</td>
<td>{$r['volume_name']}</td>
<td class='action'><a href='?delete_volume={$r['id']}'>Delete</a></td>
</tr>";
}
?>
</table>
</div>

<!-- ================= ADD SUBJECT ================= -->
<!-- ================= ADD SUBJECT ================= -->
<div class="box">
<h2>Add Subject & PDF</h2>
<form method="post" enctype="multipart/form-data">

    <!-- Subject Name -->
    <label>Subject Name:</label>
    <input type="text" name="subject_name" required>

    <!-- Year + Volume dropdown -->
    <label>Select Year & Volume:</label>
    <select name="volume_id">
        <option value="">Select Year & Volume</option>
        <?php
        $v = mysqli_query($conn,"
            SELECT syllabus_volume.id, volume_name, year_name
            FROM syllabus_volume
            JOIN syllabus_year ON syllabus_year.id = syllabus_volume.year_id
        ");
        while($r = mysqli_fetch_assoc($v)){
            echo "<option value='{$r['id']}'>{$r['year_name']} - {$r['volume_name']}</option>";
        }
        ?>
    </select>

    <!-- Only Year dropdown -->
    <label>Select Year (without volume):</label>
    <select name="year_id">
        <option value="">Select Year</option>
        <?php
        $years = mysqli_query($conn, "SELECT id, year_name FROM syllabus_year ORDER BY year_name DESC");
        while ($y = mysqli_fetch_assoc($years)) {
            echo "<option value='{$y['id']}'>{$y['year_name']}</option>";
        }
        ?>
    </select>

    <!-- PDF Upload -->
    <label>Upload PDF:</label>
    <input type="file" name="pdf_file" accept=".pdf" required>

    <br><br>
    <input type="submit" name="add_subject" value="Upload PDF">
</form>
</div>

<table>
<tr>
    <th>Year</th>
    <th>Volume</th>
    <th>Subject</th>
    <th>PDF</th>
    <th>Action</th>
</tr>

<?php
$s = mysqli_query($conn,"
    SELECT 
        syllabus_subject.id,
        syllabus_subject.subject_name,
        syllabus_subject.pdf_file,
        syllabus_year.year_name,
        syllabus_volume.volume_name
    FROM syllabus_subject
    LEFT JOIN syllabus_year 
        ON syllabus_year.id = syllabus_subject.year_id
    LEFT JOIN syllabus_volume 
        ON syllabus_volume.id = syllabus_subject.volume_id
    ORDER BY syllabus_subject.id DESC
");

while ($r = mysqli_fetch_assoc($s)) {

    $year   = $r['year_name'] ?? '-';
    $volume = $r['volume_name'] ?? '-';

    echo "<tr>
        <td>$year</td>
        <td>$volume</td>
        <td>{$r['subject_name']}</td>
        <td><a href='../uploads/syllabus/{$r['pdf_file']}' target='_blank'>View</a></td>
        <td class='action'>
            <a href='?delete_subject={$r['id']}'>Delete</a>
        </td>
    </tr>";
}
?>
</table>

</body>
</html>
