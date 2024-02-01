<?php
session_start();
require_once "includes/config.php";
$id=(int)$_GET['id'];
$user=$link->query("SELECT username FROM users WHERE userid = $id")->fetch_object()->username;
$currentid=(int)$_SESSION['id'];
$currentuser=$link->query("SELECT username FROM users WHERE userid = $currentid")->fetch_object()->username;
$sql="SELECT * FROM ratings WHERE leftby = $currentid AND receivedby = $id";
$result=mysqli_query($link, $sql);
$exists=false;
$comment="";
if($result!=false && mysqli_num_rows($result)>0){
    echo "based";
    $row=mysqli_fetch_assoc($result);
    $exists=true;
    $ratingid=$row['id'];
    $rating=$row['rating'];
    $comment=$row['comment'];
}
if($_SERVER["REQUEST_METHOD"]=="POST"){
    $comment=trim($_POST["comment"]);
    if(!$exists){
        $sql="INSERT INTO ratings (rating, comment, receivedby, leftby) VALUES (?, ?, ?, ?)";
        if($stmt=mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "isii", $param_rating, $param_comment, $param_received, $param_left);
            $param_rating=$_POST['rating'];
            $param_comment=$comment;
            $param_received=$id;
            $param_left=$_SESSION['id'];
            if(mysqli_stmt_execute($stmt)){
                header("location: viewuser.php?id=$id");
            }
            else{
                echo "Įvyko klaida, bandykite dar kartą";
            }
        }
    }
    else{
        $rating=$_POST['rating'];
        $sql="UPDATE ratings SET rating=$rating, comment='$comment' WHERE receivedby=$id AND leftby=".$_SESSION['id']."";
        if (!mysqli_query($link, $sql)) {
            echo " DB klaida keičiant įvertinimą: " . $sql . "<br>" . mysqli_error($link);
            exit;}
        header("location: viewuser.php?id=$id");
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
    <?php
    echo "<h3>Vertinimas naudotojui: <b>".$user."</b></h3><br>";
    ?>
    <form method='post'>
        <?php
        echo "<div class='form-group col-sm-12'>
            <label>Įvertinimas</label>
            <select name='rating'>";
        for($i=1; $i<=5; $i++){
            if($exists && $i==$rating){
                echo "<option selected value='$i'>$i</option>";
            }
            else{
                echo "<option value='$i'>$i</option>";
            }
        }
        echo "</select>
        </div>";
        ?>
        <div class="form-group col-sm-12">
            <label for="comment" class="control-label">Jūsų komentaras:</label>
            <textarea name='comment' type='text' class="form-control input-sm"><?php echo $comment ?></textarea>
        </div>
        <div class="form-group col-sm-2">
            <input type='submit' name='ok' value='siųsti' class="btn btn-primary">
        </div>
    </form>
</div>
</body>
</html>