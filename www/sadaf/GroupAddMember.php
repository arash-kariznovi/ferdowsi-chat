<?php

require_once "header.inc.php";
require_once "classes/Group.class.php";
require_once "classes/Profile.class.php";

if(isset($_REQUEST['group_id'])){
    $groupID = intval($_REQUEST['group_id']);
    $LoadDataJavascriptCode = "document.add_member.group_id_add.value='".htmlentities($groupID, ENT_QUOTES, 'UTF-8')."'; \r\n ";
}elseif(isset($_REQUEST["add_member"])){
    $userID = $_REQUEST["user_id"];
    $role = $_REQUEST["member_role"];
    $groupID = $_REQUEST["group_id_add"];
    ManageMember::add($groupID, $userID, $role);
    header("Location: GroupProfile.php?group_id=$groupID");
    die();
}else{
    die("Invalid Request!");
}

?>

<!DOCTYPE html>
<html dir="rtl" lang="fa">

<head>
    <meta name="author" content="Razi">
    <meta name="keywords" content="create">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Creation</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <!-- <link rel="stylesheet" href="/bootstrap-4.3.1-dist/css/bootstrap.min.css"> -->
    <script src="https://kit.fontawesome.com/8d3c106bfc.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/CreateGroup.css">
</head>

<body>
    <main>
        <!-- main container -->
        <div class="container-fluid">            
            <!-- add member -->
            <div class="container" id="add" style="display: block;">
                <form id="add_member" name="add_member">
                    <div class="row">
                        <div class="col-lg-2"></div>
                        <div class="col-lg-8" id="main-container">
                            <div class="col-sm-1"></div>
                            <div class="col-md-10" style="margin-top: 20px;">
                                <h2 class="text-center">عضو جدید</h2>
                                <h4 class="text-right">شماره کاربر:</h4>
                                <input type="text" id="user_id" name="user_id" placeholder="شماره کاربر" required>
                                <h4 class="text-right">نقش عضو جدید:</h4>
                                <select class="role-member" id="member_role" name="member_role" title="نقش عضو">
                                    <option value="Admin">مدیر گروه</option>
                                    <option value="Member" selected>عضو ساده</option>
                                </SELECT>
                                <br>
                                <br>
                                <input type="hidden" id="group_id_add" name="group_id_add">
                                <input type="submit" id="add_member" name="add_member" class="btn" value="افزودن">
                            </div>
                            <div class="col-sm-1"></div>
                        </div>
                        <div class="col-lg-2"></div>
                    </div>
                </form><script>
	                <? echo $LoadDataJavascriptCode; ?>
                </script>
            </div>
        </div>
    </main>
</body>
</html>