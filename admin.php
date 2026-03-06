<!-- IYANDEMYE Jean De Dieu 25/30575 -->
<?php
session_start();
include "db.php";

if(!isset($_SESSION['user']) || ($_SESSION['role'] != "admin" && $_SESSION['role'] != "moderator")){
    header("Location: index.php");
    exit;
}

// Approve post
if(isset($_GET['approve'])){
    $id = intval($_GET['approve']);
    mysqli_query($conn,"UPDATE posts SET status='Approved' WHERE id='$id'");
}

// Reject post
if(isset($_GET['reject'])){
    $id = intval($_GET['reject']);
    mysqli_query($conn,"UPDATE posts SET status='Rejected' WHERE id='$id'");
}

// Deactivate user (admin only)
if(isset($_GET['deactivate']) && $_SESSION['role']=="admin"){
    $uid = intval($_GET['deactivate']);
    mysqli_query($conn,"UPDATE users SET status='inactive' WHERE id='$uid'");
}

// Fetch pending posts with report count
$pending = mysqli_query($conn,"
SELECT posts.*, users.fullname,
(SELECT COUNT(*) FROM reports WHERE reports.post_id=posts.id) as reports
FROM posts
JOIN users ON posts.user_id=users.id
WHERE posts.status='Pending'
ORDER BY posts.created_at DESC
");

// Fetch all users
$users = mysqli_query($conn,"SELECT * FROM users ORDER BY role DESC, fullname ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <link rel="stylesheet" type="text/css" href="assets/admin.css">
</head>
<body>

<h2>Admin Panel - <?php echo htmlspecialchars($_SESSION['name']); ?></h2>
<a href="index.php">Home</a>
<a href="auth.php?logout=1">Logout</a>

<hr>

<h3>Pending Posts</h3>
<?php if(mysqli_num_rows($pending) > 0): ?>
    <?php while($row = mysqli_fetch_assoc($pending)): ?>
        <div class="post-box">
            <h4><?php echo htmlspecialchars($row['title']); ?> - <?php echo htmlspecialchars($row['fullname']); ?></h4>
            <p><?php echo nl2br(htmlspecialchars($row['description'])); ?></p>
            <?php if(!empty($row['image'])): ?>
                <img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" alt="Post Image">
            <?php endif; ?>
            <p>Reports: <strong><?php echo $row['reports']; ?></strong></p>
            <a href="admin.php?approve=<?php echo $row['id']; ?>">Approve</a>
            <a href="admin.php?reject=<?php echo $row['id']; ?>">Reject</a>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p>No pending posts at the moment.</p>
<?php endif; ?>

<?php if($_SESSION['role']=="admin"): ?>
<h3>Manage Users</h3>
<?php if(mysqli_num_rows($users) > 0): ?>
    <?php while($u = mysqli_fetch_assoc($users)): ?>
        <div class="user-box">
            <span><?php echo htmlspecialchars($u['fullname']); ?> - <?php echo htmlspecialchars($u['role']); ?> - <?php echo htmlspecialchars($u['status']); ?></span>
            <?php if($u['status'] != "inactive"): ?>
                <a href="admin.php?deactivate=<?php echo $u['id']; ?>" onclick="return confirm('Deactivate this user?')">Deactivate</a>
            <?php else: ?>
                <span class="inactive-label">Inactive</span>
            <?php endif; ?>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p>No users found.</p>
<?php endif; ?>
<?php endif; ?>
</body>
</html>