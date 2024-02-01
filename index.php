<?php
require "includes/config.php";
session_start();
if(!isset($_SESSION["userlvl"])){
    $_SESSION["userlvl"]=1;
    $_SESSION["username"]="Svečias";
}
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
<h5>Aivaras Vaitkus</h5>
<p>Esate prisijungę kaip <b><?php echo (!empty($_SESSION["username"])) ? htmlspecialchars($_SESSION["username"]) : "Svečias" ?></b></p>
<br>
<div class="container">
    <h3 style="text-align: center">Aukcionai</h3>
    <?php
    $sql="SELECT * FROM auctions WHERE enddate>NOW()";
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
                    <a class='btn btn-primary' href='viewauction.php?id=".$row['id']."'>Peržiūrėti</a>
                    <a class='btn btn-primary' href='editauction.php?id=".$row['id']."'>Redaguoti</a>
                </td>";
        } elseif ($_SESSION['userlvl']>1){
            echo
                "<td>
                    <a class='btn btn-primary' href='viewauction.php?id=".$row['id']."'>Peržiūrėti</a>
                </td>";
        }
        echo "</tr>";
    }
    ?>
</div>
</body>
</html>