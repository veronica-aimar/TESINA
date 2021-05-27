<?php
    include('../DB/Farmaco.php');
    include('../DB/ManagerFarmaco.php');
    include('../DB/ManagerOrdini.php');

    if($_SERVER['REQUEST_METHOD'] === 'GET') {
        // LETTURA ORDINI UTENTE
        $idUtente = $_GET['id'];
        $lista_like = ManagerOrdini::readAll($idUtente, 0);
    } else {
        // Chiusura della sessione
        session_start();
        session_destroy();
        header('Location: home.php');
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="../SRC/CSS/style.css">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet" />
    <title>YOUR PAGE</title>
</head>
<body>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    
    <?php
        session_start();
        include('../SRC/PARTIALS/userNavbar2.php');
        
        if($lista_like != null) {
            echo '<br><br><br>
                <h1>MI PIACE</h1>
                <div class="row" id="carteProdotti">';

            foreach ($lista_like as $like) {
                $farmaco = ManagerFarmaco::readById($like->getMinsan());
                Farmaco::createCard($farmaco, 1);
            }

            echo '</div>';
        } else {
            echo '<div class="container">
                    <h1>MI PIACE</h1>
                    <div class="container-fluid mt-100" id="empty">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-sm-12 empty-cart-cls text-center"> <img src="../SRC/IMG/likeVuoti.png" width="130" height="130" class="img-fluid mb-4 mr-3">
                                    <h3><strong>Non hai ancora aggiunto farmaci<br>ai post che ti piacciono</strong></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';
        }
?>
</body>
</html>