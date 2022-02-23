<?php

include "header.inc.php";
HTMLBegin();

$mysql = pdodb::getInstance();
session_start();
$values = array();
$usersIDs = array();




function checkFriend($PersonId, $FriendId, $mysql){
    $checkFriendQuery = "SELECT PersonID FROM sadaf.friends WHERE PersonID = ? AND FriendID = ?;";
    $mysql->Prepare($checkFriendQuery);
    $res = $mysql->ExecuteStatement(array($PersonId, $FriendId));
    while ($rec = $res->fetch()) {
        if(!empty($rec["PersonID"])){
            return true;
        }
    }
    return false;
}

function checkIfBlocked($blockerID,$blockedID,$mysql){
    $checkBlockQuery= "SELECT blockerID FROM sadaf.block WHERE blockerID = ? AND blockedID = ?;";
    $mysql->Prepare($checkBlockQuery);
    $res = $mysql->ExecuteStatement(array($blockerID, $blockedID));
    while ($rec = $res->fetch()) {
        if(!empty($rec["blockerID"])){
            return true;
        }
    }
    return false;
}

if (isset($_REQUEST['search']) && !empty($_REQUEST['searchValue']) ) {
    $selectedValue = $_REQUEST['searchSelect'];
    $searchValue = "";
    $query = "";
    $searchValue = $_REQUEST['searchValue'];
    if ($selectedValue == 'Name') {
        $query = "SELECT Name,NameShow,Person_ID FROM privacy WHERE Name = ? AND (NameShow= 0 OR NameShow = 1);";
    } else if ($selectedValue == 'PhoneNumber') {
        $query = "SELECT PhoneNumber,PhoneNo,Person_ID FROM sadaf.privacy WHERE PhoneNumber = ? AND (PhoneNo= 0 OR PhoneNo = 1);";
    } else if ($selectedValue == 'Faculty') {
        $query = "SELECT FacultyName,Faculty,Person_ID FROM sadaf.privacy WHERE FacultyName = ? AND (Faculty= 0 OR Faculty = 1);";
    } else if ($selectedValue == 'UserID') {
        $query = "SELECT Person_ID FROM sadaf.privacy WHERE Person_ID = ? ;";
    } else if ($selectedValue == 'Subject') {
        $query = "SELECT SubjectName,Subject,Person_ID FROM sadaf.privacy WHERE SubjectName = ? AND (Subject= 0 OR Subject = 1);";
    }
$mysql->Prepare($query);
$res = $mysql->ExecuteStatement(array($searchValue));
while ($rec = $res->fetch()) {
    if (!empty($rec["Name"])) {
        if ($rec["NameShow"] == 1) {
            if (checkFriend($rec["Person_ID"],$_SESSION["PersonID"], $mysql)) {
                $value = $rec["Name"]."( شماره دانشجویی : ".$rec["Person_ID"].")";
                $values[] = $value;
                $usersIDs[] = $rec["Person_ID"];
            }
        } else if ($rec["NameShow"] == 0) {
            $value = $rec["Name"]."(شماره دانشجویی : ".$rec["Person_ID"].")";
            $values[] = $value;
            $usersIDs[] = $rec["Person_ID"];
        }
    }
    else if (!empty($rec["PhoneNumber"])) {
        if ($rec["PhoneNo"] == 1) {
            if (checkFriend($rec["Person_ID"],$_SESSION["PersonID"], $mysql)) {
                $value = $rec["PhoneNumber"]."(شماره دانشجویی : ".$rec["Person_ID"].")";
                $values[] = $value;
                $usersIDs[] = $rec["Person_ID"];
            }
        } else if ($rec["PhoneNo"] == 0) {
            $value = $rec["PhoneNumber"]."(شماره دانشجویی : ".$rec["Person_ID"].")";
            $values[] = $value;
            $usersIDs[] = $rec["Person_ID"];
        }
    }
    else if (!empty($rec["SubjectName"])) {
        if ($rec["Subject"] == 1) {
            if (checkFriend($rec["Person_ID"],$_SESSION["PersonID"], $mysql)) {
                $value = $rec["SubjectName"]." (شماره دانشجویی : ".$rec["Person_ID"].")";
                $values[] = $value;
                $usersIDs[] = $rec["Person_ID"];
            }
        } else if ($rec["Subject"] == 0) {
            $value = $rec["SubjectName"]."(شماره دانشجویی : ".$rec["Person_ID"].")";
            $values[] = $value;
            $usersIDs[] = $rec["Person_ID"];
        }
    }
    else if (!empty($rec["FacultyName"])) {
        if ($rec["Faculty"] == 1) {
            if (checkFriend($rec["Person_ID"], $_SESSION["PersonID"], $mysql)) {
                $value = $rec["FacultyName"]."(شماره دانشجویی : ".$rec["Person_ID"].")";
                $values[] = $value;
                $usersIDs[] = $rec["Person_ID"];
            }
        } else if ($rec["Faculty"] == 0) {
            $value = $rec["FacultyName"]."(شماره دانشجویی : ".$rec["Person_ID"].")";
            $values[] = $value;
            $usersIDs[] = $rec["Person_ID"];
        }
    }
    else if (!empty($rec["Person_ID"])) {
        $value =" شماره دانشجویی : ".$rec["Person_ID"];
        $values[] = $value;
        $usersIDs[] = $rec["Person_ID"];
    }
    }
}

