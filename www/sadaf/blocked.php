<?php
include "header.inc.php";
HTMLBegin();
?>
<!DOCTYPE html>
<html>
    <div class="wrapper" dir="rtl">

        <!-- Page Content  -->
        <div id="content">

            <div class="container">
                <div class="row">
                    <div class="col-md-4">
                        <h1>کاربران مسدود شده</h1>
                    </div>
                    <div class="col-md-6"></div>
                    <div class="col-md-2"> <button class="btn btn-primary"><a href="users.php">افزودن</a></button></div>
                    <div class="col-md-12">

                        <form>

                            <div class="list-group">

                                <?php
                                $mysql = pdodb::getInstance();
                                session_start();
                                $query = "select * from block where blockerID=".$_SESSION["PersonID"];
                                $res = $mysql->Execute($query);
                                while ($rec = $res->fetch()){
                                    ?>
                                    <li class="list-group-item d-flex">
                                        <div class="col-sm-8">
                                            <p class="p-0 m-0 flex-grow-1"><?php echo "شماره دانشجویی: ".$rec["blockedID"]?></p>
                                        </div>
                                        <div class="col-sm-3">
                                            <a class="btn btn-danger" href="unblock.php?id=<?php echo $rec["blockedID"]?>">آنبلاک</a>
                                        </div>
                                    </li>
                                    <?php
                                }
                                ?>
                            </div>
                        </form>
                    </div>
                </div>
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