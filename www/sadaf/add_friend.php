<?php

include "../shares/header.inc.php";

$mysql = pdodb::getInstance();
session_start();
ini_set("error_reporting",E_ALL);

$id = $_GET["id"];
echo $id;
$query = "insert into friends (PersonID,FriendID) values (".$_SESSION["PersonID"].",".$id.")";
$identity = $mysql->Execute($query);
header("location:users.php");

