<?php
require "includes/config.php";
session_start();
$id=$_GET["id"];
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
if ($_SESSION['id']!=$sellerid && ($_SESSION['userlvl'] !=4))   { header("Location: logout.php");exit;}
if($bidderid!=null){
    $bidder=$link->query("SELECT username FROM users WHERE userid = $bidderid")->fetch_object()->username;
} else {
    $bidder="Nėra";
}

$newname=$name_err="";
$newdate=$date_err="";
$newprice=0.00;
$newdesc="";
if($_SERVER["REQUEST_METHOD"]=="POST"){
    if(empty(($_POST["name"]))){
        $name_err="Įveskite pavadinimą";
    } else{
        $newname=trim($_POST["name"]);
    }
    if(empty(trim($_POST["enddate"]))){
        $date_err="Pasirinkite datą";
    } else{
        $newdate=trim($_POST["enddate"]);
    }
    if(!empty(trim($_POST["minprice"]))){
        $newprice=trim($_POST["minprice"]);
    }
    if(!empty(trim($_POST["description"]))){
        $newdesc=trim($_POST["description"]);
    }
    if(empty($name_err) && empty($date_err)){
        $sql="UPDATE auctions SET name = ?, minprice = ?, enddate = ?, description = ? WHERE id = $id";
        if($stmt=mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "sdss", $param_name, $param_price, $param_date, $param_description);
            $param_name=$newname;
            $param_price=$newprice;
            $param_date=$newdate;
            $param_description=$newdesc;
            if(mysqli_stmt_execute($stmt)){
                header("location: viewauction.php?id=$id");
            } else {
                echo "Įvyko klaida, bandykite dar kartą";
            }
        }
    }
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html>
<?php
include "includes/navbar.php";
?>
<body>
<br>
<div class="container">
    <h2>Aukciono redagavimas</h2>
    <form method='post'>
        <div class="form-group col-sm-12">
            <label>Pavadinimas</label>
            <input type='text' name='name' class="form-control <?php (!empty($name_err)) ? 'is-invalid' : ''; ?> input-md" value="<?php echo $name ?>">
            <span class="invalid-feedback"><?php echo $name_err; ?></span>
        </div>
        <div class="row">
            <div class="form-group col-sm-6">
                <label>Pabaigos data:</label>
                <input type='datetime-local' name='enddate' min="<?php echo date("Y-m-d\TH:i") ?>"
                       class="form-control <?php (!empty($date_err)) ? 'is-invalid' : ''; ?> input-md" value="<?php echo $enddate ?>">
                <span class="invalid-feedback"><?php echo $date_err ?></span>
            </div>
            <div class="form-group col-sm-6">
                <label for="minprice" class="control-label">Minimali kaina:</label>
                <input name='minprice' type='number' step="0.01" min="0" class="form-control input-md" value="<?php echo $minprice ?>">
            </div>
        </div>
        <div class="form-group col-sm-12">
            <label for="description" class="control-label">Aprašymas:</label>
            <textarea name='description' type='text' class="form-control input-lg"><?php echo $desc ?></textarea>
        </div>
        <div class="form-group col-sm-2">
            <input type='submit' name='ok' value='Atnaujinti' class="btn btn-primary">
        </div>
    </form>
    <?php
    echo "<div class='text-center'><a class='btn btn-primary' href='imageupload.php?id=".$id."'>Nuotraukų įkėlimas</a></div>";
    ?>
</div>
</body>
</html>