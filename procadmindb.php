<?php
// procadmindb.php   admino nurodytus pakeitimus padaro DB
// $_SESSION['ka_keisti'] kuriuos vartotojus, $_SESSION['pakeitimai'] į kokį userlevel

session_start();

include "includes/config.php";

$i=0;$levels=$_SESSION['pakeitimai'];
foreach ($_SESSION['ka_keisti'] as $user)
{$level=$levels[$i++];
    if ($level == -1) {
        $sql="DELETE FROM users WHERE username='$user'";
        if (!mysqli_query($link, $sql)) {
            echo " DB klaida šalinant vartotoją: " . $sql . "<br>" . mysqli_error($link);
            exit;}
    } else {
        $sql="UPDATE users SET userlevel='$level' WHERE username='$user'";
        if (!mysqli_query($link, $sql)) {
            echo " DB klaida keičiant vartotojo įgaliojimus: " . $sql . "<br>" . mysqli_error($link);
            exit;}
    }}
header("Location:admin.php");exit;
