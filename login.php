<?php
session_start();
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"]==true){
    header("location: index.php");
    exit;
}
require_once "includes/config.php";
$username=$password="";
$username_err=$password_err=$login_err="";
if($_SERVER["REQUEST_METHOD"]=="POST"){
    if(empty(trim($_POST["username"]))){
        $username_err = "Įveskite vartotojo vardą";
    } else{
        $username = trim($_POST["username"]);
    }
    if(empty(trim($_POST["password"]))){
        $password_err = "Įveskite slaptažodį";
    } else{
        $password = trim($_POST["password"]);
    }
    if(empty($username_err) && empty($password_err)){
        $sql="SELECT userid, username, password, userlevel FROM users WHERE username = ?";
        if($stmt=mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            $param_username=$username;
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt)==1){
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password, $userlvl);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            session_start();
                            $_SESSION["loggedin"]=true;
                            $_SESSION["id"]=$id;
                            $_SESSION["username"]=$username;
                            $_SESSION["userlvl"]=$userlvl;
                            header("Location: index.php");
                        } else{
                            $login_err="Neteisingas naudotojo vardas arba slaptažodis";
                        }
                    }
                } else{
                    $login_err="Neteisingas naudotojo vardas arba slaptažodis";
                }
            } else{
                echo "Įvyko klaida, bandykite dar kartą";
            }
            mysqli_stmt_close($stmt);
        }
    }
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="lt">
<?php
include "includes/header.php";
?>
<body>
<div class="container">
    <h2>Prisijungimas</h2>
    <?php
    if(!empty($login_err)){
        echo "<p style='color: red'>$login_err</p>";
    }
    ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <label>Naudotojo vardas</label>
            <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
            <span class="invalid-feedback"><?php echo $username_err; ?></span>
        </div>
        <div class="form-group">
            <label>Slaptažodis</label>
            <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
            <span class="invalid-feedback"><?php echo $password_err; ?></span>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Prisijungti">
        </div>
        <p>Neturite paskyros? <a href="register.php">Registracija</a>.</p>
    </form>
</div>
</body>
</html>
