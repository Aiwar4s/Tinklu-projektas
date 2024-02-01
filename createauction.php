<?php
require_once "includes/config.php";
session_start();
if (($_SESSION['userlvl'] < 3))   { header("Location: logout.php");exit;}
$name=$name_err="";
$date=$date_err="";
$price=0.00;
$description="";
if($_SERVER["REQUEST_METHOD"]=="POST"){
    if(empty(($_POST["name"]))){
        $name_err="Įveskite pavadinimą";
    } else{
        $name=trim($_POST["name"]);
    }
    if(empty(trim($_POST["enddate"]))){
        $date_err="Pasirinkite datą";
    } else{
        $date=trim($_POST["enddate"]);
    }
    if(!empty(trim($_POST["minprice"]))){
        $price=trim($_POST["minprice"]);
    }
    if(!empty(trim($_POST["description"]))){
        $description=trim($_POST["description"]);
    }
    if(empty($name_err) && empty($date_err)){
        $sql="INSERT INTO auctions (name, minprice, enddate, description, seller) VALUES (?, ?, ?, ?, ?)";
        if($stmt=mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "sdssi", $param_name, $param_price, $param_date, $param_desc, $param_seller);
            $param_name=$name;
            $param_price=$price;
            $param_date=$date;
            $param_desc=$description;
            $param_seller=$_SESSION["id"];
            if(mysqli_stmt_execute($stmt)){
                header("location: index.php");
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
    <h2>Aukciono kūrimas</h2>
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
                       class="form-control <?php (!empty($date_err)) ? 'is-invalid' : ''; ?> input-md" value="<?php echo $date ?>">
                <span class="invalid-feedback"><?php echo $date_err ?></span>
            </div>
            <div class="form-group col-sm-6">
                <label for="minprice" class="control-label">Minimali kaina:</label>
                <input name='minprice' type='number' step="0.01" min="0" class="form-control input-md">
            </div>
        </div>
        <div class="form-group col-sm-12">
            <label for="description" class="control-label">Aprašymas:</label>
            <textarea name='description' type='text' class="form-control input-lg"></textarea>
        </div>
        <div class="form-group col-sm-2">
            <input type='submit' name='ok' value='siųsti' class="btnbtn-default">
        </div>
    </form>
</div>
</body>
</html>