<?php
include('./DB/Utente.php');
include('./DB/ManagerUtente.php');

if (isset($_GET['accedi'])) {
    $username = $_GET['username'];
    $password = $_GET['password'];

    $rs = ManagerUtente::readUser($username, $password);
    if ($rs != false) {
        header('Location: userPage.php?id=' . $rs['id']);
    } else {
        Utente::popUp('Nome utente o password sbagliati');
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.3.0/mdb.min.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="./SRC/CSS/style.css">

    <title>ACCEDI</title>
</head>

<body>
    <?php include './SRC/PARTIALS/navbar.php'; ?>
    <div align="center">
        <form id="loginForm">
            <div class="form-outline mb-4">
                <input type="text" class="form-control" id="username" name="username" />
                <label class="form-label" for="username">Email o Nome utente</label>
            </div>
            <div class="form-outline mb-4">
                <input type="password" class="form-control" id="password" name="password" />
                <label class="form-label" for="password">Password</label>
            </div>

            <div class="row mb-4">
                <div class="col">
                    <a href="#!">Password dimenticata?</a>
                </div>
            </div>

            <input type="submit" class="btn btn-primary btn-block mb-4" value="ACCEDI" name="accedi" id="accedi">
            <div class="text-center">
                <p>Non hai ancora un account? <a href="register.php">REGISTRATI</a></p>
            </div>
        </form>
    </div>
</body>

</html>