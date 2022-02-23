<?php
    include "header.inc.php";
    HTMLBegin();
?>
<!DOCTYPE html>
<html>
    <div class="wrapper">
        <!-- Page Content  -->
        <div class="col" style="text-align: right; padding-top: 10px; margin-right: 5%">
            <a href="edit_profile.php"><button type="button" class="btn btn-primary">ویرایش</button></a>
        </div>
        <div id="content">
            <div class="row" style="text-align: center;">
                <?php
                $mysql = pdodb::getInstance();
                session_start();
                $query = "select * from sadaf.privacy where Person_ID=".$_SESSION["PersonID"];
                $identity = $mysql->Execute($query);
                while ($rec = $identity->fetch()){
                ?>
                    <div class="col-lg-12">
                        <?php
                       echo '<img src="data:image/jpeg;base64,' . base64_encode($rec["image"]) . '" class="rounded-circle" alt="Cinque Terre" width="300" height="300">';
                         ?>
                    </div>
                  <div class="col-lg-12" style="margin-top: 30px;">
                     <div class="col"><b style="font-size: 28px;">
                             <?php
                                echo $rec["name"];
                             ?>
                        </b></div>
                </div>
                    <div class="col-lg-12" style="margin-top: 5px;">
                        <?php
                        echo $rec["PhoneNumber"];
                        ?>
                    </div>
                <div class="col-lg-12" style="margin-top: 15px;">
                    <?php
                    echo $rec["bio"]
                    ?>
                </div>
                    <div class="col-lg-12" style="margin-top: 15px;">
                        <?php
                        echo substr($rec["Birth"],0,10);
                        ?>
                    </div>

                    <div class="col-lg-12" style="margin-top: 5px;">
                        <?php
                        echo $rec["website"];
                        ?>
                    </div>

                    <div class="col-lg-12" style="margin-top: 5px;">
                        <?php
                        echo $rec["SubjectName"];
                        ?>
                    </div>
                    <div class="col-lg-12" style="margin-top: 5px;">
                        <?php
                        echo $rec["FacultyName"];
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
