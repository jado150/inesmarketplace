<!-- ISHIMWE Remy Gentil    25/33045 -->
<?php
session_start();
include "db.php";

$search = "";
$category = "";

if(isset($_GET['search'])){
    $search = mysqli_real_escape_string($conn, $_GET['search']);
}

if(isset($_GET['category'])){
    $category = mysqli_real_escape_string($conn, $_GET['category']);
}

$query = "SELECT posts.*, users.fullname 
          FROM posts 
          JOIN users ON posts.user_id = users.id 
          WHERE posts.status='Approved'";

if($search != ""){
    $query .= " AND (posts.title LIKE '%$search%' OR posts.description LIKE '%$search%')";
}

if($category != ""){
    $query .= " AND posts.category='$category'";
}

$query .= " ORDER BY posts.created_at DESC";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>UniStack INES</title>
    <link rel="stylesheet" href="assets/index.css">
</head>
<body>

<div class="nav">
    <div class="logo">UniStack INES</div>
    <div class="menu">
        <a href="index.php">Home</a>
        <?php if(isset($_SESSION['user'])): ?>
            <a href="dashboard.php">Dashboard</a>
            <a href="auth.php?logout=1">Logout</a>
        <?php else: ?>
            <a href="auth.php">Login</a>
        <?php endif; ?>
    </div>
</div>

<div class="hero">
    <h1>Student Marketplace & Notice Board</h1>
    <p>
        A trusted digital space for students. 
        Discover housing, buy and sell items, and share important updates in one organized platform.
    </p>
</div>

<div class="searchbox">
    <form method="GET">
        <input type="text" name="search" placeholder="Search here..." value="<?php echo htmlspecialchars($search); ?>">
        <select name="category">
            <option value="">All Categories</option>
            <option value="Housing" <?php if($category=="Housing") echo "selected"; ?>>Housing</option>
            <option value="For Sale" <?php if($category=="For Sale") echo "selected"; ?>>For Sale</option>
            <option value="Announcement" <?php if($category=="Announcement") echo "selected"; ?>>Announcement</option>
        </select>
        <button type="submit">Search</button>
    </form>
</div>

<div class="posts" id="posts">

<?php if(mysqli_num_rows($result) > 0): ?>
    <?php while($row = mysqli_fetch_assoc($result)): ?>
        <div class="card">

            <div class="top">
                <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                <span><?php echo htmlspecialchars($row['category']); ?></span>
            </div>

            <div class="body">
                <p><?php echo nl2br(htmlspecialchars($row['description'])); ?></p>
            </div>

            <?php if(!empty($row['image'])): ?>
                <div>
                    <img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" width="250">
                </div>
            <?php endif; ?>

            <div class="bottom">
                <span>Posted by <?php echo htmlspecialchars($row['fullname']); ?></span>
                <span><?php echo date("d M Y", strtotime($row['created_at'])); ?></span>
            </div>

            <div class="actions">
                <?php
                $pid = $row['id'];
                $count = mysqli_query($conn,"SELECT COUNT(*) as total FROM likes WHERE post_id='$pid'");
                $likes = mysqli_fetch_assoc($count)['total'];
                ?>
                <button onclick="like(<?php echo $pid; ?>)">Like (<?php echo $likes; ?>)</button>

                <?php if(isset($_SESSION['user'])): ?>
                    <button onclick="report(<?php echo $pid; ?>)">Report</button>
                <?php endif; ?>
            </div>

        </div>
    <?php endwhile; ?>
<?php else: ?>
    <div>
        <h3>No approved posts yet</h3>
        <p>Be the first to share something useful with fellow students.</p>
    </div>
<?php endif; ?>

</div>

<div class="footer">
    <p>© <?php echo date("Y"); ?> UniStack INES</p>
</div>

<script src="assets/script.js"></script>
</body>
</html>