<?php
require_once "includes/config.php";
session_start();
$id=$_GET['id'];
$sql="SELECT * FROM users WHERE userid = $id";
$res=mysqli_query($link, $sql);
$row=mysqli_fetch_assoc($res);
$username=$row['username'];
$userlvl=$row['userlevel'];
$email=$row['email'];
$rating=0;
$result=$link->query("SELECT AVG(rating) FROM ratings WHERE receivedby=$id");
if($result!=false && mysqli_num_rows($result)>0){
    $row=$result->fetch_row();
    $rating=$row[0];
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
    <?php ;
    echo "<h1>$username</h1>";
    echo "<h3>Naudotojo reitingas: ";
    if($rating==0){
        echo "<b>Nėra</b>";
    }
    else{
        echo "<b>".number_format($rating, 2)."/5</b>";
    }
    echo "</h3>";
    echo "<h5>Elektroninis paštas: $email</h5>";
    if($_SESSION['userlvl']>1){
        $result1=$link->query("SELECT COUNT(id) FROM auctions WHERE seller=$id AND topbidder IS NOT NULL AND enddate<NOW()");
        $row=$result1->fetch_row();
        $count=$row[0];
        echo "<h5>Parduota prekių: <b>$count</b></h5>";
    }
    ?>
</div>
<div class="container">
    <div class="row">
        <div class="col-sm-3"><br><h3>Atsiliepimai:</h3><br></div>
    </div>
    <?php
    $sql1="SELECT * FROM ratings WHERE receivedby=$id order by id DESC";
    $results=mysqli_query($link, $sql1);
    if($results!=false && mysqli_num_rows($results) > 0){
        echo "<table style='margin: 0px;' id='comments'>";
        while($row=mysqli_fetch_assoc($results)){
            $userid=$row['leftby'];
            $user=$link->query("SELECT username FROM users WHERE userid = $userid")->fetch_object()->username;
            $rating=$row['rating'];
            $comment=$row['comment'];
            echo "<tr><td><b>$user</b></td><td><b>$rating</b></td></tr>
                    <tr><td colspan='2'>$comment</td></tr>";
        }
        echo "</table>";
    }
    else{
        echo "<h5>Atsiliepimų nėra</h5>";
    }
    ?>
</div>
</body>
</html>
