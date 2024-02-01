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
if($_SERVER["REQUEST_METHOD"]=="POST" && $sellerid!=$_SESSION["id"] && $_SESSION["userlvl"]>1){
    if($_POST["newprice"]<$price || empty(trim($_POST["newprice"]))){
        $price_err="Įveskite tinkamą kainą";
    } else{
        $newprice=$_POST["newprice"];
    }
    if(empty($price_err)){
        $sql="UPDATE auctions SET currentprice = $newprice, topbidder = ".$_SESSION['id']." WHERE id = $id";
        mysqli_query($link, $sql);
    }
    header("location: viewauction.php?id=$id");
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="lt">
<?php include "includes/navbar.php" ?>
<body>
<br>
<div class='container'>
    <?php
    echo
    "<h1>$name</h1>
    <h3>Pardavėjas: <b>$seller</b></h3>
    <h3>Dabartinė kaina: <b>$price €</b>, kurią pasiūlė vartotojas: <b>$bidder</b></h3>";
    if($_SESSION["userlvl"]>3 || $_SESSION["id"]==$sellerid){
        echo "<h2>Nustatyta minimali kaina: <b>$minprice €</b></h2>";
    }
    echo
    "<h3>Aukciono pabaiga: <b>$enddate</b></h3>
    <h3>Aprašymas:</h3>
    <p>$desc</p>";
    ?>
    <form method='post'>
        <div class="row">
            <div class="form-group col-sm-6">
                <label>Jūsų kaina:</label>
                <input name='newprice' type='number' step="0.01" min="<?php echo $price ?>"
                       class="form-control <?php (!empty($price_err)) ? 'is-invalid' : ''; ?> input-md" value="<?php echo $newprice ?>">
                <span class="invalid-feedback"><?php echo $price_err ?></span>
            </div>
            <div class="form-group col-sm-4">
                <input type='submit' name='ok' value='Siūlyti' class="btn btn-primary">
            </div>
        </div>
    </form>
</div>
</body>
</html>
