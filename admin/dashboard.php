<?php
include "../db.php";

/* =========================
   AUTO FOLDER CREATE
========================= */
function createFolder($path){
    if(!is_dir($path)){
        mkdir($path, 0777, true);
    }
}

/* =========================
   CIRCULAR / NEWS CRUD (FIXED)
========================= */

// SAVE / UPDATE
if(isset($_POST['save_news'])){

    $id      = $_POST['id'] ?? '';
    $title   = mysqli_real_escape_string($conn,$_POST['title']);
    $content = mysqli_real_escape_string($conn,$_POST['content']);
    $type    = $_POST['type']; // circular / news
    $url     = mysqli_real_escape_string($conn,$_POST['url'] ?? '');
    $file_link = $_POST['existing_file'] ?? '';

    /* FILE UPLOAD */
    if(!empty($_FILES['file_upload']['name'])){
        $folder = "../uploads/files/";
        if(!is_dir($folder)){
            mkdir($folder,0777,true);
        }

        $file_name = time().'_'.$_FILES['file_upload']['name'];
        move_uploaded_file($_FILES['file_upload']['tmp_name'],$folder.$file_name);

        // IMPORTANT: store RELATIVE path for user page
        $file_link = "uploads/files/".$file_name;
    }

    if(!empty($id)){
        mysqli_query($conn,"
            UPDATE news_events SET
            title='$title',
            content='$content',
            type='$type',
            url='$url',
            file_link='$file_link'
            WHERE id=$id
        ");
    }else{
        mysqli_query($conn,"
            INSERT INTO news_events (title,content,type,url,file_link)
            VALUES ('$title','$content','$type','$url','$file_link')
        ");
    }

    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

/* DELETE */
if(isset($_GET['del_news'])){
    $id = $_GET['del_news'];

    // delete file
    $res = mysqli_query($conn,"SELECT file_link FROM news_events WHERE id=$id");
    $row = mysqli_fetch_assoc($res);

    if(!empty($row['file_link'])){
        $file = "../".$row['file_link'];
        if(file_exists($file)){
            unlink($file);
        }
    }

    mysqli_query($conn,"DELETE FROM news_events WHERE id=$id");
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

/* EDIT FETCH */
$edit_news = [];
if(isset($_GET['edit_news'])){
    $id = $_GET['edit_news'];
    $res = mysqli_query($conn,"SELECT * FROM news_events WHERE id=$id");
    $edit_news = mysqli_fetch_assoc($res);
}

/* =========================
   3️⃣ GALLERY UPLOAD
========================= */
if(isset($_POST['gallery_submit'])){
    $title = mysqli_real_escape_string($conn,$_POST['gallery_title']);
    $img   = time().'_'.$_FILES['gallery_image']['name'];
    $tmp   = $_FILES['gallery_image']['tmp_name'];

    $folder = "../uploads/gallery/";
    createFolder($folder);

    move_uploaded_file($tmp, $folder.$img);

    mysqli_query($conn,
        "INSERT INTO gallery(title,image)
         VALUES('$title','$img')");
}

if(isset($_POST['gallery_edit'])){
    $id = $_POST['gallery_id'];
    $title = mysqli_real_escape_string($conn,$_POST['gallery_title']);

    mysqli_query($conn,
        "UPDATE gallery SET title='$title' WHERE id=$id"
    );

    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}


/* =========================
   4️⃣ NEW COURSE / ADMISSION
========================= */
if(isset($_POST['welcome_submit'])){
    $type  = $_POST['type'];
    $title = mysqli_real_escape_string($conn,$_POST['title']);
    $year  = mysqli_real_escape_string($conn,$_POST['year']);

    mysqli_query($conn,
        "INSERT INTO welcome_updates(type,title,year)
         VALUES('$type','$title','$year')"
    );
}

/* DELETE WELCOME */
if(isset($_GET['del_welcome'])){
    $id = $_GET['del_welcome'];
    mysqli_query($conn,"DELETE FROM welcome_updates WHERE id=$id");
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Dashboard</title>
<link rel="stylesheet" href="../css/dash.css">
</head>
<body>

<div class="topbar">
    <span>Admin Dashboard</span>
    <a href="../index.php">View Site</a>
</div>

<div class="container">

<?php
include "../db.php";

/* 2. FETCH FOR EDIT */
$edit_header = [];
if(isset($_GET['edit_header']) && !empty($_GET['edit_header'])){
    $id = (int)$_GET['edit_header']; 
    $res = mysqli_query($conn,"SELECT * FROM header_images WHERE id=$id LIMIT 1");
    if($res && mysqli_num_rows($res) > 0){
        $edit_header = mysqli_fetch_assoc($res);
    }
}

/* 3. UPLOAD OR UPDATE */
if(isset($_POST['header_submit'])){
    $header_id = !empty($_POST['header_id']) ? (int)$_POST['header_id'] : 0;
    $img_name = $_FILES['header_image']['name'] ?? '';
    $folder = "../uploads/header/";
    createFolder($folder);

    if(!empty($img_name)){
        $ext = pathinfo($img_name, PATHINFO_EXTENSION);
        $img = time().'_'.uniqid().'.'.$ext; // Unique name generate pannum

        if(move_uploaded_file($_FILES['header_image']['tmp_name'], $folder.$img)){
            if($header_id > 0){ 
                // Specific Row UPDATE
                $old_res = mysqli_query($conn,"SELECT image FROM header_images WHERE id=$header_id");
                $old_row = mysqli_fetch_assoc($old_res);
                if($old_row && !empty($old_row['image'])){
                    $old_file = $folder.$old_row['image'];
                    if(file_exists($old_file)) unlink($old_file);
                }
                mysqli_query($conn,"UPDATE header_images SET image='$img' WHERE id=$header_id");
            } else { 
                // NEW INSERT
                mysqli_query($conn,"INSERT INTO header_images(image) VALUES('$img')");
            }
        }
    }
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

/* 4. DELETE Logic (Only specific ID) */
if(isset($_GET['delete_header']) && !empty($_GET['delete_header'])){
    $id = (int)$_GET['delete_header']; 
    
    // First, fetch the image name to delete file
    $res = mysqli_query($conn,"SELECT image FROM header_images WHERE id=$id");
    if($res && mysqli_num_rows($res) > 0){
        $row = mysqli_fetch_assoc($res);
        $file = "../uploads/header/".$row['image'];
        
        if(!empty($row['image']) && file_exists($file)){
            unlink($file);
        }
        // Specific ID delete query
        mysqli_query($conn,"DELETE FROM header_images WHERE id=$id");
    }

    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}
?>

<div class="box">
    <h3>Header Images Management</h3>

    <form method="post" enctype="multipart/form-data" style="padding:15px; border:1px solid #ddd; margin-bottom:20px;">
        <input type="hidden" name="header_id" value="<?= $edit_header['id'] ?? '' ?>">
        
        <?php if(!empty($edit_header)): ?>
            <p>Current Image: <br><img src="../uploads/header/<?= $edit_header['image'] ?>" width="100"></p>
        <?php endif; ?>

        <input type="file" name="header_image" <?= empty($edit_header) ? 'required' : '' ?>>
        <button name="header_submit" type="submit">
            <?= empty($edit_header) ? 'Upload New' : 'Replace Image' ?>
        </button>

        <?php if(!empty($edit_header)): ?>
            <a href="<?= $_SERVER['PHP_SELF'] ?>" style="margin-left:10px;color:red;">Cancel Edit</a>
        <?php endif; ?>
    </form>

    <table border="1" cellpadding="10" width="100%" style="border-collapse: collapse;">
        <tr style="background:#f4f4f4;">
            <th>ID</th>
            <th>Image Preview</th>
            <th>Action</th>
        </tr>
        <?php
        $res = mysqli_query($conn,"SELECT * FROM header_images ORDER BY id DESC");
        while($row = mysqli_fetch_assoc($res)){
        ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><img src="../uploads/header/<?= htmlspecialchars($row['image']) ?>" width="100"></td>
            <td>
                <a href="?edit_header=<?= $row['id'] ?>">Edit</a> | 
                <a href="?delete_header=<?= $row['id'] ?>" onclick="return confirm('Are you sure to Delete this Image?')" style="color:red;">Delete</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>

<!-- ===== NEWS / CIRCULAR FORM ===== -->
<?php
include "../db.php";

/* ===== EDIT FETCH ===== */
$edit = [];
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $q = mysqli_query($conn, "SELECT * FROM news_events WHERE id=$id");
    $edit = mysqli_fetch_assoc($q);
}
?>

<div class="box">
<h2><?= isset($edit['id']) ? "Edit" : "Add" ?> Circular / News</h2>

<form method="post" action="save_circular_news.php" enctype="multipart/form-data">

<input type="hidden" name="id" value="<?= $edit['id'] ?? '' ?>">
<input type="hidden" name="existing_file" value="<?= $edit['file_link'] ?? '' ?>">

<label>Type</label><br>
<select name="type" required>
    <option value="">Select</option>
    <option value="circular" <?= ($edit['type'] ?? '')=='circular'?'selected':'' ?>>Circular</option>
    <option value="news" <?= ($edit['type'] ?? '')=='news'?'selected':'' ?>>News / Events</option>
</select><br><br>

<label>Title</label><br>
<input type="text" name="title" required value="<?= $edit['title'] ?? '' ?>"><br><br>

<label>Content</label><br>
<textarea name="content" rows="4"><?= $edit['content'] ?? '' ?></textarea><br><br>

<label>PDF Upload (optional)</label><br>
<input type="file" name="file"><br><br>

<label>OR External Link</label><br>
<input type="text" name="url" value="<?= $edit['url'] ?? '' ?>"><br><br>

<button type="submit">Save</button>
</form>
</div>

<!-- ===== LIST ===== -->
<div class="box">
<h3>Circulars / News List</h3>

<table border="1" width="100%" cellpadding="8">
<tr>
    <th>Type</th>
    <th>Title</th>
    <th>Content</th>
    <th>Link</th>
    <th>File</th>
    <th>Action</th>
</tr>

<?php
$res = mysqli_query($conn,"SELECT * FROM news_events ORDER BY id DESC");
while($row = mysqli_fetch_assoc($res)){
$fileName = $row['file_link'] ? basename($row['file_link']) : '';
?>
<tr>
<td><?= ucfirst($row['type']) ?></td>
<td><?= htmlspecialchars($row['title']) ?></td>
<td><?= htmlspecialchars($row['content']) ?></td>

<td>
<?php if($row['url']){ ?>
<a href="<?= htmlspecialchars($row['url']) ?>" target="_blank">View Link</a>
<?php } ?>
</td>

<td>
<?php if($row['file_link']){ ?>
<a href="../<?= htmlspecialchars($row['file_link']) ?>" target="_blank">
<?= htmlspecialchars($fileName) ?>
</a>
<?php } ?>
</td>

<td>
<a href="dashboard.php?edit=<?= $row['id'] ?>">Edit</a> |
<a href="delete_news.php?id=<?= $row['id'] ?>&type=<?= $row['type'] ?>"
   onclick="return confirm('Delete this item?')">Delete</a>
</td>
</tr>
<?php } ?>
</table>
</div>

<!-- ===== GALLERY UPLOAD ===== -->
<!-- ===== GALLERY LIST ===== -->
<?php
include "../db.php";

/* 1. FETCH FOR EDIT (Gallery) */
$edit_gallery = [];
if(isset($_GET['edit_gallery']) && !empty($_GET['edit_gallery'])){
    $id = (int)$_GET['edit_gallery']; 
    $res = mysqli_query($conn,"SELECT * FROM gallery WHERE id=$id LIMIT 1");
    if($res && mysqli_num_rows($res) > 0){
        $edit_gallery = mysqli_fetch_assoc($res);
    }
}

/* 2. UPLOAD OR UPDATE (Gallery) */
if(isset($_POST['gallery_submit'])){
    $gallery_id = !empty($_POST['gallery_id']) ? (int)$_POST['gallery_id'] : 0;
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $img_name = $_FILES['gallery_image']['name'] ?? '';
    $folder = "../uploads/gallery/";
    
    // Create folder if not exists (Ensure createFolder function is in your db.php)
    if(!is_dir($folder)) mkdir($folder, 0777, true);

    if(!empty($img_name)){
        // Image Upload Logic
        $ext = pathinfo($img_name, PATHINFO_EXTENSION);
        $img = time().'_'.uniqid().'.'.$ext;

        if(move_uploaded_file($_FILES['gallery_image']['tmp_name'], $folder.$img)){
            if($gallery_id > 0){ 
                // Old file delete pannitu update pannum
                $old_res = mysqli_query($conn,"SELECT image FROM gallery WHERE id=$gallery_id");
                $old_row = mysqli_fetch_assoc($old_res);
                if($old_row && !empty($old_row['image'])){
                    $old_file = $folder.$old_row['image'];
                    if(file_exists($old_file)) unlink($old_file);
                }
                mysqli_query($conn,"UPDATE gallery SET image='$img', title='$title' WHERE id=$gallery_id");
            } else { 
                // New Insert
                mysqli_query($conn,"INSERT INTO gallery(image, title) VALUES('$img', '$title')");
            }
        }
    } else {
        // Image mathama Title mattum update panna
        if($gallery_id > 0){
            mysqli_query($conn,"UPDATE gallery SET title='$title' WHERE id=$gallery_id");
        }
    }
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

/* 3. DELETE Logic (Gallery) */
if(isset($_GET['del_gallery']) && !empty($_GET['del_gallery'])){
    $id = (int)$_GET['del_gallery']; 
    $res = mysqli_query($conn,"SELECT image FROM gallery WHERE id=$id");
    if($res && mysqli_num_rows($res) > 0){
        $row = mysqli_fetch_assoc($res);
        $file = "../uploads/gallery/".$row['image'];
        if(!empty($row['image']) && file_exists($file)) unlink($file);
        mysqli_query($conn,"DELETE FROM gallery WHERE id=$id");
    }
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}
?>

<div class="box">
    <h3>Gallery Images Management</h3>

    <form method="post" enctype="multipart/form-data" style="padding:15px; border:1px solid #ddd; margin-bottom:20px;">
        <input type="hidden" name="gallery_id" value="<?= $edit_gallery['id'] ?? '' ?>">
        
        <div style="margin-bottom: 10px;">
            <label>Image Title:</label><br>
            <input type="text" name="title" value="<?= $edit_gallery['title'] ?? '' ?>" required style="width:100%; padding:8px; margin-top:5px;">
        </div>

        <div style="margin-bottom: 10px;">
            <?php if(!empty($edit_gallery)): ?>
                <p>Current Image: <br><img src="../uploads/gallery/<?= $edit_gallery['image'] ?>" width="100" style="margin: 5px 0;"></p>
            <?php endif; ?>
            
            <label>Select Image:</label><br>
            <input type="file" name="gallery_image" <?= empty($edit_gallery) ? 'required' : '' ?>>
        </div>

        <button name="gallery_submit" type="submit" style="padding: 7px 15px; cursor: pointer;">
            <?= empty($edit_gallery) ? 'Upload to Gallery' : 'Update Gallery' ?>
        </button>

        <?php if(!empty($edit_gallery)): ?>
            <a href="<?= $_SERVER['PHP_SELF'] ?>" style="margin-left:10px;color:red;">Cancel Edit</a>
        <?php endif; ?>
    </form>

    <table border="1" cellpadding="10" width="100%" style="border-collapse: collapse;">
        <tr style="background:#f4f4f4;">
            <th>ID</th>
            <th>Image</th>
            <th>Title</th>
            <th>Action</th>
        </tr>
        <?php
        $res = mysqli_query($conn,"SELECT * FROM gallery ORDER BY id DESC");
        if(mysqli_num_rows($res) > 0){
            while($row = mysqli_fetch_assoc($res)){
            ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td align="center">
                    <img src="../uploads/gallery/<?= htmlspecialchars($row['image']) ?>" width="100">
                </td>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td>
                    <a href="?edit_gallery=<?= $row['id'] ?>" onclick="return confirm('Are you sure Edit this Image?')" style="color:blue;">Edit</a> | 
                    <a href="?del_gallery=<?= $row['id'] ?>" onclick="return confirm('Are you sure Delete this Image?')" style="color:red;">Delete</a>
                </td>
            </tr>
            <?php 
            }
        } else {
            echo "<tr><td colspan='3' align='center'>No images found in gallery.</td></tr>";
        }
        ?>
    </table>
</div>

<!-- ===== WELCOME / COURSE ===== -->
<div class="box">
<form method="post">
<h3>Add New Course / Admission Text</h3>
<select name="type" required>
    <option value="">Select Type</option>
    <option value="course">New Course</option>
    <option value="admission">Admission Portal</option>
</select>
<input type="text" name="title" placeholder="Course Name / Admission Text" required>
<input type="text" name="year" placeholder="Year (Only for Admission)">
<button name="welcome_submit">Save</button>
</form>
</div>

<!-- ===== WELCOME LIST ===== -->
<div class="box">
<h3>Welcome Section Contents</h3>
<table>
<tr>
<th>Type</th>
<th>Title</th>
<th>Year</th>
<th>Action</th>
</tr>
<?php
$res = mysqli_query($conn,"SELECT * FROM welcome_updates ORDER BY id DESC");
while($row = mysqli_fetch_assoc($res)){
?>
<tr>
<td><?= ucfirst($row['type']) ?></td>
<td><?= htmlspecialchars($row['title']) ?></td>
<td><?= htmlspecialchars($row['year']) ?></td>
<td><a href="?del_welcome=<?= $row['id'] ?>" style="color:#b30000;" onclick="return confirm('Delete this item?')">Delete</a></td>
</tr>
<?php } ?>
</table>
</div>

</div>
</body>
</html>
