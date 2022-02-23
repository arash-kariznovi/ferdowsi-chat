<?php
$conn = mysqli_connect("localhost","root","test","sadaf");
$commentId = isset($_POST['CommentID']) ? $_POST['CommentID'] : "";
$comment = isset($_POST['CommentText']) ? $_POST['CommentText'] : "";
$date = date('Y-m-d H:i:s');

$sql = "INSERT INTO sadaf.comments (CommentID,CommentText,WrittenAt) VALUES ('" . $commentId . "','" . $comment . "','" . $date . "')";

$result = mysqli_query($conn, $sql);

if (! $result) {
    $result = mysqli_error($conn);
}
echo $result;
?>
