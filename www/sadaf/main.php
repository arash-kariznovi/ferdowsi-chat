<?php
ini_set('display_errors', true);
include('header.inc.php');
?>
<html dir="rtl">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="css/main.css">
</head>

   <!-- <frameset cols="*,15%" >
   <frame id=MainContent name=MainContent src="MainContent.php" class="main-content">
   <frame id=Menu name=Menu src="Menu.php" class="main-menu">
   </frameset>--> 

	<body style="margin:0">
      <div class="main-frame" id="main-frame">
         <iframe id="MainContent" name=MainContent src="MainContent.php" height="100%" frameborder="0"></iframe>
      </div>
      <div class="menu-frame" id="menu-frame">
         <iframe id="Menu" name=Menu src="Menu.php" height="100%" frameborder="0"></iframe>
      </div>
   </body>
</html>