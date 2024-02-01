<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
    <title>Aukcionų portalas</title>
</head>
<body>
<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
    <a class="navbar-brand" href="index.php">Aukcionų portalas</a>
    <ul class="navbar-nav">
        <?php
        if($_SESSION["userlvl"]==1){
            echo
            "<li class='nav-item'>
                <a class='nav-link' href='login.php'>Prisijungimas</a>
            </li>
            <li class='nav-item'>
                <a class='nav-link' href='register.php'>Registracija</a>
            </li>";
        } elseif ($_SESSION["userlvl"]>3){
            echo
            "<li class='nav-item'>
                <a class='nav-link' href='createauction.php'>Sukurti aukcioną</a>
            </li>
            <li class='nav-item'>
                <a class='nav-link' href='myauctions.php'>Mano aukcionai</a>
            </li>
            <li class='nav-item'>
                <a class='nav-link' href='admin.php'>Administravimas</a>
            </li>
            <li class='nav-item'>
                <a class='nav-link' href='logout.php'>Atsijungti</a>
            </li>";
        }
        elseif ($_SESSION["userlvl"]>2){
            echo
            "<li class='nav-item'>
                <a class='nav-link' href='createauction.php'>Sukurti aukcioną</a>
            </li>
            <li class='nav-item'>
                <a class='nav-link' href='myauctions.php'>Mano aukcionai</a>
            </li>
            <li class='nav-item'>
                <a class='nav-link' href='logout.php'>Atsijungti</a>
            </li>";
        } elseif ($_SESSION["userlvl"]>1){
            echo
            "<li class='nav-item'>
                <a class='nav-link' href='myauctions.php'>Mano aukcionai</a>
            </li>
            <li class='nav-item'>
                <a class='nav-link' href='logout.php'>Atsijungti</a>
            </li>";
        }
        ?>
    </ul>
</nav>
</body>
</html>