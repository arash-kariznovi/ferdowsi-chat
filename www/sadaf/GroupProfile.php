<?php

require_once "header.inc.php";
require_once "classes/Group.class.php";
require_once "classes/Profile.class.php";

if(isset($_REQUEST['group_id'])){
    $groupID = intval($_REQUEST['group_id']);
    $LoadDataJavascriptCode = "document.show_group.group_id_update.value='".htmlentities($groupID, ENT_QUOTES, 'UTF-8')."'; \r\n ";
}
elseif(isset($_REQUEST["submit_update"])){
    $group_id = intval($_REQUEST["group_id_update"]);
    header("Location: GroupProfileUpdate.php?group_id=$group_id");
    die();
}elseif(isset($_REQUEST["delete_member_x"])){
    $userID = $_REQUEST["member_id"];
    $groupID = $_REQUEST["group_id_member_update"];
    ManageMember::removeMember($userID, $groupID);
    header("Location: GroupProfile.php?group_id=$groupID");
    die();
}elseif(isset($_REQUEST["admin_to_member"])){
    $userID = $_REQUEST["member_id"];
    $groupID = $_REQUEST["group_id_member_update"];
    ManageMember::updateRole($userID, $groupID, Role::Member);
    header("Location: GroupProfile.php?group_id=$groupID");
    die();
}elseif(isset($_REQUEST["member_to_admin"])){
    $userID = $_REQUEST["member_id"];
    $groupID = $_REQUEST["group_id_member_update"];
    ManageMember::updateRole($userID, $groupID, Role::Admin);
    header("Location: GroupProfile.php?group_id=$groupID");
    die();
}elseif(isset($_REQUEST["add_member"])){
    $groupID = $_REQUEST["group_id_member_update"];
    if(!Role::isMember(ManageMember::getRole($_SESSION["PersonID"], $groupID))){
        header("Location: GroupAddMember.php?group_id=$groupID");
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
        <!-- show profile -->
        <div class="container" id="show" style="display: block;">
            <form id="show_group" name="show_group">
                <div class="row">
                    <div class="col-lg-2"></div>
                    <div class="col-lg-8" id="main-container">
                        <div class="profile-img">
                            <!-- get pic from database -->
                            <?php
                            $groupID = $_REQUEST["group_id"];
                            $profile = ProfileManager::getProfileByGroupID($groupID);
                            if($profile->image == ""){
                                echo "<img id='profile-photo' name='profile-photo' src='./images/icon-groups-default.jpg'>";
                            }else{
                                echo "<img id='profile-photo' name='profile-photo' src='data:image/png;base64,".base64_encode($profile->image)."'>";
                            }
                            ?>
                        </div>
                        <div style="text-align: center;">
                            <!-- get name of group from database -->
                            <label class="group-name">
                                <h3 class="group-name"><?php
                                    echo $profile->name;
                                ?></h3>
                            </label>
                            <!-- get description of group from database -->
                            <p class="text-description"><?php echo $profile->description; ?></p>

                            <input type="hidden" id="group_id_update" name="group_id_update">

                            <input type="submit" id="submit_update" name="submit_update" class="profile-edit-btn icon"
                                value="اصلاح پروفایل">
                        </div>
                    </div>
                    <div class="col-lg-2"></div>
                </div>
            </form><script>
	            <? echo $LoadDataJavascriptCode; ?>
            </script>
        </div>

        <!-- show list of member -->
        <div class="container" id="show-member-list" style="display: block;">
            <form>
                <div class="row">
                    <div class="col-lg-2"></div>
                    <div class="col-lg-8" id="main-container">
                        <?php
                            $sessionUserRole = ManageMember::getRole($_SESSION["PersonID"], $groupID);
                            if(!Role::isMember($sessionUserRole)){
                                echo '<input type="submit" id="add_member" name="add_member" class="add-new-member icon-plus" value="افزودن عضو جدید">';
                            }
                        ?>                        
                        <div class="col-sm-1"></div>
                        <div class="col-md-10" style="margin-top: 20px;">
                            <ul class="list-group">
                                <button class="list-group-item list-group-item-info" id="memeber-list"
                                    style="clear: both;">
                                    <h4 class="align-right" style="float:right;">اعضا</h4>
                                </button>
                                <button class="list-group-item list-group-item-action" id="member1"
                                    style="clear: both;">
                                    <?php
                                        if(isset($_REQUEST["group_id"])){
                                            $groupID = $_REQUEST["group_id"];
                                        }elseif($_REQUEST["group_id_update"]){
                                            $groupID = $_REQUEST["group_id_update"];
                                        }else{die("invalid Request.");}
                                        $memberList = ManageMember::getMemberList($groupID);
                                        foreach($memberList as $member){
                                            $memberName = $member->fname . " " . $member->lname;
                                            $memberRole = $member->role;
                                            $userID = $member->userID;
                                            echo '<form><h5 class="member-name align-right" style="float:right;" id="member-1" name="member-1">
                                            ' . $memberName .' 
                                            <h6 class="role align-left" id="role-member-1">'. Role::toFarsi($memberRole) .'</h6>
                                            <input type="hidden" name="member_id" id="member_id" 
                                            value="'. $userID .'">
                                            <input type="hidden" name="group_id_member_update" id="group_id_member_update" 
                                            value="'. $groupID .'">';                                                                                    
                                            if(!Role::isMember($sessionUserRole)){
                                                if($memberRole != Role::Owner){
                                                    echo '<input type="image"
                                                        src="images/arrow-16.png"
                                                        id="delete_member" name="delete_member" class="hide-show-delete align-left" style="width:1vw; height:1vw;">';
                                                }
                                                if($memberRole == Role::Admin){
                                                    echo '<input type="submit" class="hide-show-delete align-left role-btn"
                                                        value="عضو" id="admin_to_member" name="admin_to_member">';
                                                }elseif($memberRole == Role::Member){
                                                    echo '<input type="submit" class="hide-show-delete align-left role-btn"
                                                        value="مدیر" id="member_to_admin" name="member_to_admin">';
                                                }
                                            }
                                            echo '</h5><br></form><hr><br><br>';
                                        }
                                    ?>                                    
                                </button>
                            </ul>
                        </div>
                        <div class="col-sm-1"></div>
                    </div>
                    <div class="col-lg-2"></div>
                </div>
            </form>
        </div>
    </div>
</main>
</body>

</html>
