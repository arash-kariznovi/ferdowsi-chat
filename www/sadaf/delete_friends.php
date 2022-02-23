<?php
include "../shares/header.inc.php";

$mysql = pdodb::getInstance();
session_start();
ini_set("error_reporting",E_ALL);

    $id = $_GET["id"];
    $query = "delete from friends where PersonID=" . $_SESSION["PersonID"] . " and FriendID=".$id;
    $mysql->Execute($query);
header("location:friends.php");

?>