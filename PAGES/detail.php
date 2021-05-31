<?php
include('../DB/Farmaco.php');
include('../DB/Ordine.php');
include('../DB/ManagerFarmaco.php');
include('../DB/ManagerOrdini.php');

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $minsan = $_GET['minsan'];
    $farmaco = ManagerFarmaco::readById($minsan);
} else {
    if (isset($_POST['like'])) {
        if (!isset($_SESSION["idUtente"])) {
            header("Location: login.php");
        } else {
            $compra = new Ordine($_SESSION["idUtente"], $_POST['minsan'], 0, $_POST['quantita']);
            ManagerOrdini::create($compra);
            header('Location: userPage.php?id=' . $_SESSION['idUtente']);
        }
    }

    if (isset($_POST['carrello'])) {
        if (!isset($_SESSION["idUtente"])) {
            header("Location: login.php");
        } else {
            $carrello = new Ordine($_SESSION["idUtente"], $_POST['minsan'], 1, $_POST['quantita']);
            ManagerOrdini::create($carrello);
            header('Location: carrello.php?id=' . $_SESSION['idUtente']);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="../SRC/CSS/style.css">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet" />
    <!-- MDB 
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.3.0/mdb.min.css" rel="stylesheet"/> -->
    <title></title>
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

    <div class="detailContainer">
        <section class="mb-5">
            <div class="row">
                <div class="col-md-6">
                    <div class="mdb-lightbox">
                        <div class="row product-gallery mx-1">
                            <figure class="view overlay rounded z-depth-1 main-img">
                                <img src="<?php echo $farmaco->getImg(); ?>" class="img-fluid z-depth-1">
                            </figure>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <h2><?php echo $farmaco->getNomeProdotto(); ?></h2>
                    <p class="mb-2 text-muted text-uppercase small"><?php echo $farmaco->getCategoria(); ?></p>
                    <p><span class="mr-1"><strong>PREZZO: &euro;<?php echo $farmaco->getPrezzo() / 100; ?></strong></span></p><?php echo $farmaco->getDescrizione(); ?></p>
                    <hr>
                    <form action="detail.php" method="POST">
                        <div class="table-responsive mb-2">
                            <table class="table table-sm table-borderless">
                                <tbody>
                                    <tr>
                                        <td class="pl-0 pb-0 w-25">QUANTITA'</td>
                                    </tr>
                                    <tr>
                                        <td class="pl-0">
                                            <div class="def-number-input number-input safari_only mb-0">
                                                <button onclick="this.parentNode.querySelector('input[type=number]').stepDown()" class="minus" type="button">-</button>
                                                <input class="quantita" min="0" name="quantita" id="quantita" value="1" type="number">
                                                <button onclick="this.parentNode.querySelector('input[type=number]').stepUp()" class="plus" type="button">+</button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <input type="text" hidden value="<?php echo $farmaco->getMinsan(); ?>" name="minsan">
                        <input type="submit" id="like" class="btn btn-primary btn-md mr-1 mb-2" name="like" id="like" value="PREFERITI">
                        <input type="submit" id="carrello" class="btn btn-light btn-md mr-1 mb-2 fa" name="carrello" id="carrello" value="&#xf07a;CARRELLO">
                    </form>
                </div>
            </div>
        </section>
    </div>
</body>

</html>