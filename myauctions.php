<?php
require "includes/config.php";
session_start();
if (($_SESSION['userlvl'] < 2))   { header("Location: logout.php");exit;}
?>

<!DOCTYPE html>
<html lang="lt">
<head>
    <?php
    include "includes/navbar.php";
    ?>
    <style>
        #aukcionai{
            font-family: Arial; border-collapse: collapse; width: 70%;
        }
        #aukcionai td{
            border: 1px solid black; padding: 8px;
        }
        <?php
        if($_SESSION['userlvl']>1){
            echo "#aukcionai td:last-child{border: none}";
        }
        ?>
    </style>
</head>
<body>
<br>
    <?php
    echo "<div class='container'>";
    if($_SESSION['userlvl']>2){
        $sql="SELECT * FROM auctions WHERE seller=".$_SESSION['id']." ORDER BY enddate DESC";
        $result=mysqli_query($link, $sql);
        if(mysqli_num_rows($result) > 0){
            echo "<div class='text-center'><b style='text-align: center; font-size: xx-large'>Mano sukurti aukcionai</b></div>";
            echo "<table style='margin: 0px auto;' id='aukcionai'>";
            echo
            "<tr>
        <th>Pavadinimas</th>
        <th>Pabaigos data</th>
        <th>Dabartinė kaina</th>
        <th>Pardavėjas</th>
        </tr>";
            while($row=mysqli_fetch_assoc($result)){
                $seller=$row['seller'];
                $sql1="SELECT username FROM users WHERE userid = $seller";
                $res=mysqli_query($link, $sql1);
                $seller=$link->query("SELECT username FROM users WHERE userid = $seller")->fetch_object()->username;
                echo
                    "<tr>
            <td>".$row['name']."</td>
            <td>".$row['enddate']."</td>
            <td>".$row['currentprice']."</td>
            <td>$seller</td>";
                if($_SESSION['userlvl']>3 || $_SESSION['username']==$seller){
                    echo
                        "<td>
                    <a class='btn btn-primary' href='viewauction.php?id=".$row['id']."'>Peržiūrėti</a>";
                    if(strtotime($row['enddate'])>time()){
                        echo "<a class='btn btn-primary' href='editauction.php?id=".$row['id']."'>Redaguoti</a>";
                    }
                    echo
                "</td>";
                } elseif ($_SESSION['userlvl']>1){
                    echo
                        "<td>
                    <a class='btn btn-primary' href='viewauction.php?id=".$row['id']."'>Peržiūrėti</a>
                </td>";
                }
                echo "</tr>";
            }
            echo "</table><br>";
        }
    }
    echo "</div>";
    echo "<div class='container'>";
    echo "<div class='text-center'><b style='text-align: center; font-size: xx-large'>Mano laimimi aukcionai</b></div>";
    $sql="SELECT * FROM auctions WHERE topbidder=".$_SESSION["id"]."";
    $result=mysqli_query($link, $sql);
    echo "<table style='margin: 0px auto;' id='aukcionai'>";
    echo
    "<tr>
        <th>Pavadinimas</th>
        <th>Pabaigos data</th>
        <th>Dabartinė kaina</th>
        <th>Pardavėjas</th>
        </tr>";
    while($row=mysqli_fetch_assoc($result)){
        $seller=$row['seller'];
        $sql1="SELECT username FROM users WHERE userid = $seller";
        $res=mysqli_query($link, $sql1);
        $seller=$link->query("SELECT username FROM users WHERE userid = $seller")->fetch_object()->username;
        echo
            "<tr>
            <td>".$row['name']."</td>
            <td>".$row['enddate']."</td>
            <td>".$row['currentprice']."</td>
            <td>$seller</td>";
        if($_SESSION['userlvl']>3 || $_SESSION['username']==$seller){
            echo
                "<td>
                    <a class='btn btn-primary' href='viewauction.php?id=".$row['id']."'>Peržiūrėti</a>";
            if(strtotime($row['enddate'])>time()){
                echo "<a class='btn btn-primary' href='editauction.php?id=".$row['id']."'>Redaguoti</a>
                </td>";
            }
            else {
                echo "</td>";
            }
        } elseif ($_SESSION['userlvl']>1){
            echo
                "<td>
                    <a class='btn btn-primary' href='viewauction.php?id=".$row['id']."'>Peržiūrėti</a>
                </td>";
        }
        echo "</tr>";
    }
    echo "</table>";
    echo "</div>";
    ?>
</body>
</html>