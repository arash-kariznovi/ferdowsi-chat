
<?php
include "header.inc.php";
HTMLBegin();
$n=1;
if (isset($_REQUEST["add"])) {
    $allowed = array('gif', 'png', 'jpg', 'jpeg');
    $filename = $_FILES["AttachFile"]["name"];
    $tempname = $_FILES["AttachFile"]["tmp_name"];
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    if (!in_array($ext, $allowed)) {
        echo '<div>(gif, png, jpg, jpeg).آپلود عکس فقط از این فرمت ها پشتیبانی میکند</div>';
    }else {
        $folder = "./images/".$filename;
        $mysql = pdodb::getInstance();
        $query ="insert into sadaf.books (BookName,Authors,Translaters,ISBN,ImageName,ImageData)
                    values (?,?,?,?,?,?)";
        $mysql ->Prepare($query);
        $mysql -> ExecuteStatement(array($_REQUEST["title"],$_REQUEST["author"],$_REQUEST["translater"],$_REQUEST["ISBN"],$filename,$tempname));

        if (move_uploaded_file($tempname, $folder))  {
            $msg = "Image uploaded successfully";
        }else{
            $msg = "Failed to upload image";
        }
    }
}
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/add-styles.css">
    <title>اضافه کردن کتاب جدید</title>
</head>
<body>
<form  method="post" enctype="multipart/form-data">
<div class="add-book-container" id="add-book-container">
    <div class="form">
        <label>
            <p>
                عکس کتاب:
            </p>
            <input name="AttachFile" class="add-input" type="file">
        </label>
        <label>
            <p>
                نام کتاب:
            </p>
            <input name="title" id="title" maxlength="45" class="add-input" required>
        </label>
        <label>
            <p>
                نویسنده:
            </p>
            <input name="author" id="author" maxlength="45" class="add-input" required>
        </label>
        <label>
            <p>
                مترجم:
            </p>
            <input name="translater" id="translater" maxlength="45" class="add-input" >
        </label>
        <label>
            <p>
                ISBN:
            </p>
            <input  name="ISBN" id="ISBN" minlength="11" maxlength="13" pattern="[0-9]*"  class="add-input" required>
        </label>
        <input name="add" id="add" type="submit" value="اضافه کردن" />
    </div>
</div>
    </form>
</body>
</html>