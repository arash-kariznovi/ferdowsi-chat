<?php

try {
//    if($_GET['log'] === 'logout') {
//        unset($_COOKIE['user']);
//        header("Location:login.php");
//    }

//    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
//        header("Location:index.php");
//    }

//    $ipAddress = $_SERVER['REMOTE_ADDR']; //Get Client ip Address.
    $connection = new PDO("mysql:host=localhost;port=3306;dbname=hashtad1_Messenger", "hashtad1", "9OB25W(r@nlFd8");
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT * FROM User WHERE studentId='".$_POST['StudentID']."'";
    $quer = $connection->query($sql);
    $row = $quer->fetch(PDO::FETCH_ASSOC);

    if($row['password'] === $_POST['Password']) {
        session_start();
        setcookie('user', json_encode([
            'studentid' => $_POST['StudentID'],
            'password' => $_POST['Password']
        ]), time() + 3600 * 24 * 30);
    }

    header("Location:index.php");
}
catch (PDOException $e) {
echo "Error: ".$e->getMessage();
}