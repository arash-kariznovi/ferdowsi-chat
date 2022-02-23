<?php
include "header.inc.php";
HTMLBegin();
$books = array();
$bookID=0;
$rate=0;
if (isset($_REQUEST["register"])) {
    if (isset($_REQUEST["search"])) {
        $mysql = pdodb::getInstance();
        $query= "SELECT BookID, BookName, Authors ,Translaters,ImageName,ISBN FROM sadaf.books";
        $res=$mysql->Execute($query);
        if(!empty($_REQUEST["SearchTerms"])){
            while($row = $res->fetch() ) {
                if(strpos($row["BookName"], $_REQUEST["SearchTerms"]) !== false||
                    strpos($row["Authors"], $_REQUEST["SearchTerms"]) !== false ||
                    strpos($row["Translaters"], $_REQUEST["SearchTerms"]) !== false ||
                    strpos($row["ImageName"], $_REQUEST["SearchTerms"]) !== false ||
                    $row["ISBN"]==$_REQUEST["SearchTerms"]) {
                    $bookID=$row["BookID"];
                    $query= "SELECT AVG(Rate) AS avg FROM sadaf.rate WHERE BookID=$bookID";
                    $res2=$mysql->Execute($query);
                    $row2 = $res2->fetch();
                    $rate=number_format((float)$row2["avg"], 1, '.', '');
                    if($rate==0.0){
                        $rate="امتیازی ثبت نشده";
                    }
                    array_push($books,array($row["BookID"],$row["BookName"],$row["Authors"],$row["Translaters"],$row["ISBN"],$row["ImageName"],$rate));
                }


            }
        }
    }
    else {
        $mysql = pdodb::getInstance();
        $query = "SELECT BookID FROM sadaf.books";
        $res = $mysql->Execute($query);
        while ($row = $res->fetch()) {
            if (isset($_REQUEST[$row["BookID"]])) {
                $bookID=$row["BookID"];
                break;
            }
        }
        if($_REQUEST["rating"]){
            $rate=$_REQUEST["rating"];
            $query = "SELECT Rate FROM sadaf.rate WHERE BookID=$bookID and AccountSpecID=1 ;";
            $res = $mysql->Execute($query);
            $row = $res->fetch();
            if($row == null) {
                $query = "insert into sadaf.rate (Rate,BookID,AccountSpecID) values (?,?,?)";
                $mysql->Prepare($query);
                $mysql->ExecuteStatement(array($rate, $bookID, 1));
            }
            else{
                $rate=$_REQUEST["rating"];
                $query="UPDATE sadaf.rate SET Rate=$rate WHERE BookID=$bookID and AccountSpecID=1 ;";
                $mysql->Execute($query);
            }
        }
        if($_POST['shelf_book'.$bookID]!=null) {
            $query = "SELECT BookID FROM sadaf.shelfbooks WHERE BookID=$bookID ";
            $res = $mysql->Execute($query);
            $row = $res->fetch();
            if ($row == null) {//insert book to shelf
                $query = "insert into sadaf.shelf (ShelfState) values (?)";
                $mysql->Prepare($query);
                $state = (int)$_POST['shelf_book' . $bookID];
                $mysql->ExecuteStatement(array($state));


                $query = "SELECT MAX( ShelfID ) AS max FROM sadaf.shelf;";
                $res = $mysql->Execute($query);
                $row = $res->fetch();
                $shelfID = $row['max'];

                $query = "insert into sadaf.shelfbooks (BookID,ShelfID) values (?,?)";
                $mysql->Prepare($query);
                $mysql->ExecuteStatement(array($bookID, $shelfID));
                echo "<div>ثبت شد</div>";
            } else {
                echo "<div>این کتاب در یکی از قفسه های شما موجود است</div>";
            }
        }
    }
}
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.slim.min.js" integrity="sha256-u7e5khyithlIdTpu22PHhENmPcRdFiHRjhAuHcs05RI=" crossorigin="anonymous"></script>
    <script src="js/logic.js"></script>
    <link rel="stylesheet" href="css/style-sheet.css">
    <link rel="stylesheet" href="css/book(s)-styles.css">
    <link rel="stylesheet" href="css/star-rating.css">
    <title>کتاب‌ها</title>
