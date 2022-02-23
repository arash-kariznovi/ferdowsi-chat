<?php
try {
    echo 'start';
    require('databseOpen.php');
    $connection = $GLOBALS['connect'];
    $isGroup = $_POST['isgroup'] == 1;
    $replyTo = ($_POST['replyto'] == 0) ? null : $_POST['replyto'];

//    $sql = "INSERT INTO Messages (caption, date, time, fromId, toId)
//            VALUES (:cap, :dat, :tim, :fromI, :toI)";
//    $send = $connection->prepare($sql);

    $sql = "INSERT INTO `Messages` (`id`, `fileType`, `caption`, `file`, `date`, `time`, `groupId`, `fromId`, `toId`, `replyTo`, `forwardedMessage`) 
        VALUES (NULL, :filetype, :cap, :fil,:dat, :tim, :groupid, :fromI, :toI, :replyto, :forwarded);";

    $send = $connection->prepare($sql);
    echo ' Query prepared. ';

    if (isset($_POST['message'])) {
        echo ' has mess ';
        if (isset($_FILES["fileselector"]) && $_FILES['fileselector']['error'] == 0) {
            echo ' has file ';
            mkdir('./files/' . $_POST['userid'] . 'to' . $_POST['toid']);
            echo " folder maked ";
            $file_address = './files/' . $_POST['userid'] . 'to' . $_POST['toid'] . '/' . $_FILES['fileselector']['name'];
            echo ' file address set: ' . $file_address . ' ';
            if (move_uploaded_file($_FILES['fileselector']['tmp_name'], $file_address)) {
                $send->execute(['filetype' => $_FILES['fileselector']['type'], ':cap' => $_POST['message'], ':fil' => $file_address,
                    ':dat' => date('Y-m-d'), ':tim' => date('H:i:s'), ':groupid' => $isGroup ? -1 * $_POST['toid'] : null,
                    'fromI' => $_POST['userid'], ':toI' => $isGroup ? null : $_POST['toid'], ':replyto' => $replyTo, ':forwarded' => null]);
                echo ' File sent ';
            } else {
                echo ' error was happened!, error: ' . $_FILES['fileselector']['error'];
                return;
            }
        } else {
            echo ' Has not file ';
            $send->execute([':filetype' => null, ':cap' => $_POST['message'], ':fil' => 0, ':dat' => date('Y-m-d'),
                ':tim' => date('H:i:s'), 'groupid' => $isGroup ? (-1 * $_POST['toid']) : null,
                'fromI' => $_POST['userid'], ':toI' => $isGroup ? null : $_POST['toid'], ':replyto' => $replyTo, ':forwarded' => null]);
            echo ' Text sent.';
        }

        $sql = $isGroup ?
            "UPDATE Groups SET lastAction = '" . date('Y-m-d') . ' ' . date('H:i:s') . "' WHERE id=" . (-1 * $_POST['toid']) :
            "UPDATE UserConnection SET lastAction = '" . date('Y-m-d') . ' ' . date('H:i:s') . "' WHERE 
            (firstId = {$_POST['userid']} AND secondId = {$_POST['toid']}) OR (firstId = {$_POST['toid']} OR secondId = {$_POST['userid']})";

        $connection->query($sql);
        echo " Last action updated.";
    } else if (isset($_POST['forwardid'])) {
        echo 'Has forward';
        $send->execute([':filetype' => null, ':cap' => null, ':fil' => 0, ':dat' => date('Y-m-d'), ':tim' => date('H:i:s'),
            'groupid' => $isGroup ? (-1 * $_POST['toid']) : null, 'fromI' => $_POST['userid'], ':toI' => $isGroup ? null : $_POST['toid'],
            ':replyto' => null, ':forwarded' => $_POST['forwardid']]);

        echo ' Forwarded. ';
        $sql = $isGroup ?
            "UPDATE Groups SET lastAction = '" . date('Y-m-d') . ' ' . date('H:i:s') . "' WHERE id=" . (-1 * $_POST['toid']) :
            "UPDATE UserConnection SET lastAction = '" . date('Y-m-d') . ' ' . date('H:i:s') . "' WHERE 
            (firstId = {$_POST['userid']} AND secondId = {$_POST['toid']}) OR (firstId = {$_POST['toid']} OR secondId = {$_POST['userid']})";

        $connection->query($sql);
        echo " Last action updated.";
    } else {
        echo 'Has not message';
    }

}
catch (Exception $e) {
    echo $e->getMessage();
}
