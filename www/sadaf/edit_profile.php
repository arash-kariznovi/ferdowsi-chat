<?php
include "header.inc.php";
HTMLBegin();

$mysql = pdodb::getInstance();
session_start();
if(isset($_REQUEST["save"])){
    $file_content="";
        if (isset($_FILES["avatar"]) && $_FILES["avatar"]["name"]!=""){

            $file_content = file_get_contents($_FILES["avatar"]["tmp_name"]);
            $fileName =$_FILES["avatar"]["name"];
            move_uploaded_file($_FILES["avatar"]["tmp_name"],"c:/profile-images/".$_SESSION["PersonID"]."_".$fileName);
        }
    $imageName = $_SESSION["PersonID"]."_".$_FILES["avatar"]["name"];
    $query = "UPDATE privacy set bio = (?), website =(?),name=(?),image=(?),FacultyName=(?),
        SubjectName=(?),PhoneNumber=(?),Birth=(?) where Person_ID=".$_SESSION["PersonID"];
    $mysql->Prepare($query);
    $mysql->ExecuteStatement(array($_REQUEST["bio"],$_REQUEST["website"],$_REQUEST["name"],$file_content,
        $_REQUEST["FacultyName"],$_REQUEST["SubjectName"],$_REQUEST["PhoneNumber"],$_REQUEST["Birth"]));
}
?>
    <div class="wrapper" dir="rtl">

        <!-- Page Content  -->
        <form method="POST"  enctype="multipart/form-data">

            <div id="content">
                <div class="col" style="text-align: right; padding-top: 10px;">
                    <input type="submit" id="save" name="save" class="btn btn-primary" value="ذخیره" />
                    <a href="my_profile.php">
                            <input type="button" class="btn btn-danger " value="انصراف">
                    </a>
                </div>



                <?php
                $mysql = pdodb::getInstance();

                $query = "select * from privacy where Person_ID=".$_SESSION["PersonID"];
                $identity = $mysql->Execute($query);
                while ($rec = $identity->fetch()){
                ?>

                    <div class="row" style="text-align: center;">
                        <div class="col-lg-12">
                         <?php
                         echo   '<img src="data:image/jpeg;base64,' . base64_encode($rec["image"]) . '" class="rounded-circle" alt="Cinque Terre" width="300" height="300">';
                         ?>
                        </div>
                        <div class="col-lg-12" style="margin: 30px;">
                            <input id="avatar" name="avatar"  accept="image/png, image/jpeg" type="file"
                                   onchange="
                        readURL(this);
" />
                        </div>
                    </div>
            <div class="form-group">
                <label for="usr">نام : </label>
                <input value="<?php echo $rec["Name"] ?> " type="text" class="form-control" name="name" id="name" style="max-width: 80%">
            </div>
                    <div class="form-group">
                        <label for="usr">شماره تماس : </label>
                        <input value="<?php echo $rec["PhoneNumber"] ?>" type="text" class="form-control" name="PhoneNumber" id="PhoneNumber" style="max-width: 80%">
                    </div>
            <div class="form-group">
                <label for="usr">بیوگرافی : </label>
                <input value="<?php echo $rec["bio"] ?>" type="text" class="form-control" name="bio" id="bio" style="max-width: 80%">
            </div>
                    <div class="form-group">
                        <label for="usr">تاریخ تولد : </label>
                        <input value="<?php echo strftime('%Y-%m-%d', strtotime($rec['Birth'])); ?>" type="date" class="form-control" name="Birth" id="Birth" style="max-width: 80%">
                    </div>
            <div class="form-group">
                <label for="usr">آدرس وبسایت : </label>
                <input value="<?php echo $rec["website"] ?> " type="text" class="form-control" name="website" id="website" style="max-width: 80%">
            </div>
                    <div class="form-group">
                        <label for="usr">رشته تحصیلی : </label>
                        <input value="<?php echo $rec["SubjectName"] ?>" type="text" class="form-control" name="SubjectName" id="SubjectName" style="max-width: 80%">
                    </div>
                    <div class="form-group">
                        <label for="usr">دانشکده : </label>
                        <input value="<?php echo $rec["FacultyName"] ?>" type="text" class="form-control" name="FacultyName" id="FacultyName" style="max-width: 80%">
                    </div>
                <?php
                }
                ?>



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
            function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        $('#blah').attr('src', e.target.result).width(150).height(200);
                    };

                    reader.readAsDataURL(input.files[0]);
                }
            }

        </script>
<?php
HTMLEnd();
?>