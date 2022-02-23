<?php
include "../shares/header.inc.php";
ini_set("error_reporting",E_ALL);
$id = $_GET["id"];
$mysql = pdodb::getInstance();


$query = "insert into block (blockerID,blockedID) values (".$_SESSION["PersonID"].",".$id.")";
$mysql->Execute($query);

$query_2 = "delete from friends where PersonID=".$_SESSION["PersonID"]." and FriendID=".$id;
$mysql->Execute($query_2);
header("location:users.php");