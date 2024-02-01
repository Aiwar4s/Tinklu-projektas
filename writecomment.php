<?php
require_once "includes/config.php";
session_start();
if (($_SESSION['userlvl'] < 2))   { header("Location: logout.php");exit;}
$id=$_GET['id'];
if($_SERVER["REQUEST_METHOD"]=="POST"){
    $comment=$comment_err="";
    if(empty(trim($_POST["comment"]))){
        $comment_err="Įveskite komentarą";
    } else{
        $comment=trim($_POST["comment"]);
    }
    if(empty($comment_err)){
        $sql="INSERT INTO comments (user, text, auction, created) VALUES (?, ?, ?, NOW())";
        if($stmt=mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "isi", $param_user, $param_text, $param_auction);
            $param_user=$_SESSION['id'];
            echo $_SESSION['id'];
            $param_text=$comment;
            $param_auction=$id;
            if(mysqli_stmt_execute($stmt)){
                header("location: viewauction.php?id=$id");
            }
            else{
                echo "Įvyko klaida, bandykite dar kartą";
            }
        }
    }
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html>
<head>
    <?php
    include "includes/navbar.php";
    ?>
</head>
<body>
<br>
<div class="container">
    <h2>Naujas komentaras</h2>
    <form method='post'>
        <div class="form-group col-sm-12">
            <label for="comment" class="control-label">Jūsų komentaras:</label>
            <textarea name='comment' type='text' class="form-control input-sm"></textarea>
            <?php
            if(!empty($comment_err)){
                echo "<p style='color: red'>$comment_err</p>";
            }
            ?>
        </div>
        <div class="form-group col-sm-2">
            <input type='submit' name='ok' value='siųsti' class="btn btn-primary">
        </div>
    </form>
</div>
</body>
</html>