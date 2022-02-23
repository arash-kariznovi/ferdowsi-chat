<?php
    include "header.inc.php";

    HTMLBegin();
    function setOptions($selectedValue){
        $selectedKey = '';
        $isSelected = "selected";
        $key1 = '';
        $key2 = '';
        $value1 = '';
        $value2 = '';
        if(!empty($selectedValue)){
            if($selectedValue == 2){
                $selectedKey = "هیچ کس";
                $value1 = 1;
                $key1 = "فقط دوستانم";
                $value2 = 0;
                $key2 = "همه";
            }else if($selectedValue == 1){
                $selectedKey = "فقط دوستانم";
                $value1 = 2;
                $key1 = "هیچ کس";
                $value2 = 0;
                $key2 = "همه";
            }else{//0 by default
                $selectedKey = "همه";
                $value1 = 1;
                $key1 = "فقط دوستانم";
                $value2 = 2;
                $key2 = "هیچ کس";
            }
        }
        if($key1 == '' && $key2 == '' && $value1 == '' && $value2 == '' && $selectedKey == ''){
            echo '<option value="0">همه</option>';
            echo '<option value="1">فقط دوستانم</option>';
            echo '<option value="2">هیچ کس</option>';
        }else{
            echo '<option value="'.$selectedValue.'"'.$isSelected.'>'.$selectedKey.'</option>';
            echo '<option value="'.$value1.'">'.$key1.'</option>';
            echo '<option value="'.$value2.'">'.$key2.'</option>';
        }
    }

    if (isset($_REQUEST['save'])) {
        $selectedName = $_REQUEST['name'];
        $selectedNumber = $_REQUEST['number'];
        $selectedBirthdate = $_REQUEST['birthdate'];
        $selectedFaculty = $_REQUEST['faculty'];
        $selectedPicture = $_REQUEST['picture'];
        $selectedField = $_REQUEST['field'];
        $selectedGroup = $_REQUEST['group'];
        $mysql= pdodb::getInstance();
        session_start();
        $query= "UPDATE sadaf.privacy SET NameShow= ? , PhoneNo= ? ,BirthDate= ? , PictureShow= ? ,Faculty= ?, 
                         GroupPermission= ? , Subject= ? WHERE Person_ID= ? ;";
        $mysql->Prepare($query);
        $mysql->ExecuteStatement(array($selectedName, $selectedNumber, $selectedBirthdate, $selectedPicture,
            $selectedFaculty, $selectedGroup, $selectedField, $_SESSION["PersonID"]));
    }

    $mysql= pdodb::getInstance();
    session_start();
    $selectQuery="SELECT NameShow , PhoneNo , BirthDate, PictureShow, Faculty , GroupPermission , Subject
            FROM sadaf.privacy WHERE Person_ID= ? ;";
    $mysql->Prepare($selectQuery);
    $res = $mysql->ExecuteStatement(array($_SESSION["PersonID"]));
    while ($rec = $res->fetch()){
    ?>

<!DOCTYPE html>
<html>

<div class="wrapper">
    <!-- Page Content  -->
    <div id="content">
        <br>
        <div class="row">
            <h1 class="col-lg-10">حریم شخصی</h1>
        </div>
        <br>
        <br>
        <form dir="rtl">
            <div class="row">
                <div class="col-lg-4" style="margin-right: 5%">
                    نمایش نام من به
                </div>
                <br>
                <div class="col-lg-4">
                    <div class="form-group">
                        <select class="form-control" id="name" name="name" >
                            <?php
                                setOptions($rec["NameShow"]);
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-lg-4" style="margin-right: 5%">
                    نمایش شماره ی من به
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <select class="form-control" id="number" name="number">
                            <?php
                            setOptions($rec["PhoneNo"]);
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-lg-4" style="margin-right: 5%">
                    نمایش تاریخ تولد من به
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <select class="form-control" id="birthdate" name="birthdate">
                            <?php
                            setOptions($rec["BirthDate"]);
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-lg-4" style="margin-right: 5%">
                    نمایش عکس پروفایل من به
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <select class="form-control" id="picture" name="picture">
                            <?php
                            setOptions($rec["PictureShow"]);
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-lg-4" style="margin-right: 5%">
                    نمایش نام دانشکده ی من به
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <select class="form-control" id="faculty" name="faculty">
                            <?php
                            setOptions($rec["Faculty"]);
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-lg-4" style="margin-right: 5%">
                    نمایش رشته ی من به
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <select class="form-control" id="field" name="field">
                            <?php
                            setOptions($rec["Subject"]);
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-lg-4" style="margin-right: 5%">
                    مجوز اضافه شدن به گروه توسط
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <select class="form-control" id="group" name="group">
                            <?php
                            setOptions($rec["GroupPermission"]);
                            }
                            ?>
                        </select>
                    </div>
                </div>

            </div>
            <div class="row" style="margin-right: 5%">
                <div class="col-sm-7">
                    <button class="btn btn-danger "> <a href="./blocked.php" class="col-lg-10">کاربران مسدود شده</a></button>
                </div>
                <div class="col-sm-3">
                    <input type="submit" class="btn btn-success" name="save" id="save" value="ذخیره">
                </div>
            </div>
        </form>

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