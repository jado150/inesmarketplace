<!-- ISHIMWE Richard -->


<?php
session_start();
include "db.php";

if(!isset($_SESSION['user']) || $_SESSION['role'] != "student"){
    header("Location: index.php");
    exit;
}

$id = $_SESSION['user'];

if(isset($_POST['create'])){
    $title = mysqli_real_escape_string($conn,$_POST['title']);
    $desc = mysqli_real_escape_string($conn,$_POST['description']);
    $cat = mysqli_real_escape_string($conn,$_POST['category']);

    $img = "";
    if(!empty($_FILES['image']['name'])){
        $img = time()."_".basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'],"uploads/".$img);
    }

    mysqli_query($conn,"INSERT INTO posts(user_id,title,description,category,image) 
                        VALUES('$id','$title','$desc','$cat','$img')");
}

if(isset($_POST['update'])){
    $pid = intval($_POST['post_id']);
    $title = mysqli_real_escape_string($conn,$_POST['title']);
    $desc = mysqli_real_escape_string($conn,$_POST['description']);
    $cat = mysqli_real_escape_string($conn,$_POST['category']);

    mysqli_query($conn,"UPDATE posts 
                        SET title='$title', description='$desc', category='$cat' 
                        WHERE id='$pid' AND user_id='$id'");
}

if(isset($_GET['delete'])){
    $pid = intval($_GET['delete']);
    mysqli_query($conn,"DELETE FROM posts WHERE id='$pid' AND user_id='$id'");
}

$editData = null;
if(isset($_GET['edit'])){
    $eid = intval($_GET['edit']);
    $editQuery = mysqli_query($conn,"SELECT * FROM posts WHERE id='$eid' AND user_id='$id'");
    $editData = mysqli_fetch_assoc($editQuery);
}

$result = mysqli_query($conn,"SELECT * FROM posts WHERE user_id='$id' ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard | UniStack INES</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>

<div class="nav">
    <div class="logo">UniStack INES</div>
    <div class="menu">
        <a href="index.php">Home</a>
        <a href="auth.php?logout=1">Logout</a>
    </div>
</div>

<div class="container dashboard">

    <h2 style="text-align: center;">Welcome <?php echo htmlspecialchars($_SESSION['name']); ?></h2>

    <div class="form-section"><br>
        <h3 style="text-align: center;"><?php echo $editData ? "Edit Post" : "Create Post"; ?></h3>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="post_id" value="<?php echo $editData ? $editData['id'] : ""; ?>">

            <div class="form-group">
                <input type="text" name="title" placeholder=" " value="<?php echo $editData ? htmlspecialchars($editData['title']) : ""; ?>" required>
                <label>Title</label>
            </div>

            <div class="form-group">
                <textarea name="description" placeholder=" " required><?php echo $editData ? htmlspecialchars($editData['description']) : ""; ?></textarea>
                <label>Description|| Remember to add your <b>contact</b> Sibyo?</label>
            </div>

            <div class="form-group">
                <select name="category" required>
                    <option value="">Select Category</option>
                    <option value="Housing" <?php if($editData && $editData['category']=="Housing") echo "selected"; ?>>Housing</option>
                    <option value="For Sale" <?php if($editData && $editData['category']=="For Sale") echo "selected"; ?>>For Sale</option>
                    <option value="Announcement" <?php if($editData && $editData['category']=="Announcement") echo "selected"; ?>>Announcement</option>
                </select>
                <label>Category</label>
            </div>

            <?php if(!$editData): ?>
                <div class="form-group">
                    <input type="file" name="image" accept="image/*">
                    <label>Image (optional)</label>
                </div>
            <?php endif; ?>

            <button type="submit" name="<?php echo $editData ? "update" : "create"; ?>">
                <?php echo $editData ? "Update Post" : "Create Post"; ?>
            </button>
        </form>
    </div>

    <hr>

    <h3>My Posts</h3>
    <div class="posts">
        <?php if(mysqli_num_rows($result) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
                <div class="card">
                    <?php if(!empty($row['image'])): ?>
                        <img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" alt="Post Image">
                    <?php endif; ?>
                    <div class="top">
                        <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                        <span><?php echo htmlspecialchars($row['category']); ?></span>
                    </div>
                    <div class="body">
                        <p><?php echo nl2br(htmlspecialchars($row['description'])); ?></p>
                    </div>
                    <div class="bottom">
                        <span>Status: <strong><?php echo htmlspecialchars($row['status']); ?></strong></span>
                        <div class="actions">
                            <button onclick="location.href='dashboard.php?edit=<?php echo $row['id']; ?>'">Edit</button>
                            <button onclick="if(confirm('Delete this post?')){location.href='dashboard.php?delete=<?php echo $row['id']; ?>'}">Delete</button>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="empty">You haven't created any posts yet.</div>
        <?php endif; ?>
    </div>

</div>

<div class="footer">
    <p>© <?php echo date("Y"); ?> UniStack INES. All rights reserved.</p>
</div>

</body>
</html>