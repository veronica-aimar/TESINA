<?php
include('../DB/Utente.php');
include('../DB/ManagerUtente.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['registrati'])) {
        $nome = $_POST['nome'];
        $cognome = $_POST['cognome'];
        $telefono = $_POST['telefono'];
        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $password2 = $_POST['password2'];

        if ($nome == '' || $cognome == '' || $email == '' || $username == '' || $password == '') {
            Utente::popUp('Compila tutti i campi');
        } else {
            if ($password != $password2) {
                Utente::popUp('Le password devono coincidere!');
            }

            if (ManagerUtente::readUser($username) != false) {
                Utente::popUp('Nome utente non disponibile');
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


            $hash = password_hash($password, PASSWORD_BCRYPT);
            $utente = new Utente(-1, $nome, $cognome, $telefono, $email, $username, $hash);
            $id = ManagerUtente::create($utente);
            if ($id == -1) {
                Utente::popUp('Utente già esistente');
            } else {
                Utente::popUp('Ben fatto! Ti stiamo reindirizando alla pagina di login');
                header('Refresh: 3; URL=login.php');
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

    <title>ACCEDI</title>
</head>

<body>
    <?php include '../SRC/PARTIALS/navbar.php'; ?>
    <form action="register.php" method="POST" id="loginForm">
        <div class="row mb-4">
            <div class="col">
                <div class="form-outline">
                    <input type="text" class="form-control" id="nome" name="nome" />
                    <label class="form-label" for="nome">Nome</label>
                </div>
            </div>
            <div class="col">
                <div class="form-outline">
                    <input type="text" class="form-control" id="cognome" name="cognome" />
                    <label class="form-label" for="cognome">Cognome</label>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col">
                <div class="form-outline">
                    <input type="tel" class="form-control" id="telefono" name="telefono" />
                    <label class="form-label" for="telefono">Telefono</label>
                </div>
            </div>
            <div class="col">
                <div class="form-outline">
                    <input type="email" class="form-control" id="email" name="email" />
                    <label class="form-label" for="email">Email</label>
                </div>
            </div>
        </div>

        <div class="form-outline mb-4">
            <input type="text" class="form-control" id="username" name="username" />
            <label class="form-label" for="username">Username</label>
        </div>
        <div class="form-outline mb-4">
            <input type="password" class="form-control" id="password" name="password" />
            <label class="form-label" for="password">Password</label>
        </div>
        <div class="form-outline mb-4">
            <input type="password" class="form-control" id="password2" name="password2" />
            <label class="form-label" for="password2">Ripeti la Password</label>
        </div>

        <input type="submit" class="btn btn-primary btn-block mb-4" value="REGISTRATI" id="registrati" name="registrati">
    </form>
</body>

</html>