<?php
session_start();

include "includes/config.php";

$sql="SELECT * FROM auctions";
$result = mysqli_query($link, $sql);
if (!$result || (mysqli_num_rows($result) < 1))
{echo "Klaida skaitant lentelę users"; exit;}

while($row=mysqli_fetch_assoc($result)){
    $id=$row['id'];
    $naikinti=isset($_POST['naikinti_'.$id]);
    if($naikinti){
        $sql="DELETE FROM auctions WHERE id='$id'";
        if (!mysqli_query($link, $sql)) {
            echo " DB klaida šalinant vartotoją: " . $sql . "<br>" . mysqli_error($link);
            exit;}
    }
}
header("Location:admin.php");exit;
?>