<?php
include "header.inc.php";
HTMLBegin();
$mysql = pdodb::getInstance();
session_start();
ini_set("error_reporting",E_ALL);
?>

<!DOCTYPE html>
<html>

    <div class="wrapper" dir="rtl">

        <!-- Page Content  -->
        <div id="content">
            <div class="container">
                <div class="row">
                    <div class="col-md-2">
                        <br>
                        <h1>دوستان من</h1>
                        <br>
                    </div>
                    <div class="col-md-8"></div>

                    <div class="col-md-12">
                        <?php
                        $mysql = pdodb::getInstance();

                        ini_set("error_reporting",E_ALL);

                        $query = "select FriendID from friends where PersonID=".$_SESSION["PersonID"];
                        $identity = $mysql->Execute($query);
                        while ($rec = $identity->fetch()){
                            $query = "select Name from privacy where Person_ID=".$rec["FriendID"];
                            $ident = $mysql->Execute($query);
                            $name = $ident->fetch();
                        ?>
                                <form method="get">
                                    <div class="list-group">
                                        <li class="list-group-item d-flex">
                                            <div class="col-sm-3">
                                                <p class="p-0 m-0 flex-grow-1"><a href="about.php?id=<?php echo $rec['FriendID']; ?>"><?php echo $name["Name"];?></a></p>
                                            </div>
                                            <div class="col-sm-6">
                                                <p class="p-0 m-0 flex-grow-1"><a href="about.php?id=<?php echo $rec['FriendID']; ?>"><?php echo "شماره دانشجویی: ".$rec["FriendID"];?></a></p>
                                            </div>
                                            <div class="col-sm-3">
                                                <a class="btn btn-danger" href="delete_friend.php?id=<?php echo $rec['FriendID']; ?>">حذف</a>
                                            </div>
                                        </li>
                                    </div>
                                </form>
                        <?php
                        }
                        ?>
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
