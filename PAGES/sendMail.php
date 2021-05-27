<?php
include('../DB/Utente.php');
include('../DB/ManagerUtente.php');

if($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['inviaEmail'])) {
        $email = $_GET['email'];
        
        if($email == ''){
            Utente::popUp('Email non valida');
        } else {
            /* ---------------------- INVIO MAIL ---------------------- */
            $oggetto = 'CAMBIA LA TUA PASSWORD';
            $testo = 'Sembra che tu abbia dimenticato la password... Reimpostala ora!' . '\r\n' . 
                ' Clicca sul seguente LINK"';
            $header = 'From: aimarveronica1@gmail.com' . '\r\n' .
                'X-Mailer: PHP/' . phpversion() . '\r\n' ;

            // PROBLEMA NELL'INVIO
            ini_set('SMTP','myserver');
            ini_set('smtp_port',25);
            ini_set('sendmail_from','aimarveronica1@gmail.com');
            // PROBLEMA NELL'INVIO

            $invio = mail($email, $oggetto, $testo, $header);
            if($invio == true) {
                Utente::popUp('Email inviata con successo!');
            } else {
                Utente::popUp('Non siamo riusciti a mandare la mail');
            }
            /* ---------------------- INVIO MAIL ---------------------- */
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