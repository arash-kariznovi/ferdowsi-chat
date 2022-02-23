<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
<head>
    <link rel="stylesheet" type="text/css" href="css/style.css"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<!--    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Sofia">-->
    <link rel="stylesheet" href="fonts.googleapis.com/earlyaccess/notonaskharabic.css">
    <link href="https://fonts.googleapis.com/css?family=Lobster" rel="stylesheet" type="text/css">
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <title>پیامرسان</title>
    <meta name="google-site-verification" content="google-site-verification=ZHpZj5Y9GBZTRN6JDv29tP1PJw-lWEa5LjMxIVscMfU">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
</head>
<body>
<?php
try {
if(isset($_COOKIE['user']) && !isset($_SESSION["loggedin"])) {

    require('databseOpen.php');
    $connection = $GLOBALS['connect'];

//    $userList = [];

    $user = json_decode($_COOKIE['user'], true);

    $sql = "SELECT * FROM User WHERE studentId='".$user['studentid']."' AND password='".$user['password']."'";
    $quer = $connection->query($sql);
    $thisUser = $quer->fetch(PDO::FETCH_ASSOC);

    if(isset($thisUser['id'])) {
        $_SESSION["loggedin"] = true;
        $_SESSION["id"] = $thisUser['id'];
        $_SESSION["studentid"] = $user['studentid'];
    }
    else {
        $_SESSION["loggedin"] = false;
        header("location:login.php");
    }

}
else {
    header("location:login.php");
}

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
$userID = $_SESSION['id'];

if(isset($_POST['chat_to_add'])) {
    $sql = "SELECT id FROM User WHERE studentId='{$_POST['chat_to_add']}'";
    $result = $connection->query($sql)->fetch(PDO::FETCH_ASSOC);
    file_get_contents("send.php?message='#new##'&userid=$userID&toid={$result['id']}");
}

?>
<!--<input id="is-mobile" type="hidden">-->
<div class="container">
    <div class="list">
        <div class="chat-header">
            <div class="menu-button">
                <span class="top-span"></span>
                <span class="middle-span"></span>
                <span class="bottom-span"></span>
                <div class="menu-list">
                    <div id="add-chat-btn" class="menu-item">افزودن گفت و گو</div>
                    <div id="logout-btn" class="menu-item">خروج از حساب</div>
                </div>
            </div>
            <h3>پیامرسان دانشجو</h3>
        </div>

        <?php
        $sql = "SELECT CONCAT('-', Groups.id) AS id, Groups.groupName AS name, Groups.lastAction AS lastTime 
            FROM GroupConnection JOIN Groups ON GroupConnection.chatId = Groups.id 
            WHERE GroupConnection.userId = {$userID} AND GroupConnection.leftChat = 0
            UNION ALL
            SELECT User.id As id, CONCAT(User.firstName, ' ', User.lastName) AS name , UserConnection.lastAction AS lastTime
            FROM UserConnection JOIN User ON UserConnection.secondId = User.id WHERE UserConnection.firstId = $userID
            ORDER BY lastTime DESC";
        $messQuer = $connection->query($sql);
        ?>
        <ul id="chatList" class="chat-list">
            <?php
            $added = [];
            while($row = $messQuer->fetch(PDO::FETCH_ASSOC)) {
                ?>
                <li id="<?php echo 'view'.$row['id']?>" class="chat-view" onclick="chatView(<?php echo $row['id']?>)">
                    <div>
                        <div class="picture-container"><img src="images/emptyProfile.jpg" alt="" class="chat-pic"></div>
                        <div class="name-container">
                            <h4><?php echo $row['name']?></h4>
                        </div>
                    </div>
                </li>
                <?
                $added[$row['id']] = $row['name'];
                } ?>
        </ul>
    </div>
    <div class="chat-page">
        <?php
        foreach (array_keys($added) as $usId) {
            $isGroup = false;
            if($usId > 0) {
                $messQuery = $connection->query("SELECT * FROM Messages WHERE (fromId='$userID' AND toId='$usId') 
                OR (fromId='$usId' AND toId='$userID') ORDER BY date DESC, time DESC;");
                }
            else {
                $messQuery = $connection->query("SELECT Messages.*, Messages.groupId AS toId FROM Messages WHERE groupId='".(-1 * $usId)."' ORDER BY date DESC, time DESC");
                $isGroup = true;
            }
        $messages = $messQuery->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <div id="<?php echo 'chatn'.$usId?>" class="chat-nav">
            <div class="chat-nav-header">
                <div id="chat-back<?php echo $usId?>" class="back-div"><a class="back-link">بازگشت ></a></div>
                <div class="contact-information">
                    <div id="chat-avatar<?php echo $usId?>" class="picture-container chat-avatar"><img src="images/emptyProfile.jpg" alt="" class="chat-pic"></div>
                    <div id="chat-name<?php echo $usId?>" class="name-container chat-name">
                        <h3><?php echo $added[$usId]?></h3>
                    </div>
                </div>
            </div>
            <div class="chat-body">
                <div class="free-space"></div>
                <ul id="mess-list<?php echo $usId?>" class="messages-list">
                <iframe name="dummyframe" id="dummyframe" style="display: none;"></iframe>
                    <?php
                    foreach ($messages as $message) {
                        $isForward = false;
                        if($message['forwardedMessage'] != null) {
                        $sq = "SELECT a.id, b.caption, b.file, b.fileType, a.date, a.time, a.fromId, a.toId, a.groupId, a.seen, a.replyTo, b.fromId AS forwardId, a.forwardedMessage As forwardMess 
                                FROM Messages AS a JOIN Messages As b ON b.id = a.forwardedMessage WHERE a.id = ".$message['id'];
                        $message = $connection->query($sq)->fetch(PDO::FETCH_ASSOC);
                        $isForward = true;
                        $id = $message['forwardId'];
                        $userInfo = $connection->query("SELECT * FROM User WHERE id=$id")->fetch(PDO::FETCH_ASSOC);
                        } else {
                            $id = $message['fromId'];
                            $userInfo = $connection->query("SELECT * FROM User WHERE id=$id")->fetch(PDO::FETCH_ASSOC);
                        }


                        if($message['caption'] == '#new##')
                            continue;

                        if($message['file'] != '0') {
                            $type = explode('/', $message['fileType'])[0];
                            $fl = explode('/', $message['file']);
                            $fileAddress = dirname('./'.$message['file']).'/'.end($fl);

                            switch ($type) {
                                case 'image':
                                    if($isGroup && $message['fromId'] != $userID) {
                                    ?>
                                    <li id="line<?php echo $message['id']?>" <?php if($isForward) echo " data-action='".$message['forwardMess']."'"?> class="message-line <?php echo $message['fromId'] == $userID ? 'their-message' : 'other-message' ?> in-group" dir="<?php echo $message['fromId'] == $userID ? 'rtl' : 'ltr'?>">
                                        <div class="message" style="display: flex; flex-flow: column; padding: 3px">
                                            <div class="sender-name-contain"><?php
                                                echo ($isForward ? 'هدایت شده از: ' : '').$userInfo['firstName'].' '.$userInfo['lastName']?>
                                            </div>
                                            <a href="<?php echo $fileAddress ?>" download >
                                            <img class="message-image" src="<?php echo str_replace('./' , '' ,$message['file'])?>">
                                            </a>
                                            <p class="message-p" id="mess<?php echo $message['id'] ?>"><?php echo $message['caption']?></p>
                                        </div>
                                    </li>
                                    <?php
                                    }
                                    else {
                                        ?>
                                        <li id="line<?php echo $message['id']?>" <?php if($isForward) echo " data-action='".$message['forwardMess']."'"?> class="message-line <?php echo $message['fromId'] == $userID ? 'their-message' : 'other-message';?>" dir="<?php echo $message['fromId'] == $userID ? 'rtl' : 'ltr'?>">
                                            <div class="message" style="display: flex; flex-flow: column; padding: 3px">
                                                <?php if($isGroup) {
                                                    echo "<div class='sender-name-contain'>هدایت شده از: ".$userInfo['firstName'].' '.$userInfo['lastName']."</div>";
                                                }?>
                                                <a href="<?php echo $fileAddress?>" download>
                                                    <img class="message-image" src="<?php echo str_replace('./' , '' ,$message['file'])?>">
                                                </a>
                                                <p class="message-p" id="mess<?php echo $message['id'] ?>"><?php echo $message['caption']?></p>
                                            </div>
                                        </li>
                                        <?php
                                    }
                                    break;
                                default:
                                    if($isGroup && $message['fromId'] != $userID) { ?><li id="line<?php echo $message['id']?>" class="message-line has-file <?php echo $message['fromId'] == $userID ? 'their-message' : 'other-message'?>" dir="<?php echo $message['fromId'] == $userID ? 'rtl' : 'ltr'?>">
                                    <li id="line<?php echo $message['id']?>" <?php if($isForward) echo " data-action='".$message['forwardMess']."'"?> class="message-line in-group has-file <?php echo $message['fromId'] == $userID ? 'their-message' : 'other-message'; if($isForward) echo ' forwarded'?>" dir="<?php echo $message['fromId'] == $userID ? 'rtl' : 'ltr'?>">
                                        <div class="message-file-container">
                                            <div class="sender-name-contain"><?php echo ($isForward ? 'هدایت شده از: ' : '').$userInfo['firstName'].' '.$userInfo['lastName']?></div>
                                            <a href="<?php echo $fileAddress?>" download>
                                                <div class="file-message">
                                                    <img class="message-file-image" src="images/file-icon.jpg">
                                                    <p class="file-name"><?php $name = explode('/', $message['file']); echo end($name)?></p>
                                                </div>
                                            </a>
                                        </div>
                                    <div class="message" style="display: flex; flex-flow: column; padding: 5px; margin-top: 0;"><?php echo $message['caption']?></div></li>
                                    <?php } else { ?>
                                    <li id="line<?php echo $message['id']?>" <?php if($isForward) echo " data-action='".$message['forwardMess']."'"?> class="message-line has-file <?php echo $message['fromId'] == $userID ? 'their-message' : 'other-message'; if($isForward == true) echo ' in-group forwarded';?>" dir="<?php echo $message['fromId'] == $userID ? 'rtl' : 'ltr'?>">
                                        <?php if($isForward) {
                                            $name = explode('/', $message['file']);
                                            echo    "<div class='message-file-container'>
                                                        <div class='sender-name-contain'>هدایت شده از: ".$userInfo['firstName'].' '.$userInfo['lastName']."
                                                            <a href='$fileAddress' download>
                                                                <div class='file-message'>
                                                                    <img class='message-file-image' src='images/file-icon.jpg'>
                                                                    <p class='file-name'>".end($name)."</p> 
                                                                </div>
                                                            </a>
                                                        </div>
                                                    </div>";
                                        } else {?>
                                        <a href="<?php echo $fileAddress?>" download>
                                        <div class="message-file-container">
                                            <img class="message-file-image" src="images/file-icon.jpg">
                                            <p class="file-name"><?php $name = explode('/', $message['file']); echo end($name)?></p>
                                        </div>
                                        </a>
                                            <div class="message" style="display: flex; flex-flow: column; padding: 5px; margin-top: 0;"><p class="message-p" id="mess<?php echo $message['id'] ?>"><?php echo $message['caption']?></p></div>
                                    </li>
                                    <?php
                                        }
                                    }
                            }
                        }
                        else {
                            if($isGroup && $message['fromId'] != $userID) {
                            ?>
                            <li id="line<?php echo $message['id']?>" <?php if($isForward) echo " data-action='".$message['forwardMess']."'"?> class="message-line in-group <?php echo $message['fromId'] == $userID ? 'their-message' : 'other-message'?>" dir="<?php echo $message['fromId'] == $userID ? 'rtl' : 'ltr'?>">
                                <div class="message">
                                    <div class="sender-name-contain"><?php if($isForward)echo 'هدایت شده از: '; echo $userInfo['firstName'].' '.$userInfo['lastName']?></div>
                                    <p class="message-p" id="mess<?php echo $message['id'] ?>"><?php echo $message['caption']?></p>
                                </div>
                            </li>
                            <?php
                            } else { ?>
                            <li id="line<?php echo $message['id']?>" <?php if($isForward) echo " data-action='".$message['forwardMess']."'"?> class="message-line <?php echo $message['fromId'] == $userID ? 'their-message' : 'other-message'?>" dir="<?php echo $message['fromId'] == $userID ? 'rtl' : 'ltr'?>">
                                <div class="message">
                                    <?php if($isForward) { echo "<div class='sender-name-contain'>هدایت شده از: ".$userInfo['firstName'].' '.$userInfo['lastName']."</div>";} ?>
                                    <p class="message-p" id="mess<?php echo $message['id'] ?>" id="mess<?php echo $message['id'] ?>"><?php echo $message['caption']?></p>
                                </div>
                            </li>
                            <?php
                            }
                        }
                    }
                    ?>
                </ul>
            </div>
            <form enctype="multipart/form-data" class="chat-nav-footer" id="chat-footer<?php echo $usId?>" >

                <div class="nav-btn emoji-button">
                    <span class="material-icons">&#128512;</span>
                </div>
                <div class="sending-form">
                    <a class="reply-link" id="replyLink<?php echo $usId?>" href="">
                        <div class="reply-div" id="reply<?php echo $usId?>">
                            <div class="reply-container" id="reply<?php echo $usId?>" href="">
                                <div class="reply-name-container" id="replyName<?php echo $usId?>">name</div>
                                <div class="reply-message-container" id="replyMessage<?php echo $usId?>">
                                    <div class="reply-message-view">message</div>
                                </div>
                            </div>
                            <div class="delete-selected-reply" onclick="deleteReply(<?php echo $usId?>)" id="deleteReply<?php echo $usId?>">&times;</div>
                        </div>
                    </a>
                    <div class="message-container" id="message-contain<?php echo $usId?>">
                        <img src="" id="sending-file-image<?php echo $usId?>"/>
                        <label for="sending-file-image<?php echo $usId?>" class="file-caption" id="file-cap<?php echo $usId?>"></label>
                        <div class="delete-selected-file" onclick="closeFile(<?php echo $usId?>)" id="delete-file<?php echo $usId?>">&times;</div>
                    </div>
                    <textarea name="message" id="message-textarea<?php echo $usId?>" placeholder="پیام" name="Message" class="message-box" dir="auto"></textarea>
                    <input id="toId<?php echo $usId?>" name="toid" value="<?php echo $usId?>" type="hidden">
                    <input id="userId<?php echo $usId?>" name="userid" value="<?php echo $userID?>" type="hidden">
                    <input id="isGroup<?php echo $usId?>" name="isgroup" value="<?php echo $usId < 0 ? 1 : 0?>" type="hidden">
                    <input id="replyTo<?php echo $usId?>" name="replyto" value="0" type="hidden">
                </div>
                <button name="submit-send<?php echo $usId?>" onclick="sendMessage(<?php echo $usId?>)" id="send-btn<?php echo $usId?>" class="nav-btn send-button"><span class="material-icons">send</span></button>
                <div id="select-files<?php echo $usId?>" class="nav-btn select-file">
                    <i class="material-icons" onclick="selectFileClick(<?php echo $usId?>)">attach_file</i>
                    <div class="select-file-dialog">
                        <input name="fileselector" onchange="changeDialog(this, <?php echo $usId?>)" id="file-dialog<?php echo $usId?>" type="file">
                    </div>
                </input>
                </div>
            </form>
        </div>
        <?php } ?>
        <p>لطفا یک چت را انتخاب نمایید.</p>
    </div>
    <div class="other-pages">
        <div class="other-div add-chat-div">
            <input type="text" id="add-chat-text" placeholder="کد دانشجویی" class="add-student-id">
            <button onclick="addChat()" id="add-chat" class="add-chat-btn">اضافه کردن</button>
        </div>
        <div class="other-div" id="select-forward-chat">
            <h3 dir="rtl" >گفت و گویی را انتخاب کنید:</h3>
            <ul class="select-forward-list">
                <?php
                foreach (array_keys($added) as $key) {
                    echo '<li class="forward-item" data-action="'.$key.'" id="forward'.$key.'">'.$added[$key].'</li>';
                }
                ?>
            </ul>
        </div>
    </div>
</div>
<div class="context-menu">
    <ul class="context-list">
        <li id="reply-line" class="context-item">
            <p>پاسخ</p>
        </li>
        <li id="forward-line" class="context-item">
            <p>باز ارسال</p>
        </li>
    </ul>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="script.js"></script>
</body>
</html>
<?php
    }
}
catch (PDOException $e) {
    echo 'Error: '.$e->getMessage();
    return;
}