</head>
<body>
    <form method="post" enctype="multipart/form-data" id="frm">
        <div >
            <div class="nav-bar">
                <div>
                    <a href="./AddNewBook.php"><img class="icon" id="icon" src="images/add-book.svg" alt=""></a>
                </div>
                <span></span>
                <input type="submit"  name="search" id="search" value="جستجو"  />
                <span></span>
                <label>
                    <input type="text" name="SearchTerms" id="search-ipt" placeholder="ISBN، کتاب، نویسنده، ..." >
                </label>
                <div>
                    <select class="shelf-select" style="height: 34px;" onchange="location = this.value;">
                        <option value="" disabled selected>...رفتن به قفسه</option>
                        <option value="./UnreadBook.php">خوانده نشده</option>
                        <option value="./ReadingBook.php">در حال خواندن</option>
                        <option value="./ReadedBook.php">خوانده شده</option>
                    </select>
                </div>
            </div>
            <div class="container-scroll">
                <div class="books-container">
                    <div class="books" id="books"></div>
                </div>
            </div>
        </div>
        <!--    <input type="hidden" name="register" id="register" >-->
        <script src="js/axios.js"></script>
        <div class="books" id="books">
            <?php foreach ($books as $book):
                $src_image="./images/".$book[5];
                ?>
                <div class='book'>
                    <div class='_image'>
                        <img class="image" src="<?= $src_image ?>" alt="">
                    </div>
                    <div class="info-row">
                        <span></span>
                        نام کتاب: <span></span> <p><?= htmlspecialchars($book[1]) ?></p>
                    </div>
                    <div class="info-row">
                        <span></span>
                        امتیاز: <span></span> <p><?= htmlspecialchars($book[6]) ?></p>
                    </div>
                    <div class="info-row">
                        <span></span>
                        نویسنده: <span></span> <p><?= htmlspecialchars($book[2]) ?></p>
                    </div>
                    <div class="info-row">
                        <span></span>
                        مترجم: <span></span> <p><?= htmlspecialchars($book[3]) ?></p>
                    </div>
                    <div class="info-row">
                        <span></span>
                        ISBN: <span></span> <p><?= htmlspecialchars($book[4]) ?></p>
                    </div>
                    <?php
                    $mysql = pdodb::getInstance();
                    $query = "SELECT ShelfState FROM sadaf.shelf WHERE ShelfID in 
                                            (SELECT ShelfID FROM sadaf.shelfbooks WHERE BookID=$book[0]);" ;
                    $res = $mysql->Execute($query);
                    $row = $res->fetch();
                    if($row!=null && $row['ShelfState']!="1"):// reading or readed
                        if($row['ShelfState']=="2"){
                            $state_read="در حال خواندن";
                        }
                        else{$state_read="خوانده شده";
                        }
                        echo "<div class='text_read'>";
                        echo " <span></span>";
                        echo "این کتاب در قفسه ".$state_read." شما است";
                        echo "</div>";
                        ?>
                        <div class="info-row">
                            <span></span>
                            ثبت نظر: <span></span>
                            <textarea class="input-field" type="text" name="comment"
                            id="comment" placeholder="ثبت نظر ..."></textarea>
                        </div>
                        <div class="info-row" style="padding-top: 20px !important">
                            <span></span>
                            امتیاز: <span></span>
                            <fieldset id="<?= "rate".htmlspecialchars($book[0]) ?>" name="<?= "rate".htmlspecialchars($book[0]) ?>"  class="rating" >
                                <input type="radio" id="<?= "rate1".htmlspecialchars($book[0]) ?>" name="rating" value="5" /><label class = "full" for="<?= "rate1".htmlspecialchars($book[0])?>"></label>
                                <input type="radio" id="<?= "rate2".htmlspecialchars($book[0]) ?>" name="rating" value="4" /><label class = "full" for="<?= "rate2".htmlspecialchars($book[0])?>"></label>
                                <input type="radio" id="<?= "rate3".htmlspecialchars($book[0]) ?>" name="rating" value="3" /><label class = "full" for="<?= "rate3".htmlspecialchars($book[0])?>"></label>
                                <input type="radio" id="<?= "rate4".htmlspecialchars($book[0]) ?>" name="rating" value="2" /><label class = "full" for="<?= "rate4".htmlspecialchars($book[0])?>"></label>
                                <input type="radio" id="<?= "rate5".htmlspecialchars($book[0]) ?>" name="rating" value="1" /><label class = "full" for="<?= "rate5".htmlspecialchars($book[0])?>"></label>
                            </fieldset>
                        </div>
                        <div class="info-row">
                            <input id='<?= $book[0]."save" ?>' name='<?= $book[0] ?>' type='submit'  value='ثبت'  style='width: 100%;'/>
                        </div>
                    <?php elseif($row['ShelfState']=="1")://not readed
                        echo "<div class='text_not_read' >";
                        echo "این کتاب در قفسه خوانده نشده شما است و نمیتوانید نظر یا امتیازی برای این کتاب ثبت کنید";
                        echo "</div>";
                        ?>
                        <div class="info-row">
                            <span></span>
                            ثبت نظر: <span></span>
                            <textarea class="input-field" type="text" name="comment"
                            id="comment" placeholder="ثبت نظر ..."></textarea>
                        </div>
                        <div class="info-row" style="padding-top: 20px !important">
                            <input id='<?= $book[0]."save" ?>' name='<?= $book[0] ?>' type='submit'  value='ثبت'  style='width: 100%;' disabled/>
                        </div>
                    <?php else: ?>
                        <div class="add-to-shelf">
                            <div class="info-row">
                                <span></span>
                                اضافه به قفسه:
                            </div>
                            <select name="<?= "shelf_book".htmlspecialchars($book[0]) ?>" id="shelf_select" class="shelf-select">
                                <option value="" disabled selected>قفسه...</option>
                                <option  value="1">خوانده نشده</option>
                                <option  value="2">در حال خواندن</option>
                                <option  value="3">خوانده شده</option>
                            </select>
                        </div>
                        <div class="info-row" style="padding-top: 20px !important">
                            <span></span>
                            ثبت نظر: <span></span>
                            <textarea class="input-field" type="text" name="comment"
                            id="comment" placeholder="ثبت نظر ..."></textarea>
                        </div>
                        <div class="info-row" style="padding-top: 40px!important">
                            <input id='<?= $book[0]."save" ?>' name='<?= $book[0] ?>' type='submit'  value='ثبت'  style='width: 100%;'/>
                        </div>
                        <div id="output"></div>
                        <script>
                                // function postReply(commentId) {
                                //     $('#commentId').val(commentId);
                                //     $("#name").focus();
                                // }

                                $('<?= $book[0]."save" ?>').click(function () {
                                    var str = $("#frm").serialize();

                                    $.ajax({
                                        url: "./comment-add.php",
                                        data: str,
                                        type: 'post',
                                        success: function (response)
                                        {
                                            var result = eval('(' + response + ')');
                                            if (response)
                                            {
                                                $("#comment").val("");
                                            listComment();
                                            } else
                                            {
                                                alert("Failed to add comments !");
                                                return false;
                                            }
                                        }
                                    });
                                });
                                
                                $(document).ready(function () {
                                    listComment();
                                });

                                function listComment() {
                                    $.post("./comment-list.php",
                                            function (data) {
                                                var data = JSON.parse(data);
                                                
                                                var comments = "";
                                                var replies = "";
                                                var item = "";
                                                var parent = -1;
                                                var results = new Array();

                                                var list = $("<ul class='outer-comment'>");
                                                var item = $("<li>").html(comments);

                                                for (var i = 0; (i < data.length); i++)
                                                {
                                                    var commentId = data[i]['CommentID'];
                                                    // parent = data[i]['parent_comment_id'];

                                                    // if (parent == "0")
                                                    // {
                                                    //     comments = "<div class='comment-row'>"+
                                                    //     "<div class='comment-info'><span class='commet-row-label'>from</span> <span class='posted-by'>" + data[i]['comment_sender_name'] + " </span> <span class='commet-row-label'>at</span> <span class='posted-at'>" + data[i]['date'] + "</span></div>" + 
                                                    //     "<div class='comment-text'>" + data[i]['comment'] + "</div>"+
                                                    //     "<div><a class='btn-reply' onClick='postReply(" + commentId + ")'>Reply</a></div>"+
                                                    //     "</div>";

                                                    //     var item = $("<li>").html(comments);
                                                    //     list.append(item);
                                                    //     var reply_list = $('<ul>');
                                                    //     item.append(reply_list);
                                                    //     listReplies(commentId, data, reply_list);
                                                    // }
                                                }
                                                $("#output").html(list);
                                            });
                                }
                            </script>
                    <?php endif; ?>
                </div>
            <?php endforeach ?>
        </div>
        <input type="hidden" name="register" id="register" >
    </form>
</body>
</html>

