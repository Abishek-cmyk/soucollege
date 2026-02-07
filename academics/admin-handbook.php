<?php
include 'auth.php';
include 'db.php';

/* =========================
   UPLOAD / UPDATE
========================= */
if(isset($_POST['upload'])){

    $type = $_POST['type']; // aided / self
    $year = $_POST['year'];
    $edit_id = $_POST['edit_id'] ?? '';

    $dir = "../uploads/handbook/".$type."/";

    if(!is_dir($dir)){
        mkdir($dir,0777,true);
    }

    // If EDIT
    if(!empty($edit_id)){
        $old = mysqli_query($conn,"SELECT * FROM handbooks WHERE id=$edit_id");
        $oldRow = mysqli_fetch_assoc($old);

        $fileName = $oldRow['pdf_file'];

        if(!empty($_FILES['pdf']['name'])){
            $fileName = time()."_".$_FILES['pdf']['name'];
            move_uploaded_file($_FILES['pdf']['tmp_name'], $dir.$fileName);

            $oldFile = "../uploads/handbook/".$oldRow['type']."/".$oldRow['pdf_file'];
            if(file_exists($oldFile)){
                unlink($oldFile);
            }
        }

        mysqli_query($conn,"UPDATE handbooks SET 
            type='$type',
            year='$year',
            pdf_file='$fileName'
            WHERE id=$edit_id
        ");

        echo "<script>alert('Updated Successfully'); window.location='';</script>";
    }
    else{
        $fileName = time()."_".$_FILES['pdf']['name'];
        move_uploaded_file($_FILES['pdf']['tmp_name'], $dir.$fileName);

        mysqli_query($conn,"INSERT INTO handbooks (type,year,pdf_file)
            VALUES ('$type','$year','$fileName')
        ");

        echo "<script>alert('Uploaded Successfully');</script>";
    }
}

/* =========================
   DELETE
========================= */
if(isset($_GET['delete'])){
    $id = (int)$_GET['delete'];

    $q = mysqli_query($conn,"SELECT * FROM handbooks WHERE id=$id");
    if($row = mysqli_fetch_assoc($q)){
        $file = "../uploads/handbook/".$row['type']."/".$row['pdf_file'];
        if(file_exists($file)){
            unlink($file);
        }
        mysqli_query($conn,"DELETE FROM handbooks WHERE id=$id");
    }

    echo "<script>alert('Deleted Successfully'); window.location='';</script>";
}

/* =========================
   EDIT FETCH
========================= */
$edit = null;
if(isset($_GET['edit'])){
    $id = (int)$_GET['edit'];
    $res = mysqli_query($conn,"SELECT * FROM handbooks WHERE id=$id");
    $edit = mysqli_fetch_assoc($res);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin – Handbook Upload</title>

<style>
body{
    font-family:"Times New Roman", Times, serif;
    background:#f4f6fb;
    margin:0;
}

/* LOGOUT */
.logout-box{
    text-align:right;
    padding:20px 30px;
}

.logout-btn{
    background:linear-gradient(135deg,#c62828,#003366);
    color:#fff;
    padding:10px 22px;
    text-decoration:none;
    border-radius:25px;
    font-weight:bold;
}

/* BOX */
.admin-box{
    max-width:600px;
    background:#fff;
    margin:30px auto;
    padding:30px;
    border-radius:10px;
    box-shadow:0 10px 30px rgba(0,0,0,0.15);
    border-top:6px solid #c62828;
}

.admin-box h2{
    text-align:center;
    color:#003366;
}

/* FORM */
.admin-box select,
.admin-box input{
    width:100%;
    padding:10px;
    margin:12px 0;
    border:1px solid #ccc;
    border-radius:6px;
    font-family:"Times New Roman", Times, serif;
}

.admin-box button{
    width:100%;
    padding:12px;
    background:linear-gradient(135deg,#003366,#c62828);
    color:#fff;
    border:none;
    border-radius:30px;
    font-size:16px;
    font-weight:bold;
    cursor:pointer;
}

/* TABLE */
table{
    width:100%;
    margin-top:25px;
    border-collapse:collapse;
}

th{
    background:#003366;
    color:#fff;
    padding:10px;
}

td{
    padding:10px;
    text-align:center;
    border-bottom:1px solid #ddd;
}

tr:nth-child(even){
    background:#f4f6fb;
}

/* ACTIONS */
.action a{
    padding:6px 12px;
    border-radius:20px;
    color:#fff;
    text-decoration:none;
    font-size:13px;
    margin:0 3px;
}

.edit{background:#1976d2;}
.delete{background:#c62828;}
</style>
</head>

<body>

<div class="logout-box">
    <a href="../index.php" class="logout-btn">Logout</a>
</div>

<div class="admin-box">

<h2><?= $edit ? 'Edit Handbook' : 'Upload Handbook' ?></h2>

<form method="post" enctype="multipart/form-data">
    <input type="hidden" name="edit_id" value="<?= $edit['id'] ?? '' ?>">

    <select name="type" required>
        <option value="">Select Type</option>
        <option value="aided" <?= isset($edit)&&$edit['type']=='aided'?'selected':'' ?>>Aided</option>
        <option value="self" <?= isset($edit)&&$edit['type']=='self'?'selected':'' ?>>Self</option>
    </select>

    <input type="text" name="year" placeholder="2019-20"
           value="<?= $edit['year'] ?? '' ?>" required>

    <input type="file" name="pdf" accept="application/pdf" <?= $edit?'':'required' ?>>

    <button name="upload"><?= $edit?'Update PDF':'Upload PDF' ?></button>
</form>

<hr>

<table>
<tr>
    <th>Type</th>
    <th>Year</th>
    <th>PDF</th>
    <th>Action</th>
</tr>

<?php
$list = mysqli_query($conn,"SELECT * FROM handbooks ORDER BY type, year");
while($row = mysqli_fetch_assoc($list)){
?>
<tr>
    <td><?= $row['type'] ?></td>
    <td><?= $row['year'] ?></td>
    <td><?= $row['pdf_file'] ?></td>
    <td class="action">
        <a class="edit" href="?edit=<?= $row['id'] ?>">Edit</a>
        <a class="delete" href="?delete=<?= $row['id'] ?>"
           onclick="return confirm('Delete this PDF?')">Delete</a>
    </td>
</tr>
<?php } ?>

</table>

</div>

</body>
</html>
