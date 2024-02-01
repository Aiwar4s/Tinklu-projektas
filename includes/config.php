<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'stud');
define('DB_PASSWORD', 'stud');
define('DB_NAME', 'itprojektas');

$user_roles=array(      // vartotojų rolių vardai lentelėse ir  atitinkamos userlevel reikšmės
    "Administratorius"=>"4",
    "Pardavėjas"=>"3",
    "Pirkėjas"=>"2");

$link=mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
if($link==false){
    die("ERROR: could not connect. ".mysqli_connect_error());
}
?>