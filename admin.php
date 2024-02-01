<?php
session_start();
include "includes/config.php";
if (($_SESSION['userlvl'] != 4))   { header("Location: logout.php");exit;}
date_default_timezone_set("Europe/Vilnius");
?>

<!DOCTYPE html>
<html lang="lt">
<head>
    <?php
    include "includes/navbar.php";
    ?>
    <title>Administratoriaus sąsaja</title>
    <style>
        #users{
            font-family: Arial; border-collapse: collapse; width: 70%;
        }
        #users td{
            border: 1px solid black; padding: 8px;
        }
    </style>
</head>
<body>
<div class="container">
    <h3 style="text-align: center">Vartotojų registracija, peržiūra ir įgaliojimų keitimas</h3>
    <form name="vartotojai" action="procadmin.php" method="post">
        <?php
        $sql = "SELECT username,userlevel,email FROM users ORDER BY userlevel DESC,username";
        $result=mysqli_query($link, $sql);
        if (!$result || (mysqli_num_rows($result) < 1))
        {echo "Klaida skaitant lentelę users"; exit;}
        ?>
        <table style='margin: 0px auto;' id="users">
            <tr>
                <td><b>Vartotojo vardas</b></td>
                <td><b>Rolė</b></td>
                <td><b>E-paštas</b></td>
                <td><b>Šalinti?</b></td>
            </tr>
            <?php
            while($row = mysqli_fetch_assoc($result))
            {
                $level=$row['userlevel'];
                $user= $row['username'];
                $email = $row['email'];
                echo "<tr><td>".$user. "</td><td>";
                echo "<select name=\"role_".$user."\">";
                $yra=false;
                foreach($user_roles as $x=>$x_value)
                {echo "<option ";
                    if ($x_value == $level) {$yra=true;echo "selected ";}
                    echo "value=\"".$x_value."\" ";
                    echo ">".$x."</option>";
                }
                if (!$yra)
                {echo "<option selected value=".$level.">Neegzistuoja=".$level."</option>";}
                echo "</select></td>";

                echo "<td>".$email."</td>";
                echo "<td><input type=\"checkbox\" name=\"naikinti_".$user."\"></td></tr>";
            }
            ?>
        </table>
        <br>
        <div class="text-center">
            <a class='btn btn-primary' href='adminregister.php'>Registruoti naują vartotoją</a>
            <input type="submit" class="btn btn-primary" value="Vykdyti">
        </div><br>
    </form>
</div>
<div class="container">
    <h3 style="text-align: center">Aukcionų trynimas</h3>
    <form name="aukcionai" action="procauctionadmin.php" method="post">
        <?php
        $sql="SELECT id, name, enddate, seller FROM auctions ORDER BY id ASC";
        $result=mysqli_query($link, $sql);
        if (!$result || (mysqli_num_rows($result) < 1))
        {echo "Klaida skaitant lentelę users"; exit;}
        ?>
        <table style='margin: 0px auto;' id="users">
            <tr>
                <td><b>Id</b></td>
                <td><b>Pavadinimas</b></td>
                <td><b>Pabaigos data</b></td>
                <td><b>Pardavėjas</b></td>
                <td><b>Šalinti?</b></td>
            </tr>
            <?php
            while($row = mysqli_fetch_assoc($result))
            {
                $id=$row['id'];
                $name= $row['name'];
                $enddate = $row['enddate'];
                $seller= $row['seller'];
                $seller=$link->query("SELECT username FROM users WHERE userid = $seller")->fetch_object()->username;
                echo
                    "<tr><td>".$id."</td>
                        <td>".$name."</td>
                        <td>".$enddate."</td>
                        <td>".$seller."</td>";
                echo "<td><input type=\"checkbox\" name=\"naikinti_".$id."\"></td></tr>";
            }
            ?>
        </table>
        <br>
        <div class="text-center"><input type="submit" class="btn btn-primary" value="Vykdyti"></div><br>
    </form>
</div>
</body>
</html>
