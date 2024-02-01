<?php
require_once "includes/config.php";
$username=$email=$password="";
$username_err=$email_err=$password_err="";

if($_SERVER["REQUEST_METHOD"]=="POST"){
    if(empty(trim($_POST["username"]))){
        $username_err="Įveskite naudotojo vardą";
    }
    else{
        $sql="SELECT userid FROM users WHERE username = ?";
        if($stmt=mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            $param_username=trim($_POST["username"]);
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt)==1){
                    $username_err="Naudotojo vardas užimtas";
                }
                else{
                    $username=trim($_POST["username"]);
                }
            }
        }
        mysqli_stmt_close($stmt);
    }
    if(empty(trim($_POST["password"]))){
        $password_err = "Įveskite slaptažodį";
    }
    else{
        $password = trim($_POST["password"]);
    }
    if(empty(trim($_POST["email"]))){
        $email_err_err = "Įveskite el. paštą";
    }
    else{
        $email = trim($_POST["email"]);
    }

    if(empty($username_err) && empty($password_err) && empty($email_err)){
        $sql="INSERT INTO users (username, password, email) VALUES (? ,?, ?)";
        if($stmt=mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "sss", $param_username, $param_password, $param_email);
            $param_username=$username;
            $param_password=password_hash($password, PASSWORD_DEFAULT);
            $param_email=$email;
            if(mysqli_stmt_execute($stmt)){
                header("location: login.php");
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
<html lang="lt">
<?php
include "includes/header.php";
?>
<body>
<div class="container">
    <h2>Registracija</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <label>Vartotojo vardas</label>
            <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username ?>">
            <span class="invalid-feedback"><?php echo $username_err; ?></span>
        </div>
        <div class="form-group">
            <label>Slaptažodis</label>
            <input type="password" name="password" class="form-control <?php echo (!empty($password_err_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password ?>">
            <span class="invalid-feedback"><?php echo $password_err; ?></span>
        </div>
        <div class="form-group">
            <label>El. paštas</label>
            <input type="email" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email ?>">
            <span class="invalid-feedback"><?php echo $email_err; ?></span>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Registruoti">
        </div>
        <p>Jau turite paskyrą? <a href="login.php">Prisijunkite</a>.</p>
    </form>
</div>
</body>
</html>
