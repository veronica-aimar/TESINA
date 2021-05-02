<!doctype html>
<html lang="it">

<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="./SRC/CSS/style.css">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet"/>
    <!-- MDB 
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.3.0/mdb.min.css" rel="stylesheet"/> -->
    <title>HOME</title>
</head>

<body>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <?php include './SRC/PARTIALS/navbar.php'; ?>

    <!-- TESTO HOME -->
    <div class="b1">
        <div class="in1">
            <div class="textBox">
                <h2>TITOLO</h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                    tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                    quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo.</p>
                <div class="input-group">
                    <input type="search" class="form-control rounded" placeholder="Cerca il prodotto..." aria-label="Search" aria-describedby="search-addon" />
                    <button type="button" class="btn btn-outline-primary">CERCA</button>
                </div>
            </div>
        </div>
        <hr>
    </div>

    <!-- OFFERTE DEL GIORNO -->
    <h1>OFFERTE DEL GIORNO</h1>
    <div class="row">
        <?php
        include('./DB/Farmaco.php');
        include('./DB/DBManager.php');

        $rs = DBManager::readAll();
        echo '<div class="container">';
        foreach ($rs as $farmaco) {
            $sconto = (($farmaco['prezzoVecchio'] - $farmaco['prezzo']) * 100 ) / $farmaco['prezzoVecchio'];
            if ($sconto >= 50) {
                echo '<div class="card">
                        <div class="view overlay z-depth-2 rounded">
                            <a href="#">
                                <img class="img-fluid w-100" src="' . $farmaco['img'] . '" alt="' . $farmaco['nomeProdotto'] . '">
                            </a>
                        </div>

                        <div class="text-center pt-4">
                            <h6 class="mb-3">
                                <span class="text-danger mr-1">€' . $farmaco['prezzo']/100 . '</span>
                                <span class="text-grey"><s>€' . $farmaco['prezzoVecchio']/100 . '</s></span>
                            </h6>
                            <h5>' . $farmaco['nomeProdotto'] . '</h5>
                            <p class="small text-muted text-uppercase mb-2">' . $farmaco['categoria'] . '</p>

                            <button type="button" class="btn btn-primary btn-sm mr-1 mb-2">+ CARRELLO</button>
                            <button type="button" class="btn btn-danger btn-sm px-3 mb-2"><i class="far fa-heart"></i></button>
                        </div>
                    </div>';
            }
        }
        ?>
    </div>
</body>

</html>