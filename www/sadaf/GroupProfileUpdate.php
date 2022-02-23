<?php

require_once "header.inc.php";
require_once "classes/Group.class.php";
require_once "classes/Profile.class.php";

if(isset($_REQUEST['group_id']) && !(isset($_REQUEST["group_id_update"]))){
    $groupID = intval($_REQUEST['group_id']);
    $profile = ProfileManager::getProfileByGroupID($groupID);
    $LoadDataJavascriptCode = "document.update_group.group_name.value='".htmlentities($profile->name, ENT_QUOTES, 'UTF-8')."'; \r\n ";
    $LoadDataJavascriptCode .= "document.update_group.description.value='".htmlentities($profile->description, ENT_QUOTES, 'UTF-8')."'; \r\n ";
    $LoadDataJavascriptCode .= "document.update_group.group_id_update.value='".htmlentities($groupID, ENT_QUOTES, 'UTF-8')."'; \r\n ";

}
elseif(isset($_POST["submit_update"])){   
    if(isset($_POST["group_id_update"])){
        $groupID = intval($_POST["group_id_update"]);
        ProfileManager::updateName($groupID,$_POST['group_name']);
        ProfileManager::updateDescription($groupID,$_POST['description']);
        $image = "";
        if(isset($_FILES["picture"]) && $_FILES["picture"]["name"] != ""){
        
            if($_FILES['picture']['error']!= "0"){
                echo "Error: ".$_FILES['picture']['error'];
                die();
            }
            $image = file_get_contents($_FILES["picture"]["tmp_name"]);
            ProfileManager::updateImageByGroupID($groupID,$image);
        }
        header("Location: GroupProfile.php?group_id=$groupID");
        die();
    }
}
else{
    echo "Invalid Request.";
    die();
}
?>


<!DOCTYPE html>
<html>

<head>
    <meta name="author" content="Razi">
    <meta name="keywords" content="create">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Creation</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="/bootstrap-4.3.1-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/CreateGroup.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="/bootstrap-4.3.1-dist/js/bootstrap.min.js"></script>
</head>

<body>
<main>
    <div class="container-fluid row">
        <div class="container" id="edit" style="display: block;">
            <form id="update_group" name="update_group" method="POST" enctype="multipart/form-data">
                <div class="row" id="div1" name="div1">
                    <div class="col-lg-2"></div>
                    <div class="col-lg-8" id="main-container" name="main-container">
                        <div class="profile-pic" style="text-align: center;">
                            <!-- profile photo from database -->
                            <?php
                            $groupID = $_REQUEST["group_id"];
                            $profile = ProfileManager::getProfileByGroupID($groupID);
                            if($profile->image == ""){
                                echo "<img id='edit-pic' width = 200 name='edit-pic' src='./images/icon-groups-default.jpg'>";
                            }else{
                                echo "<img id='edit-pic' width = 200 name='edit-pic' src='data:image/png;base64,".base64_encode($profile->image)."'>";
                            }
                            ?>
                        </div>
                        <div class="edit-file" style="text-align: center;">
                            <input type="file" accept=".png,.jpg,.jpeg,.gif" placeholder="picture" name="picture"
                                id="picture">
                        </div>
                        <div style="text-align: center;" id="div2" name="div2">
                            <!-- get name of group for placeholder from database -->
                            <h4 class="text-center">نام گروه:</h4>
                            <input type="text" placeholder="نام گروه" id="group_name" name="group_name">
                            <!-- get description of group from database -->
                            <h4 class="text-center">توضیحات:</h4>
                            <input type="text" id="description" name="description">
                            <br>
                            <input type="hidden" id="group_id_update" name="group_id_update">

                            <input type="submit" class="profile-edit-btn icon" value="اصلاح پروفایل" id="submit_update" name="submit_update">
                        </div>
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
