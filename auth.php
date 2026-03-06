<!-- ISHIMWE Remy Gentil    25/33045 -->

<?php
session_start();
include "db.php";

if(isset($_GET['logout'])){
    session_destroy();
    header("Location: index.php");
    exit;
}

// Register
if(isset($_POST['register'])){
    $name = mysqli_real_escape_string($conn,$_POST['name']);
    $email = mysqli_real_escape_string($conn,$_POST['email']);
    $pass = $_POST['password'];

    if(!preg_match("/@ines\.ac\.rw$/",$email)){
        $error = "Use your INES school email";
    } else {
        $check = mysqli_query($conn,"SELECT * FROM users WHERE email='$email'");
        if(mysqli_num_rows($check) > 0){
            $error = "Email already exists";
        } else {
            $hash = password_hash($pass,PASSWORD_DEFAULT);
            mysqli_query($conn,"INSERT INTO users(fullname,email,password,role,status)
                                VALUES('$name','$email','$hash','student','active')");
            $success = "Registration successful! You can now login.";
        }
    }
}

// Login
if(isset($_POST['login'])){
    $email = mysqli_real_escape_string($conn,$_POST['email']);
    $pass = $_POST['password'];

    $query = mysqli_query($conn,"SELECT * FROM users WHERE email='$email' AND status='active'");
    $user = mysqli_fetch_assoc($query);

    if($user && password_verify($pass,$user['password'])){
        $_SESSION['user'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['name'] = $user['fullname'];

        if($user['role']=="student"){
            header("Location: dashboard.php");
        } else {
            header("Location: admin.php");
        }
        exit;
    } else {
        $error = "Invalid credentials or inactive account";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login / Register</title>
    <link rel="stylesheet" type="text/css" href="assets/auth.css">
</head>
<body>
<div class="auth-box">

    <h2 class="auth-heading">Login</h2>
    <?php if(isset($error)) echo "<p class='msg msg-error'>$error</p>"; ?>
    <?php if(isset($success)) echo "<p class='msg msg-success'>$success</p>"; ?>

    <form method="POST">
        <input class="input-field" type="email" name="email" placeholder="School Email" required>
        <input class="input-field" type="password" name="password" placeholder="Password" required>
        <button class="btn-action" type="submit" name="login">Login</button>
    </form>

    <p>I u don't have account Register <a href="register.php">Here</a></p>

</div>

</div>

</body>
</html>