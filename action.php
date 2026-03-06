<!-- IYANDEMYE Jean De Dieu 25/30575 -->
<?php
session_start();
include "db.php";

if(!isset($_SESSION['user'])){
    exit;
}

$user = $_SESSION['user'];

if(isset($_POST['like'])){
    $post = $_POST['post'];

    $check = mysqli_query($conn,"SELECT * FROM likes WHERE user_id='$user' AND post_id='$post'");

    if(mysqli_num_rows($check) == 0){
        mysqli_query($conn,"INSERT INTO likes(user_id,post_id) VALUES('$user','$post')");
    }
}

if(isset($_POST['report'])){
    $post = $_POST['post'];

    $check = mysqli_query($conn,"SELECT * FROM reports WHERE user_id='$user' AND post_id='$post'");

    if(mysqli_num_rows($check) == 0){
        mysqli_query($conn,"INSERT INTO reports(user_id,post_id) VALUES('$user','$post')");
    }
}
?>