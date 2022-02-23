<?php
include("header.inc.php");
include("./classes/Skills.class.php");
include("./classes/Projects.class.php");
include("./classes/manage_pubs.php");
include("./classes/Interests.class.php");

HTMLBegin();


$PersonID = $_SESSION["PersonID"];
$skillsList = manage_skills::GetList(0, 10);
$personSkillsList = manage_skills::GetPersonSkills($PersonID);

$projectsList = manage_projects::GetList(0, 10);
$personProjectsList = manage_projects::GetPersonProjects($PersonID);

$personPubList = manage_pubs::getPersonPubs($PersonID);

$InterestsList = manage_Interests::GetList(0, 10);
$personInterestsList = manage_Interests::GetPersonInterests($PersonID);
$recommendedPersons = manage_Interests::GetRecommendedPersons($PersonID);


if ($_SESSION["Message"]) {
    echo SharedClass::CreateMessageBox($_SESSION["Message"][0], $_SESSION["Message"][1]);
    $_SESSION["Message"] = null;
}
if (isset($_REQUEST["Add"])) {
    if ($_REQUEST["SkillID"] != 0) {
        $SkillID = $_REQUEST["SkillID"];
        if (!manage_skills::PersonHasSkill($PersonID, $SkillID)) {
            manage_skills::AddToPerson($SkillID, $PersonID);
            $_SESSION["Message"] = ["مهارت انتخابی، به پروفایل شما افزوده شد.", "green"];
        } else {
            $_SESSION["Message"] = ["مهارت انتخابی، تکراری است.", "red"];
        }
    } else {
        $SkillName = $_REQUEST["SkillName"];
        if (is_null(manage_skills::getByName($SkillName))) {
            $SkillID = manage_skills::Add($SkillName);
            manage_skills::AddToPerson($SkillID, $PersonID);
            $_SESSION["Message"] = ["مهارت جدید به پایگاه داده افزوده و در پروفایل شما ثبت شد.", "green"];
        } else {
            $_SESSION["Message"] = ["مهارتی با نام مشابه وجود دارد.", "red"];
        }
    }
    echo "<meta http-equiv='refresh' content='0'>";
} elseif (isset($_REQUEST["Remove"])) {
    $SkillID = $_REQUEST["SkillID"];
    if (manage_skills::PersonHasSkill($PersonID, $SkillID)) {
        manage_skills::RemoveFromPerson($SkillID, $PersonID);
        $_SESSION["Message"] = ["مهارت انتخابی، از پروفایل شما حذف شد.", "green"];
    } else {
        $_SESSION["Message"] = ["مهارت انتخابی را دارا نمی باشید.", "red"];
    }
    echo "<meta http-equiv='refresh' content='0'>";
} elseif (isset($_REQUEST["AddInterest"])) {
    if ($_REQUEST["InterestID"] != 0) {
        $InterestID = $_REQUEST["InterestID"];
        if (!manage_Interests::PersonHasInterest($PersonID, $InterestID)) {
            manage_Interests::AddToPerson($InterestID, $PersonID);
            $_SESSION["Message"] = ["علاقمندی انتخابی، به پروفایل شما افزوده شد.", "green"];
            echo "<meta http-equiv='refresh' content='0'>";
        } else {
            $_SESSION["Message"] = ["علاقمندی انتخابی، تکراری است.", "red"];
            echo "<meta http-equiv='refresh' content='0'>";
        }
    } else {
        $InterestName = $_REQUEST["InterestName"];

        if (is_null(manage_Interests::getByName($InterestName))) {
            $InterestID = manage_Interests::Add($InterestName);
            manage_Interests::AddToPerson($InterestID, $PersonID);
            echo SharedClass::CreateMessageBox("علاقمندی جدید به پایگاه داده افزوده و در پروفایل شما ثبت شد.");
            echo "<meta http-equiv='refresh' content='0'>";
        } else if (!(is_null(manage_Interests::getByName($InterestName)))) {
            echo SharedClass::CreateMessageBox("علاقمندی با نام مشابه وجود دارد.", "red");
        }
        echo "<meta http-equiv='refresh' content='0'>";


    }
} elseif (isset($_REQUEST["RemoveInterest"])) {
    $InterestID = $_REQUEST["InterestID"];
    if (manage_Interests::PersonHasInterest($PersonID, $InterestID)) {
        manage_Interests::RemoveFromPerson($InterestID, $PersonID);
        $_SESSION["Message"] = ["علاقمندی انتخابی، از پروفایل شما حذف شد.", "green"];
    } else {
        $_SESSION["Message"] = ["علاقمندی انتخابی را دارا نمی باشید.", "red"];
    }
    echo "<meta http-equiv='refresh' content='0'>";

} elseif (isset($_REQUEST["AddProject"])) {
    if ($_REQUEST["ProjectID"] != 0) {
        $ProjectID = $_REQUEST["ProjectID"];
        if (!manage_Projects::PersonHasProject($PersonID, $ProjectID)) {
            manage_Projects::AddToPerson($ProjectID, $PersonID);
            $_SESSION["Message"] = ["پروژه انتخابی، به پروفایل شما افزوده شد.", "green"];
        } else {
            $_SESSION["Message"] = ["پروژه انتخابی، تکراری است.", "red"];
        }
    } else {
        $ProjectName = $_REQUEST["ProjectName"];
        // if($_REQUEST["StartDate"] != NULL)
        //     $StartDate = $_REQUEST["StartDate"];
        // else
        //     $StartDate = " ";
        $StartDate = $_REQUEST["StartDate"];
        $EndDate = $_REQUEST["EndDate"];
        $Description = $_REQUEST["Description"];
        $Link = $_REQUEST["Link"];

        if (is_null(manage_Projects::getByName($ProjectName))) {
            $ProjectID = manage_Projects::Add($ProjectName, $StartDate, $EndDate, $Description, $Link);
            manage_Projects::AddToPerson($ProjectID, $PersonID);
            echo SharedClass::CreateMessageBox("پروژه جدید به پایگاه داده افزوده و در پروفایل شما ثبت شد.");
        } else if (!(is_null(manage_Projects::getByName($ProjectName)))) {
            echo SharedClass::CreateMessageBox("پروژه با نام مشابه وجود دارد.", "red");
        }
        echo "<meta http-equiv='refresh' content='0'>";


    }
} elseif (isset($_REQUEST["RemoveProject"])) {
    $ProjectID = $_REQUEST["ProjectID"];
    if (manage_Projects::PersonHasProject($PersonID, $ProjectID)) {
        manage_Projects::RemoveFromPerson($ProjectID, $PersonID);
        $_SESSION["Message"] = ["پروژه انتخابی، از پروفایل شما حذف شد.", "green"];
    } else {
        $_SESSION["Message"] = ["پروژه انتخابی را دارا نمی باشید.", "red"];
    }
    echo "<meta http-equiv='refresh' content='0'>";

}


