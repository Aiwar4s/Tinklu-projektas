<?php
// procadmin.php  kai adminas keičia vartotojų įgaliojimus ir padaro atžymas lentelėje per admin.php
// ji suformuoja numatytų pakeitimų aiškią lentelę ir prašo patvirtinimo, toliau į procadmindb, kuri įrašys į DB

session_start();

include "includes/config.php";
if (($_SESSION['userlvl'] != 4))   { header("Location: logout.php");exit;}
$sql = "SELECT username,userlevel,email FROM users ORDER BY userlevel DESC,username";
$result = mysqli_query($link, $sql);
if (!$result || (mysqli_num_rows($result) < 1))
{echo "Klaida skaitant lentelę users"; exit;}
?>
<html>
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
    <h3>Vartotojų įgaliojimų pakeitimas</h3>
    <form name="vartotojai" action="procadmindb.php" method="post">
        <h5>Patikrinkite ar teisingi pakeitimai</h5>
        <table style='margin: 0px auto;' id="users">
            <tr>
                <td><b>Vartotojo vardas</b></td>
                <td><b>Buvusi rolė</b></td>
                <td><b>Nauja rolė</b></td>
            </tr>
            <?php
            $naikpoz=false;
            while($row=mysqli_fetch_assoc($result)){
                $level=$row['userlevel'];
                $user=$row['username'];
                $nlevel=$_POST['role_'.$user];
                $naikinti=isset($_POST['naikinti_'.$user]);
                if($naikinti || $nlevel!=$level){
                    $keisti[]=$user;
                    echo "<tr><td>".$user."</td><td>";
                    foreach ($user_roles as $x=>$x_value){
                        if($x_value==$level) echo $x;
                    }
                    echo "</td><td>";
                    if($naikinti){
                        echo "<p style='color: red'>PAŠALINTI</p>";
                        $pakeitimai[]=-1;
                        $naikpoz=true;
                    }
                    else{
                        $pakeitimai[]=$nlevel;
                        foreach ($user_roles as $x=>$x_value){
                            if($x_value==$nlevel) echo $x;
                        }
                    }
                    echo "</td></tr>";
                }
            }
            if($naikpoz){
                echo "<br><p>Dėmesio! Bus šalinami tik įrašai iš lentelės 'users'.</p><br>";
            }
            if(empty($keisti)){
                header("Location:admin.php");
                exit;
            }
            $_SESSION['ka_keisti']=$keisti;
            $_SESSION['pakeitimai']=$pakeitimai;
            ?>
        </table>
        <div class="text-center"><input type="submit" class="btn btn-primary" value="Atlikti"></div>
    </form>
</div>
</body></html>
