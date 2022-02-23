<?php
include "header.inc.php";
HTMLBegin();
$books = array();
$bookID=0;
$rate=0;
if (isset($_REQUEST["register"])) {
    $mysql = pdodb::getInstance();
    $query = "SELECT BookID FROM sadaf.shelfbooks";
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
    if($_POST[$bookID]=="ثبت"){
        if($_POST['shelf_book'.$bookID]!=null) {
            $mysql = pdodb::getInstance();
            $state = (int)$_POST['shelf_book' . $bookID];
            $query="UPDATE sadaf.shelf SET ShelfState=$state WHERE ShelfID in (SELECT ShelfID FROM sadaf.shelfbooks WHERE BookID=$bookID) ;";
            $mysql->Execute($query);
        }
    }
    elseif ($_POST[$bookID]=="حذف"){
        $query="DELETE FROM sadaf.shelf WHERE ShelfID in (SELECT ShelfID FROM sadaf.shelfbooks WHERE BookID=$bookID) ;";
        $mysql->Execute($query);
        $query="DELETE FROM sadaf.shelfbooks WHERE  BookID=$bookID ;";
        $mysql->Execute($query);
    }

}
$mysql = pdodb::getInstance();
$query= "SELECT BookID FROM sadaf.shelfbooks where ShelfID in
                                    (SELECT ShelfID FROM sadaf.shelf WHERE ShelfState=2); ";
$res1=$mysql->Execute($query);
while($row1 = $res1->fetch() ) {
    $bookID = $row1["BookID"];
    $query = "SELECT BookName, Authors ,Translaters,ISBN,ImageName FROM sadaf.books where BookID=$bookID";
    $res2 = $mysql->Execute($query);
    $row2 = $res2->fetch();
    $query= "SELECT AVG(Rate) AS avg FROM sadaf.rate WHERE BookID=$bookID;";
    $res3=$mysql->Execute($query);
    $row3 = $res3->fetch();
    $rate=number_format((float)$row3["avg"], 1, '.', '');
    if($rate==0.0){
        $rate="امتیازی ثبت نشده";
    }
    array_push($books, array($bookID, $row2["BookName"], $row2["Authors"], $row2["Translaters"], $row2["ISBN"],$row2["ImageName"],$rate));
}
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.slim.min.js" integrity="sha256-u7e5khyithlIdTpu22PHhENmPcRdFiHRjhAuHcs05RI=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/style-sheet.css">
    <link rel="stylesheet" href="css/shlefs-styles.css">
    <link rel="stylesheet" href="css/book(s)-styles.css">
    <link rel="stylesheet" href="css/star-rating.css">
    <title>کتاب‌های در حال مطالعه</title>
</head>
<body>
<form method="post" enctype="multipart/form-data">
    <div>
        <div class="nav-bar">
            <div>
                <a href="./SearchBook.php"><img class="icon back-icon" src="images/page-back.svg" alt=""></a>
            </div>
            <span></span>
            <div class="shelf-title">
                کتاب‌های در حال مطالعه
            </div>
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

    <script src="js/axios.js"></script>
    <div class="books" id="books">
        <?php
        if ($books==null){
            echo "<div class='text_shelf_empty'><p>کتابی در این قفسه موجود نیست</p></div>";
        }
        foreach ($books as $book) :
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
                <div class="add-to-shelf">
                    <div class="info-row">
                        <span></span>
                        اضافه به قفسه:
                    </div>
                    <select name="<?= "shelf_book".htmlspecialchars($book[0]) ?>" id="shelf_select" class="shelf-select">
                        <option value="" disabled selected>قفسه...</option>
                        <option  value="1">خوانده نشده</option>
                        <option  value="3">خوانده شده</option>
                    </select>
                </div>
                <div class="opt_shelf">
                    <span></span>
                    ثبت نظر: <span></span>
                    <textarea placeholder="ثبت نظر ..." ></textarea>
                </div>
                <div class="opt_shelf" >
                    <span></span>
                    امتیاز: <span></span>
                    <fieldset id="<?= "rate_shelf".htmlspecialchars($book[0]) ?>" name="<?= "rate".htmlspecialchars($book[0]) ?>"  class="rating" >
                        <input type="radio" id="<?= "rate1".htmlspecialchars($book[0]) ?>" name="rating" value="5" /><label class = "full" for="<?= "rate1".htmlspecialchars($book[0])?>"></label>
                        <input type="radio" id="<?= "rate2".htmlspecialchars($book[0]) ?>" name="rating" value="4" /><label class = "full" for="<?= "rate2".htmlspecialchars($book[0])?>"></label>
                        <input type="radio" id="<?= "rate3".htmlspecialchars($book[0]) ?>" name="rating" value="3" /><label class = "full" for="<?= "rate3".htmlspecialchars($book[0])?>"></label>
                        <input type="radio" id="<?= "rate4".htmlspecialchars($book[0]) ?>" name="rating" value="2" /><label class = "full" for="<?= "rate4".htmlspecialchars($book[0])?>"></label>
                        <input type="radio" id="<?= "rate5".htmlspecialchars($book[0]) ?>" name="rating" value="1" /><label class = "full" for="<?= "rate5".htmlspecialchars($book[0])?>"></label>
                    </fieldset>
                </div>
                <div class="info-row">
                    <input id='<?= $book[0]."save" ?>' name='<?= $book[0] ?>' type='submit'  value='ثبت'  style='width: 100%;'/>
                    <input id='<?= $book[0]."delete" ?>' name='<?= $book[0] ?>' type='submit'  value='حذف'  style='width: 100%;'/>
                </div>
            </div>
        <?php endforeach ?>
        <div/>
        <input type="hidden" name="register" id="register" >
</form>
</body>
</html>