<?php
session_start();

include "includes/config.php";

$id=$_GET["id"];
$sql="SELECT * FROM images WHERE auction='$id'";
$result = mysqli_query($link, $sql);
if (!$result || (mysqli_num_rows($result) < 1))
{echo "Klaida skaitant lentelę users"; exit;}

while($row=mysqli_fetch_assoc($result)){
    $img=$row['id'];
    $file=$row['filename'];
    $naikinti=isset($_POST['naikinti_'.$img]);
    if($naikinti){
        unlink($file);
        $sql="DELETE FROM images WHERE id='$img'";
        if (!mysqli_query($link, $sql)) {
            echo " DB klaida šalinant vartotoją: " . $sql . "<br>" . mysqli_error($link);
            exit;}
    }
}
header("Location:imageupload.php?id='$id'");exit;
?>