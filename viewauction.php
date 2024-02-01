<?php
require_once "includes/config.php";
session_start();
if (($_SESSION['userlvl'] < 2))   { header("Location: logout.php");exit;}
$id=$_GET['id'];
$newprice=0.00;
$price_err="";
$sql="SELECT * FROM auctions WHERE id = $id";
$res=mysqli_query($link, $sql);
$row=mysqli_fetch_assoc($res);
$name=$row['name'];
$minprice=$row['minprice'];
$enddate=$row['enddate'];
$desc=$row['description'];
$price=$row['currentprice'];
$sellerid=$row['seller'];
$seller=$link->query("SELECT username FROM users WHERE userid = $sellerid")->fetch_object()->username;
$bidderid=$row['topbidder'];
if($bidderid!=null){
    $bidder=$link->query("SELECT username FROM users WHERE userid = $bidderid")->fetch_object()->username;
} else {
    $bidder="Nėra";
}
?>

<!DOCTYPE html>
<html lang="lt">
<head>
    <?php include "includes/navbar.php" ?>
    <style>
        #comments{
            font-family: Arial; border-collapse: collapse; width: 70%;
        }
        #comments td{
            border: 1px solid black; padding: 8px;
        }
    </style>
</head>
<body>
<br>
<div class='container'>
    <?php
    $sql="SELECT * FROM images WHERE auction='$id'";
    $result=mysqli_query($link, $sql);
    echo "<h1>$name</h1>";
    if($result!=false && mysqli_num_rows($result) > 0){
        echo
        "<div id='imageGallery' class='carousel slide' data-ride='carousel'>
            <div class='carousel-inner' role='listbox' style='width: 100%; background-color: grey;'>";
        $i=0;
        while($row=mysqli_fetch_assoc($result)){
            $image=$row['filename'];
            if($i==0){
                echo
                "<div class='carousel-item active'>
                <img class='d-block' height='500px' style='margin: auto' src='$image' alt='image'> 
            </div>";
            }
            else{
                echo
                "<div class='carousel-item'>
                <img class='d-block' height='500px' style='margin: auto' src='$image' alt='image'>
            </div>";
            }
            $i++;
        }
        echo "</div>
                <a class='carousel-control-prev' href='#imageGallery' role='button' data-slide='prev'>
                    <span class='carousel-control-prev-icon' aria-hidden='true'></span>
                    <span class='sr-only'>Praeitas</span>
                </a>
                <a class='carousel-control-next' href='#imageGallery' role='button' data-slide='next'>
                    <span class='carousel-control-next-icon' aria-hidden='true'></span>
                    <span class='sr-only'>Kitas</span>
                </a>
              </div>";
    }
    echo
    "<h3>Pardavėjas: <b><a href='viewuser.php?id=$sellerid'>$seller</a></b></h3>
    <h3>Dabartinė kaina: <b>$price €</b>, kurią pasiūlė vartotojas:";
    if($bidder=="Nėra"){
        echo "<b>$bidder</b>";
    }
    else {
        echo "<b><a href='viewuser.php?id=$bidderid'>$bidder</a></b></h3>";
    }
    if($_SESSION["userlvl"]>3 || $_SESSION["id"]==$sellerid){
        echo "<h2>Nustatyta minimali kaina: <b>$minprice €</b></h2>";
    }
    echo
    "<h3>Aukciono pabaiga: <b>$enddate</b></h3>
    <h3>Aprašymas:</h3>
    <p>$desc</p><br>";
    if($_SESSION['userlvl']>1 && $_SESSION['id']!=$sellerid && (strtotime($enddate)>time())){
        echo "<div class='text-center'><a class='btn btn-primary' href='offerprice.php?id=".$id."'>Siūlyti kainą</a></div><br>";
    }
    elseif((strtotime($enddate)<time()) && $_SESSION['id']==$bidderid){
        echo "<div class='text-center'><a class='btn btn-primary' href='leaverating.php?id=".$sellerid."&auction=".$id."'>Palikti atsiliepimą pardavėjui</a></div><br>";
    }
    elseif ((strtotime($enddate)<time()) && $_SESSION['id']==$sellerid){
        echo "<div class='text-center'><a class='btn btn-primary' href='leaverating.php?id=".$bidderid."&auction=".$id."'>Palikti atsiliepimą pirkėjui</a></div><br>";
    }
    ?>
</div>
<div class="container">
    <div class="row">
        <div class="col-sm-3"><h3>Komentarai</h3><br></div>
        <?php
        echo "<div class='col-cm-3'><a class='btn btn-primary' href='writecomment.php?id=".$id."'>Rašyti komentarą</a></div>";
        ?>
    </div>
    <?php
    $sql1="SELECT * FROM comments WHERE auction=$id order by created DESC";
    $results=mysqli_query($link, $sql1);
    if($results!=false && mysqli_num_rows($results) > 0){
        echo "<table style='margin: 0px auto;' id='comments'>";
        while($row=mysqli_fetch_assoc($results)){
            $userid=$row['user'];
            $user=$link->query("SELECT username FROM users WHERE userid = $userid")->fetch_object()->username;
            $time=$row['created'];
            $comment=$row['text'];
            echo "<tr><td><b>$user</b></td><td><b>$time</b></td></tr>
                    <tr><td colspan='2'>$comment</td></tr>";
        }
        echo "</table>";
    }
    else{
        echo "<h5>Komentarų nėra</h5>";
    }
    ?>
</div>
</body>
</html>
