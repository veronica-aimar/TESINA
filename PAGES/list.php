<?php
include('../DB/Farmaco.php');
include('../DB/Utente.php');
include('../DB/ManagerFarmaco.php');

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['SearchButton'])) {
        $prodotto = $_GET['SearchBar'];

        // Controllo che sia stato inserito qualcosa nella barra di ricerca
        if ($prodotto == '') {
            header('Location: home.php');
        } else {
            $lista_prodotti = ManagerFarmaco::readAll($prodotto);
            if ($lista_prodotti == null) {
                echo '<div class="container">
                    <h1>OPS...</h1>
                    <div class="container-fluid mt-100" id="empty">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-sm-12 empty-cart-cls text-center"> <img src="../SRC/IMG/nonTrovato.png" width="130" height="130" class="img-fluid mb-4 mr-3">
                                    <h3><strong>Non è stato trovato nessun articolo<br>che possa soddisfare la tua ricerca</strong></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';
            }
        }
    }

    if (isset($_GET['all'])) {
        $lista_prodotti = ManagerFarmaco::readAll('');
    }

    if (isset($_GET['catalogo'])) {
        $lista_prodotti = ManagerFarmaco::readAll('');
    }
} else {
    if (isset($_POST['like'])) {
        if (!isset($_SESSION["idUtente"])) {
            header("Location: login.php");
        } else {
            $like = new Ordine($_SESSION["idUtente"], $_POST['minsan'], 0, 1);
        }
    }

    // Aggiunta al carrello
    if (isset($_POST['carrello'])) {
        if (!isset($_SESSION["idUtente"])) {
            header("Location: login.php");
        } else {
            $carrello = new Ordine($_SESSION["idUtente"], $_POST['minsan'], 1, $_POST['quantita']);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="../SRC/CSS/style.css">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet" />
    <title>PRODOTTI</title>
</head>

<body>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

    <?php
    if (!isset($_SESSION['idUtente'])) {
        include '../SRC/PARTIALS/navbar.php';
    } else {
        include '../SRC/PARTIALS/loginNavbar.php';
    }
    ?>


    <?php
    if ($lista_prodotti != null) {
        echo '<br><br><br>
            <h1>RISULTATI DELLA RICERCA</h1>
            <br>
            <div class="row" id="carteProdotti">';

        foreach ($lista_prodotti as $farmaco) {
            Farmaco::createCard($farmaco, 3, '');
        }

        echo '</div>';
    }
    ?>
</body>

</html>