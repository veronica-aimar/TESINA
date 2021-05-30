<?php

include('../DB/Utente.php');
include('../DB/ManagerUtente.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    session_start();
    $_SESSION["idPassword"] = $_GET['id'];
} else {
    if (isset($_POST['cambiaPassword'])) {
        $password = $_POST['password'];
        $password2 = $_POST['password2'];

        if ($password != $password2) {
            Utente::popUp('Le password devono coincidere!');
        }

        $arrayPassword = str_split($password);
        $lettereMaiuscole = false;
        $lettereMinuscole = false;
        foreach ($arrayPassword as $lettera) {
            if (ctype_upper($lettera) == True) {
                $lettereMaiuscole = True;
            }

            if (ctype_lower($lettera) == True) {
                $lettereMinuscole = True;
            }
        }

        if (strlen($password) < 8 || !preg_match('~[0-9]+~', $password) || !preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $password) || $lettereMaiuscole == False || $lettereMinuscole == False) {
            Utente::popUp('La password deve essere lunga almeno 8 caratteri, deve contenere almeno una lettera maiuscola, una lettera minuscola, un numero e un carattere speciale');
        }

        $utente = ManagerUtente::readById($_SESSION["idPassword"]);
        $utente->setPassword($password);
        ManagerUtente::update($utente);
        Utente::popUp('Password cambiata con successo! Ti stiamo reindirizzando alla pagina di login');
        header('Refresh: 3; URL=login.php');
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.3.0/mdb.min.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="../SRC/CSS/style.css">

    <title>CAMBIA PASSWORD</title>
</head>

<body>
    <?php include '../SRC/PARTIALS/navbar.php'; ?>
    <div align="center">
        <form action="cambiaPassword.php" method="POST" id="loginForm">
            <div class="form-outline mb-4">
                <input type="password" class="form-control" id="password" name="password" />
                <label class="form-label" for="email">Nuova Password</label>
            </div>
            <div class="form-outline mb-4">
                <input type="password" class="form-control" id="password2" name="password2" />
                <label class="form-label" for="email">Ripeti la Nuova Password</label>
            </div>
            <input type="submit" class="btn btn-primary btn-block mb-4" value="CAMBIA PASSWORD" name="cambiaPassword" id="cambiaPassword">
        </form>
    </div>
</body>

</html>