?>

<!DOCTYPE html>
<html>

<div class="wrapper" dir="rtl">
    <!-- Page Content  -->

    <div id="content">

        <form>
            <div class="col-md-12">
                <div class="form-group">
                    <br>
                    <label for="usr" style="margin-right: 10%">
                        <h1>جست و جوی کاربران</h1>
                    </label>
                    <br>
                    <div class="row">
                        <div class="col-md-10">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <select class="custom-select" id="searchSelect" name="searchSelect" style="margin-right: 10%; max-width: 100%">
                                        <option value="Name">نام</option>
                                        <option value="PhoneNumber">شماره تماس</option>
                                        <option value="UserID">شماره دانشجویی</option>
                                        <option value="Faculty">دانشکده</option>
                                        <option value="Subject">رشته</option>
                                    </select>
                                </div>
                                <input placeholder="جست و جوی کاربران" type="text" class="form-control"
                                       aria-label="Text input with dropdown button" id="searchValue" name="searchValue" />
                            </div>
                        </div>
                        <div class="col-md-2">
                            <input type="submit"  class="btn btn-info" name="search" id="search" value="جست و جو" />
                        </div>
                    </div>
                </div>
                    <div class="list-group">
                         <?php
                            for ($index = 0; $index < count($values); $index++) {
                                echo '<li class="list-group-item d-flex" style="margin-right: 5% ; max-width: 75%">';
                                ?>
                                    <div class="col-sm-8">
                                        <p class="p-0 m-0 flex-grow-1" ><a href="about.php?id=<?php echo $usersIDs[$index]; ?>"><?php echo $values[$index] ?></a></p>
                                    </div>
                        <?php
                                if(checkIfBlocked($_SESSION["PersonID"],$usersIDs[$index],$mysql)){
                                    ?>
                                    <div class="col-sm-1">
                                        <a class="btn btn-danger"  href="unblock.php?id=<?php echo $usersIDs[$index]?>" >آنبلاک</a>
                                    </div>
                                    <?php
                                }else{
                                    ?>
                                    <div class="col-sm-1">
                                        <a class="btn btn-danger"  href="block.php?id=<?php echo $usersIDs[$index]?>" >بلاک</a>
                                    </div>
                                    <?php
                                }
                                echo '<div style="margin: 2px;"></div>';
                                if(checkFriend( $_SESSION["PersonID"],$usersIDs[$index],$mysql)){
                                    ?>
                                    <div class="col-sm-3">
                                        <a href="delete_friend.php?id=<?php echo $usersIDs[$index]?>" class="btn btn-info " >حذف دوست</a>
                                    </div>
                                    <?php
                                }else{
                                    ?>
                                    <div class="col-sm-3">
                                        <a  href="add_friend.php?id=<?php echo $usersIDs[$index]?>" class="btn btn-info" >افزودن دوست</a>
                                    </div>
                                    <?php
                                }
                                echo '</li>';
                            }
                            ?>
                    </div>
            </div>
        </form>



        <!-- jQuery CDN - Slim version (=without AJAX) -->
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
                integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
                crossorigin="anonymous"></script>
        <!-- Popper.JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"
                integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ"
                crossorigin="anonymous"></script>
        <!-- Bootstrap JS -->
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"
                integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm"
                crossorigin="anonymous"></script>

        <script type="text/javascript">
            $(document).ready(function () {
                $('#sidebarCollapse').on('click', function () {
                    $('#sidebar').toggleClass('active');
                });
            });
        </script>
        <?php
        HTMLEnd();
        ?>
