<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="css/style.css"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <meta charset="UTF-8">
    <title>login</title>
    <meta name="google-site-verification" content="google-site-verification=ZHpZj5Y9GBZTRN6JDv29tP1PJw-lWEa5LjMxIVscMfU">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
</head>
<body class="login-body">
<div class="login-container">
    <form action="checklogin.php" name="form" method="post" class="login-form">
        <label>کد دانشجویی</label>
        <input type="text" required name="StudentID" class="studentId">
        <label>کلمه عبور</label>
        <input type="password" required name="Password" class="password">
        <input type="submit" name="submit" class="btn-login">
    </form>
</div>
<?php
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true)
    header("Location:index.php");
    ?>
</body>
</html>