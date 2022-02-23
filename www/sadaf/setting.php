<?php
include("header.inc.php");
include("settings.class.php");

HTMLBegin();


if (isset($_POST['submit'])) {
    if (!empty($_POST['theme_chooser'])) {
        $selected = $_POST['theme_chooser'];
        $mysql = pdodb::getInstance();
        $mysql->Prepare("UPDATE sadaf.person_theme t SET t.theme_id = ? WHERE t.person_id = ?");
        $res1 = $mysql->ExecuteStatement(array($selected ,$_SESSION["PersonID"]));
        if ($trec1 = $res1->fetch()) {

        } else {
            SharedClass::loadAndPerformTheme($mysql, $_SESSION["PersonID"]);
        }

    }
    if(!empty($_POST['status_chooser'])) {
        $selectedStatus = $_POST['status_chooser'];
        $mysql = pdodb::getInstance();
        $mysql->Prepare("UPDATE sadaf.person_theme t SET t.status = ? WHERE t.person_id = ?");
        $res1 = $mysql->ExecuteStatement(array($selectedStatus ,$_SESSION["PersonID"]));
        if ($trec1 = $res1->fetch()) {

        } else {
            SharedClass::loadAndPerformTheme($mysql, $_SESSION["PersonID"]);
        }
    }
}


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/bootstrap.css">
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/lab.css" type="text/css">
</head>

<body>


    <div class="setting-container">
        <form action="" method="post" class="mb-3 mx-auto text-center">
            <br>

            <table border="0" class="mt-3 mx-auto">
                <tr class="pt-3">
                    <td>
                        قالب پس زمینه
                    </td>
                    <td>
                        <select class="form-control sadaf-m-input" dir="ltr" name="theme_chooser" id="theme_chooser">
                            <option value=0>-
                                <? echo SharedClass::CreateSettingsThemeSelectOptions("sadaf.theme", "theme_id", "name", "name"); ?>
                        </select>
                    </td>
                </tr>

                <tr class="pt-3">
                    <td>
                        وضعیت کاربر
                    </td>
                    <td>

                        <select class="form-control sadaf-m-input" dir="ltr" name="status_chooser" id="status_chooser">
                            <option value=0>-
                            <option value="online">آنلاین</option>
                            <option value="away">آفلاین</option>
                            <option value="busy">مشغول</option>
                        </select>
                    </td>
                </tr>

            </table>

            <div select class="sadaf-m-input mx-auto mt-4">
                <button type="submit" name="submit" class="btn btn-primary" onclick="buttonHandler()">
                    اعمال
                </button>
            </div>
        </form>
    </div>



    <script>
        function buttonHandler() {

            let theme = document.getElementById("theme_chooser").value;

            <? $mysql->Prepare("
			// SELECT * FROM theme where theme_id=?");
                // $res1 = $mysql->ExecuteStatement(array(cc));

                // if ($trec1 = $res1->fetch()) {
                //     $_SESSION["font"] = $trec1["font"];
                //     $_SESSION["font_size"] = $trec1["font_size"];
                //     $_SESSION["color1"] = $trec1["color1"];
                //     $_SESSION["color2"] = $trec1["color2"];
                //     $_SESSION["color3"] = $trec1["color3"];
                // } else {
                //     // fill...
                // } 
                ?>

        }
    </script>


</body>

</html>