<?php
    include "header.inc.php";
    HTMLBegin();
?>
<!DOCTYPE html>
<html>
    <div class="wrapper" dir="rtl">
        <div id="content">
            <div class="row">
                <div class="col-lg-8" style="margin-right: 5%">
                    <br>
                    <h1>پروفایل کاربر</h1>
                </div>
                <div class="col-lg-12" style="text-align: center;">
                    <?php
                    $mysql = pdodb::getInstance();
                    session_start();
                    $id = $_GET["id"];
                    $query = "select * from privacy where Person_ID=".$id;
                    $res = $mysql->Execute($query);
                    while ($rec = $res->fetch()){
                    if($rec["PictureShow"]==0){
                        echo '<img src="data:image/jpeg;base64,' . base64_encode($rec["image"]) . '" class="rounded-circle" alt="Cinque Terre" width="300" height="300">';

                    }
                    elseif ($rec["PictureShow"]==2){
//                        echo "Hidden";

                    }elseif ($rec["PictureShow"]==1){
                        $query = "select FriendID from friends where PersonID=".$id." and FriendID=".$_SESSION["PersonID"];
                        $res = $mysql->Execute($query);
                        $ph = $res->fetch();
                        echo $ph["FriendID"];
                        if($ph["FriendID"]==$_SESSION["PersonID"]){
                            echo '<img src="data:image/jpeg;base64,' . base64_encode($rec["image"]) . '" class="rounded-circle" alt="Cinque Terre" width="300" height="300">';

                        }else{
//                            echo "Hidden";
                        }
                    }
                     ?>
                </div>
                <div class="col-lg-12" style="margin-top: 30px;text-align: center;">
                    <div class="col"><b style="font-size: 28px;"><?php
                            if($rec["NameShow"]==0){
                                echo $rec["Name"];
                            }
                            elseif ($rec["NameShow"]==2){
//                                echo "Hidden";

                            }elseif ($rec["NameShow"]==1){
                                $query = "select FriendID from friends where PersonID=".$id." and FriendID=".$_SESSION["PersonID"];
                                $res = $mysql->Execute($query);
                                $ph = $res->fetch();
                                if($ph["FriendID"]==$_SESSION["PersonID"]){
                                    echo $rec["Name"];
                                }else{
//                                    echo "Hidden";
                                }
                            }
                            ?></b></div>
                </div>
                <div class="col-lg-12" style="margin-bottom: 30px;text-align: center;">
                    <div class="col"><b style="font-size: 22px; font-weight: lighter;">
                            <?php

                                echo  $rec["Person_ID"];

                            ?>
                        </b></div>
                </div>
                <br>
                <br>

                <div class="col-lg-10">
                    <?php
                    if(!empty($rec["PhoneNo"])){
                        if($rec["PhoneNo"]==0){
                            echo '<div class="row" style="margin-right: 5%">';
                            echo '<div class="col-lg-2" >';
                            echo '<h5>شماره تماس : </h5>';
                            echo '</div>';
                            echo '<div class="col-lg-2" >';
                            echo $rec["PhoneNumber"];
                            echo '</div>';
                            echo '</div>';
                        }
                        elseif ($rec["PhoneNo"]==2){
//                        echo "Hidden";

                        }elseif ($rec["PhoneNo"]==1){
                            $query = "select FriendID from friends where PersonID=".$id." and FriendID=".$_SESSION["PersonID"];
                            $res = $mysql->Execute($query);
                            $ph = $res->fetch();
                            if($ph["FriendID"]==$_SESSION["PersonID"]){
                                echo '<div class="row" style="margin-right: 5%">';
                                echo '<div class="col-lg-2" >';
                                echo '<h5>شماره تماس : </h5>';
                                echo '</div>';
                                echo '<div class="col-lg-2" >';
                                echo $rec["PhoneNumber"];
                                echo '</div>';
                                echo '</div>';
                            }
//                        else{
//                            echo "Hidden";
//                        }
                        }
                    }
                    ?>

                </div>
<!--                <div class="col-lg-2" style="margin-right: 5%">-->
<!--                    <h5>بیوگرافی : </h5>-->
<!--                </div>-->
                <div class="col-lg-10">
                    <?php
                    if(!empty($rec["bio"])){
                        echo '<div class="row" style="margin-right: 5%">';
                        echo '<div class="col-lg-2" >';
                        echo '<h5>بیوگرافی : </h5>';
                        echo '</div>';
                        echo '<div class="col-lg-2" >';
                        echo  $rec["bio"];
                        echo '</div>';
                        echo '</div>';

                    }

                    ?>
                </div>
<!--                <div class="col-lg-2" style="margin-right: 5%">-->
<!--                    <h5>آدرس وبسایت : </h5>-->
<!--                </div>-->
                <div class="col-lg-10">
                    <?php
                    if (!empty($rec["bio"])) {
                        echo '<div class="row" style="margin-right: 5%">';
                        echo '<div class="col-lg-2" >';
                        echo '<h5>آدرس وبسایت : </h5>';
                        echo '</div>';
                        echo '<div class="col-lg-2" >';
                        echo  $rec["website"];
                        echo '</div>';
                        echo '</div>';

                    }
                    ?>
                </div>
<!--                <div class="col-lg-2" style="margin-right: 5%">-->
<!--                    <h5>نام دانشکده : </h5>-->
<!--                </div>-->
                <div class="col-lg-10">
                    <?php
                    if (!empty($rec["Faculty"])) {
                        if($rec["Faculty"]==0){
                            echo '<div class="row" style="margin-right: 5%">';
                            echo '<div class="col-lg-2" >';
                            echo '<h5>نام دانشکده : </h5>';
                            echo '</div>';
                            echo '<div class="col-lg-2" >';
                            echo $rec["FacultyName"];
                            echo '</div>';
                            echo '</div>';
                        }
                        elseif ($rec["Faculty"]==2){
//                            echo "Hidden";

                        }elseif ($rec["Faculty"]==1){
                            $query = "select FriendID from friends where PersonID=".$id." and FriendID=".$_SESSION["PersonID"];
                            $res = $mysql->Execute($query);
                            $ph = $res->fetch();
                            if($ph["FriendID"]==$_SESSION["PersonID"]){
                                echo '<div class="row" style="margin-right: 5%">';
                                echo '<div class="col-lg-2" >';
                                echo '<h5>نام دانشکده : </h5>';
                                echo '</div>';
                                echo '<div class="col-lg-2" >';
                                echo $rec["FacultyName"];
                                echo '</div>';
                                echo '</div>';
                            }else{
//                                echo "Hidden";
                            }
                        }
                    }

                    ?>

                </div>
<!--                <div class="col-lg-2" style="margin-right: 5%">-->
<!--                    <h5>تاریخ تولد : </h5>-->
<!--                </div>-->
                <div class="col-lg-10">
                    <?php
                    if(!empty($rec["Birth"])){
                        if($rec["BirthDate"]==0){
                            echo '<div class="row" style="margin-right: 5%">';
                            echo '<div class="col-lg-2" >';
                            echo '<h5>تاریخ تولد : </h5>';
                            echo '</div>';
                            echo '<div class="col-lg-2" >';
                            echo substr($rec["Birth"],0,10);
                            echo '</div>';
                            echo '</div>';
                        }
                        elseif ($rec["BirthDate"]==2){
//                        echo "Hidden";

                        }elseif ($rec["BirthDate"]==1){

                            $query = "select FriendID from friends where PersonID=".$id." and FriendID=".$_SESSION["PersonID"];
                            $res = $mysql->Execute($query);
                            $ph = $res->fetch();
                            if($ph["FriendID"]==$_SESSION["PersonID"]){
                                echo '<div class="row" style="margin-right: 5%">';
                                echo '<div class="col-lg-2" >';
                                echo '<h5>تاریخ تولد : </h5>';
                                echo '</div>';
                                echo '<div class="col-lg-2" >';
                                echo substr($rec["Birth"],0,10);
                                echo '</div>';
                                echo '</div>';
                            }else{
//                            echo "Hidden";
                            }
                        }
                    }
                    ?>

                </div>
<!--                <div class="col-lg-2" style="margin-right: 5%">-->
<!--                    <h5>رشته ی تحصیلی : </h5>-->
<!--                </div>-->
                <div class="col-lg-10">
                    <?php
                    if(!empty($rec["SubjectName"])){
                        if($rec["Subject"]==0){
                            echo '<div class="row" style="margin-right: 5%">';
                            echo '<div class="col-lg-2" >';
                            echo '<h5>رشته ی تحصیلی : </h5>';
                            echo '</div>';
                            echo '<div class="col-lg-2" >';
                            echo $rec["SubjectName"];
                            echo '</div>';
                            echo '</div>';
                        }
                        elseif ($rec["Subject"]==2){
//                            echo "Hidden";

                        }elseif ($rec["Subject"]==1){
                            $query = "select FriendID from friends where PersonID=".$id." and FriendID=".$_SESSION["PersonID"];
                            $res = $mysql->Execute($query);
                            $ph = $res->fetch();
                            if($ph["FriendID"]==$_SESSION["PersonID"]){
                                echo '<div class="row" style="margin-right: 5%">';
                                echo '<div class="col-lg-2" >';
                                echo '<h5>رشته ی تحصیلی : </h5>';
                                echo '</div>';
                                echo '<div class="col-lg-2" >';
                                echo $rec["SubjectName"];
                                echo '</div>';
                                echo '</div>';
                            }else{
//                                echo "Hidden";
                            }
                        }
                    }

                    ?>


                </div>
                <?php
                }
                ?>



            </div>



        </div>

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
