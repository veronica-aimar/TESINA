<?php
    include('../DB/Farmaco.php');
    include('../DB/Ordine.php');
    include('../DB/ManagerFarmaco.php');
    include('../DB/ManagerOrdini.php');

    session_start();

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Aggiunta ai like
        if(isset($_POST['like'])) {
            if( !isset($_SESSION["idUtente"]) ){
                header("Location: login.php");
            } else {
                $like = new Ordine($_SESSION["idUtente"], $_POST['minsan'], 0, 1);
                ManagerOrdini::create($like);
            }
        }

        // Aggiunta al carrello
        if(isset($_POST['carrello'])) {
            if( !isset($_SESSION["idUtente"]) ){
                header("Location: login.php");
            } else {
                $carrello = new Ordine($_SESSION["idUtente"], $_POST['minsan'], 1, 1);
                ManagerOrdini::create($carrello);
            }
        }
    }
?>
<!doctype html>
<html lang="it">

<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="../SRC/CSS/style.css">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet" />
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>HOME</title>
</head>

<body>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <?php
        if(!isset($_SESSION['idUtente'])) {
            include '../SRC/PARTIALS/navbar.php';
        } else {
            include '../SRC/PARTIALS/loginNavbar.php';
        }
    ?>

    <!-- TESTO HOME -->
    <div class="b1">
        <div class="in1">
            <div class="textBox">
                <h2>TITOLO</h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                    tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                    quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo.</p>
                <form action="list.php" method="GET">
                    <div class="input-group">
                        <input type="search" class="form-control rounded" placeholder="Cerca il prodotto..." aria-label="Search" aria-describedby="search-addon" id="SearchBar" name="SearchBar" />
                        <input type="submit" class="btn btn-outline-primary" id="SearchButton" name="SearchButton" value="CERCA">
                    </div>
                </form>
            </div>
        </div>
        <hr>
    </div> <br><br>

    <!-- OFFERTE DEL GIORNO -->
    <h1>OFFERTE DEL GIORNO</h1>
    <div class="row" id="carteProdotti">
        <?php

        $lista_prodotti = ManagerFarmaco::readAll();
        foreach ($lista_prodotti as $farmaco) {
            $sconto = (($farmaco->getPrezzoVecchio() - $farmaco->getPrezzo()) * 100) / $farmaco->getPrezzoVecchio();
            if ($sconto >= 50) {
                Farmaco::createCard($farmaco, 0);
            }
        }
        ?>
    </div>
</body>

</html>