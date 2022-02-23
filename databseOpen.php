<?php
//    $ipAddress=$_SERVER['REMOTE_ADDR'];
    $connect = new PDO("mysql:host=localhost;dbname=Messenger", "root", "");
    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
