<?php
setcookie('user', "", time() - 3600);
session_destroy();
session_write_close();

header('Location:index.php');
