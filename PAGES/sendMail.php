<?php

include('../DB/Utente.php');
include('../DB/ManagerUtente.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['inviaEmail'])) {
        $email = $_GET['email'];

        if ($email == '') {
            Utente::popUp('Email non valida');
        } else {
            $utente = ManagerUtente::readUser($email);
            if (is_bool($utente)) {
                Utente::popUp('La mail non è presente tra gli utenti registrati... Ti stiamo reindirizzando al modulo di registrazione');
                header('Refresh: 3; URL=register.php');
            } else {
                $to_email = $email;
                $subject = 'CAMBIA PASSWORD';
                $message = '<p>Clicca sul seguente link per cambiare password</p> <br> <a href="cambiaPassword?id="' . $utente . '>CLICCA QUI</a>';
                $headers = 'From: aimarveronica1@gmail.com';
                mail($to_email, $subject, $message, $headers);
                /* ---------------------- INVIO MAIL ---------------------- */
            }
        }
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
        <form action="sendMail.php" method="GET" id="loginForm">
            <div class="form-outline mb-4">
                <input type="email" class="form-control" id="email" name="email" />
                <label class="form-label" for="email">Email</label>
            </div>
            <input type="submit" class="btn btn-primary btn-block mb-4" value="INVIA MAIL" name="inviaEmail" id="inviaEmail">
        </form>
    </div>
</body>

</html>