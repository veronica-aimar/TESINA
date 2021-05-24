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

        if ($nome == '' || $cognome == '' || $email == '' || $username == '' || $password == '') {
            Utente::popUp('Compila tutti i campi');
        } else {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $utente = new Utente(-1, $nome, $cognome, $telefono, $email, $username, $hash);
            $id = ManagerUtente::create($utente);
            if ($id == -1) {
                Utente::popUp('Utente già esistente');
            } else {
                Utente::popUp('Ben fatto!');
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

        <input type="submit" class="btn btn-primary btn-block mb-4" value="REGISTRATI" id="registrati" name="registrati">
    </form>
</body>

</html>