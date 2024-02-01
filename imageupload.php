<?php
require "includes/config.php";
session_start();

$id=$_GET['id'];
$image_err="";
$target_dir="images/";
$uploadOk=1;
if (($_SESSION['userlvl'] < 3))   { header("Location: logout.php");exit;}
if($_SERVER["REQUEST_METHOD"]=="POST"){
    $imagefile=basename($_FILES['upfile']["name"]);
    $target_file=$target_dir.$imagefile;
    $imageFileType=strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    if(isset($_POST["submit"])){
        $check=getimagesize($_FILES["upfile"]["tmp_name"]);
        if($check!=false){
            $uploadOk=1;
        }
        else{
            $image_err="Failas yra ne nuotrauka";
            $uploadOk=0;
        }
    }
    if(file_exists($target_file)){
        $index=0;
        $target_file=$target_dir.$index.$imagefile;
        while(true){
            if(!file_exists($target_file)){
                break;
            }
            $target_file=$target_dir.$index.$imagefile;
            $index++;
        }
    }
    if($_FILES["upfile"]["size"]>500000000){
        $image_err="Failas yra per didelis";
        $uploadOk=0;
    }
    if($imageFileType!='jpg' && $imageFileType!='png' && $imageFileType!='jpeg'){
        $image_err="Priimami failų formatai: JPG, PNG, JPEG";
        $uploadOk=0;
    }
    if($uploadOk!=0){
        if(move_uploaded_file($_FILES["upfile"]["tmp_name"], $target_file)){
            $sql="INSERT INTO images (filename, auction) VALUES ('$target_file', '$id')";
            mysqli_query($link, $sql);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="lt">
<head>
    <?php include "includes/navbar.php"; ?>
    <style>
        .custom-file-input ~ .custom-file-label::after {
            content: "Pasirinkti";
        }
         #images{
             font-family: Arial; border-collapse: collapse; width: 70%;
         }
        #images td{
            border: 1px solid black; padding: 8px;
        }
    </style>
</head>
<body>
<div class="container">
    <form method="post" enctype='multipart/form-data'>
        <div class="form-group col-sm-12">
            <br><h3 style="text-align: center">Nuotraukų talpinimas</h3><br>
            <div class="custom-file" id="customFile">
                <input type="file" class="custom-file-input" name="upfile" id="upfile" value="Pasirinkite failą">
                <label class="custom-file-label" for="files">Pasirinkite failą</label>
            </div>
            <span class="invalid-feedback"><?php echo $image_err; ?></span>
            <div class='text-center'><br><input type="submit" class="btn btn-primary" value="Įkelti" name="submit"></div>
        </div>
    </form>
</div>
<br>
<div class="container">
    <?php
    echo "<form method='post' action='deleteimage.php?id=$id' name='images'>";
    $query="SELECT * FROM images WHERE auction=$id";
    $result=mysqli_query($link, $query);
    if($result!=false && mysqli_num_rows($result) > 0){
        echo "<h3 style='text-align: center'>Nuotraukų šalinimas</h3>";
        echo
        "<table style='margin: 0px auto;' id='images'><tr>
        <td><b>Nuotrauka</b></td>
        <td><b>Šalinti?</b></td>
     </tr>";
        while($row=mysqli_fetch_assoc($result)){
            $filename=$row['filename'];
            $img=$row['id'];
            echo "<tr><td><img src='$filename' style='margin: auto' height='250px'></td>
                <td><input type=\"checkbox\" name=\"naikinti_".$img."\"></td></tr>";
        }
        echo "</table>";
        echo "<div class='text-center'><br><input type='submit' class='btn btn-primary' value='Vykdyti'></div>";
    }
    ?>

</div>
</body>
</html>