?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>مدیریت پروفایل مهارتی</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="./pages/css/manage-skills.css">
    <link rel="stylesheet" href="./pages/css/manage_skills_profiles.scss">
    
</head>
<body style="margin-top: 30px;">
<div class="container">
    <div class="jumbotron" style="padding: 15px">
        <h1>مهارت های فردی</h1>
        <p class="subtitle">در این بخش می توانید پروفایل مهارتی و پژوهشی خود را مشاهده و ویرایش کنید</p>
    </div>

    <!--مهارت ها -->
    <div class="component">
        <h2 class="box-title">مهارت ها</h2>
        <div class="box">
            <?php
            for ($i = 0; $i < count($personSkillsList); $i++) {
                echo "<span onclick=\"removeSkill(this)\" value=\"{$personSkillsList[$i]->SkillID}\" class=\"badge\">{$personSkillsList[$i]->SkillName}</span>";
            }
            ?>
        </div>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#skillModalCenter">افزودن مهارت
        </button>
        <div class="modal fade" id="skillModalCenter" tabindex="-1" role="dialog"
             aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">افزودن مهارت</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="post" name="addSkillForm" id="addSkillForm" class="form-inline">
                            <div class="form-group">
                                <label>مهارت</label>
                                <input type="hidden" name="Add" id="Add" value="1">
                                <input type="hidden" name="SkillID" value="0">
                                <input type="text" required name="SkillName" class="form-control mx-sm-1"
                                       placeholder="مثلا، حل مساله" dir="rtl">
                            </div>
                            <div class="error"></div>
                        </form>
                        <hr/>
                        <?php
                        for ($i = 0; $i < count($skillsList); $i++) {
                            echo "<button type=\"button\" value=\"{$skillsList[$i]->SkillID}\" onclick=\"addSkill(this)\" class=\"btn btn-outline-primary item-btn\">{$skillsList[$i]->SkillName}</button>";
                        }
                        ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                        <button type="button" class="btn btn-primary" onclick="submitAddSkillForm()">اضافه کردن</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--پروژه ها -->
    <div class="component">
        <h2 class="box-title">پروژه ها</h2>
        <div class="box">
            <?php
            for ($i = 0; $i < count($personProjectsList); $i++) {
                echo "<span onclick=\"removeProject(this)\" value=\"{$personProjectsList[$i]->ProjectID}\" class=\"badge\">
                {$personProjectsList[$i]->Link},
                {$personProjectsList[$i]->Description},
                {$personProjectsList[$i]->EndDate},
                {$personProjectsList[$i]->StartDate},
                {$personProjectsList[$i]->ProjectName}
                </span><br>";
            }
            ?>
        </div>

        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#projectModalCenter">افزودن پروژه
        </button>
        <div class="modal fade" id="projectModalCenter" tabindex="-1" role="dialog"
             aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">افزودن پروژه</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="post" name="addProjectForm" id="addProjectForm" class="form-inline">
                            <div class="form-group">
                                <label>پروژه</label>
                                <input type="hidden" name="AddProject" id="AddProject" value="1">
                                <input type="hidden" name="ProjectID" id="ProjectID" value="0">
                                <input type="text" name="ProjectName" id="ProjectName" class="form-control mx-sm-1"
                                       placeholder="مثلا، پروژه شبکه اجتماعی" dir="rtl">
                            </div>
                            <div class="form-group">
                                <label>تاریخ شروع</label>
                                <input type="hidden" name="AddProject" id="AddProject" value="1">
                                <input type="hidden" name="ProjectID" id="ProjectID" value="0">
                                <input type="date" name="StartDate" id="StartDate" class="form-control mx-sm-1">
                            </div>
                            <div class="form-group">
                                <label>تاریخ پایان</label>
                                <input type="hidden" name="AddProject" id="AddProject" value="1">
                                <input type="hidden" name="ProjectID" id="ProjectID" value="0">
                                <input type="date" name="EndDate" id="EndDate" class="form-control mx-sm-1">
                            </div>
                            <div class="form-group">
                                <label>شرح پروژه</label>
                                <input type="hidden" name="AddProject" id="AddProject" value="1">
                                <input type="hidden" name="ProjectID" id="ProjectID" value="0">
                                <textarea name="Description" id="Description" class="form-control mx-sm-1" rows="4" dir="rtl"></textarea>
                            </div>
                            <div class="form-group">
                                <label>لینک</label>
                                <input type="hidden" name="AddProject" id="AddProject" value="1">
                                <input type="hidden" name="ProjectID" id="ProjectID" value="0">
                                <input type="basic-url" name="Link" id="Link" class="form-control mx-sm-1">
                            </div>
                            <div class="form-group">
                                <label>همکاران</label>
                                <input type="hidden" name="AddProject" id="AddProject" value="1">
                                <input type="hidden" name="ProjectID" id="ProjectID" value="0">
                                <input type="text" name="Colleagues" id="Colleagues" class="form-control mx-sm-1">
                            </div>
                        </form>
                        <hr/>
                        <?php
                        for ($i = 0; $i < count($projectsList); $i++) {
                            echo "<button type=\"button\" value=\"{$projectsList[$i]->ProjectID}\" onclick=\"addProject(this)\" class=\"btn btn-outline-primary item-btn\">{$projectsList[$i]->ProjectName}</button>";
                        }
                        ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                        <button type="button" class="btn btn-primary" onclick="submitAddProjectForm()">اضافه کردن
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!--PUBLICATIONS-->
    <div class="component">
        <h2 class="box-title">انتشارات</h2>
        <div class="box">
            <?php
            for ($i = 0; $i < count($personPubList); $i++) {
                echo "<div class=\"record\" value=\"{$personPubList[$i]->pubID}\"><strong>{$personPubList[$i]->pubName}</strong></div>";
            }
            ?>

        </div>
    </div>


    <!--PUBLICATIONS ENDS-->


    <!--علاقمندی ها -->
    <div class="component">
        <h2 class="box-title">علاقمندی ها</h2>
        <div class="box">
            <?php
            for ($i = 0; $i < count($personInterestsList); $i++) {
                echo "<span onclick=\"removeInterest(this)\" value=\"{$personInterestsList[$i]->InterestID}\" class=\"badge\">{$personInterestsList[$i]->InterestName}</span>";
            }
            ?>
        </div>

        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#interestModalCenter">افزودن
            علاقمندی
        </button>
        <div class="modal fade" id="interestModalCenter" tabindex="-1" role="dialog"
             aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">افزودن علاقمندی</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="post" name="addInterestForm" id="addInterestForm" class="form-inline">
                            <div class="form-group">
                                <label>علاقمندی</label>
                                <input type="hidden" name="AddInterest" id="AddInterest" value="1">
                                <input type="hidden" name="InterestID" id="InterestID" value="0">
                                <input type="text" name="InterestName" id="InterestName" class="form-control mx-sm-1"
                                       placeholder="مثلا، مطالعه" dir="rtl">
                            </div>
                        </form>
                        <hr/>
                        <?
                        for ($i = 0; $i < count($InterestsList); $i++) {
                            echo "<button type=\"button\" value=\"{$InterestsList[$i]->InterestID}\" onclick=\"addInterest(this)\" class=\"btn btn-outline-primary item-btn\">{$InterestsList[$i]->InterestName}</button>";
                        }
                        ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                        <button type="button" class="btn btn-primary" onclick="submitAddInterestForm()">اضافه کردن
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!--    بخش پیشنهادات-->
    <div class="component">
        <h2 class="box-title">پیشنهاد افراد</h2>

        <div class="profile-cards" id="profiles">
            <div class="row justify-content-end align-items-center gx-2">

                <?php
                for ($i = 0; $i < count($recommendedPersons); $i++) {
                    $first_name = $recommendedPersons[$i]->pfname;
                    $last_name = $recommendedPersons[$i]->plname;

                    $elmt = sprintf('<div class="col-3 justify-content-end align-items-center gx-2">
                                    <div class="profile-card">
                                        <div class="profile-card__content">
                                            <div class="profile-card__image">
                                                <img src="https://upload.wikimedia.org/wikipedia/commons/9/99/Sample_User_Icon.png" style="width:60px;">
                                            </div>
                                            <h2 class="profile-card__name">%s %s</h2>
                                            <p class="profile-card__handle"></p>
                                        </div>
                                        <button class="profile-card__button">پیشنهاد میشود</button>
                                    </div>
                            </div>', $first_name, $last_name);
                    echo $elmt;
                    if ($i > 9) break;
                }
                ?>

            </div>
        </div>
    </div>

    <!--    پایان بخش پیشنهادات-->


    <!-- Hidden Form: Remove Skill Form -->
    <form method="post" name="removeSkillForm" id="removeSkillForm" class="form-inline" style="display: none">
        <div class="form-group">
            <input type="hidden" name="Remove" value="1">
            <input type="hidden" name="SkillID" value="0">
        </div>
    </form>
    <!-- End -->

    <!-- Hidden Form: Remove Project Form -->
    <form method="post" name="removeProjectForm" id="removeProjectForm" class="form-inline" style="display: none">
        <div class="form-group">
            <input type="hidden" name="RemoveProject" value="1">
            <input type="hidden" name="ProjectID" value="0">
        </div>
    </form>
    <!-- End -->

    <!-- Hidden Form: Remove Interest Form -->
    <form method="post" name="removeInterestForm" id="removeInterestForm" class="form-inline" style="display: none">
        <div class="form-group">
            <input type="hidden" name="RemoveInterest" value="1">
            <input type="hidden" name="InterestID" value="0">
        </div>
    </form>
    <!-- End -->

    <script>
        function addSkill(element) {
            let skillId = element.value;
            document.querySelector("#addSkillForm input[name='SkillID']").value = skillId;
            submitAddSkillForm(false);
        }

        function removeSkill(element) {
            let skillId = element.getAttribute('value');
            document.querySelector("#removeSkillForm input[name='SkillID']").value = skillId;
            document.getElementById('removeSkillForm').submit();
        }

        function submitAddSkillForm(validate) {
            const form = document.getElementById('addSkillForm');
            if (validate === false || form.checkValidity()) {
                document.querySelector(".modal-content div.error").innerHTML = "";
                form.submit();
            } else {
                document.querySelector("#addSkillForm input[name='SkillName']").classList.add('error');
                document.querySelector(".modal-content div.error").innerHTML = "برای ساخت مهارت جدید، وارد کردن نام ضروری است.";
            }
        }

        function addProject(element) {
            let projectd = element.value;
            document.querySelector("#addProjectForm input[name='ProjectID']").value = projectId;
            submitAddProjectForm(false);
        }

        function removeProject(element) {
            let projectId = element.getAttribute('value');
            document.querySelector("#removeProjectForm input[name='ProjectID']").value = projectId;
            document.getElementById('removeProjectForm').submit();
        }

        function submitAddProjectForm(validate) {
            const form = document.getElementById('addProjectForm');
            if (validate === false || form.checkValidity()) {
                document.querySelector(".modal-content div.error").innerHTML = "";
                form.submit();
            } else {
                document.querySelector("#addProjectForm input[name='ProjectName']").classList.add('error');
                document.querySelector(".modal-content div.error").innerHTML = "برای ساخت پروژه جدید، وارد کردن نام ضروری است.";
            }
        }

        function addInterest(element) {
            let interestId = element.value;
            document.getElementById('InterestID').value = interestId;
            submitAddInterestForm();
        }

        function removeInterest(element) {
            let InterestId = element.getAttribute('value');
            document.querySelector("#removeInterestForm input[name='InterestID']").value = InterestId;
            document.getElementById('removeInterestForm').submit();
        }

        function submitAddInterestForm() {
            document.getElementById('addInterestForm').submit();
        }

    </script>

    <script src="./pages/scripts/manage_skills_profiles.js"></script>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
            integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
            crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
            integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
            crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
            integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
            crossorigin="anonymous"></script>
</div>
</body>
</html>
