<?php
include "../shares/header.inc.php";
$id = $_GET["id"];
$mysql = pdodb::getInstance();
session_start();
    $query = "delete from block where blockerID=".$_SESSION["PersonID"]." and blockedID=".$id;
    $mysql->Execute($query);
header("location:blocked.php");