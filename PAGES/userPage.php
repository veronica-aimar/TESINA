<?php
include('../DB/Utente.php');
include('../DB/ManagerOrdiniUtente.php');
include('../DB/Farmaco.php');
include('../DB/ManagerFarmaco.php');

if($_SERVER['REQUEST_METHOD'] === 'GET') {
    // LETTURA ORDINI UTENTE
    $idUtente = $_GET['id'];
    $lista_ordini = ManagerOrdiniUtente::readById($idUtente);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>YOUR PAGE</title>
</head>
<body>
<h1>MI PIACE</h1>
    <div class="row">
        <?php
            $lista_prodotti = ManagerFarmaco::readAll();
            foreach ($lista_ordini as $ordine) {
                if ($ordine->getTipoOrdine() == 0) {
                    $farmaco = ManagerFarmaco::readById($ordine->getMinsan());
                    Farmaco::createUserCard($farmaco);
                }
            }
        ?>
    </div>
</body>
</html>