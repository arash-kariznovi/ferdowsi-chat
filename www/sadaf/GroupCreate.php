<?php

require_once "header.inc.php";
require_once "classes/Group.class.php";
require_once "classes/Profile.class.php";

if (isset($_REQUEST['group_create'])) {
    $group_name = $_REQUEST['group_name'];
    $description = $_REQUEST['description'];
    $image = "";
    if(isset($_FILES["picture"]) && $_FILES["picture"]["name"] != ""){
        if($_FILES['picture']['error']!= "0"){
            echo "Error: ".$_FILES['picture']['error'];
            die();
        }
        $image = file_get_contents($_FILES["picture"]["tmp_name"]);
    }
    $new_group = ManageGroup::create($group_name, $description, $image);
    header("Location: GroupProfile.php?group_id=$new_group");
    die();
}if(isset($_REQUEST["group_auto_create"])){
    ManageGroup::autoLessonGroupCreate();
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
    <link rel="stylesheet" type="text/css" href="css/CreateGroup.css">
</head>

<body>
    <main>
        <!-- main container -->
        <div class="container-fluid">
            <!-- create group -->
            <div class="container" id="create">
                <form method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-lg-2"></div>
                        <div class="col-lg-8" id="main-container">
                            <h2 class="text-center">ایجاد گروه</h2>
                            <h4 class="text-right">نام گروه:</h4>
                            <input type="text" placeholder="نام گروه" name="group_name" id="group_name" required>
                            <h4 class="text-right">توضیحات:</h4>
                            <input type="text" id="description" name="description" >
                            <h4 class="text-right">تصویر</h4>
                            <input type="file" accept=".png,.jpg,.jpeg,.gif" placeholder="picture" name="picture"
                                id="picture">
                            <br>
                            <img id="output" alt="Avatar" class="avatar">
                            <br>
                            <input type="submit" id="group_create" name="group_create" class="btn" value="ایجاد">
                        </div>
                        <div class="col-lg-2"></div>
                    </div>
                </form>
            </div>
            <!-- auto create groups -->
            <div class="container" id="auto_create">
                <form>
                    <div class="row">
                        <div class="col-lg-2"></div>
                        <div class="col-lg-8" id="main-container">
                            <h2 class="text-center">ایجاد خودکار گروه</h2>
                            <br>
                            <input type="submit" id="group_auto_create" name="group_auto_create" class="btn" value="ایجاد">
                        </div>
                        <div class="col-lg-2"></div>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        document.getElementById('picture').onchange = function loadFile(event) {
            var image = document.getElementById('output');
            image.src = URL.createObjectURL(event.target.files[0]);
        };


        document.getElementById('edit-file').onchange = function editFile(event) {
            var image = document.getElementById('edit-pic');
            image.src = URL.createObjectURL(event.target.files[0]);
        };
    </script>
</body>

</html>