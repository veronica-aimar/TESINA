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

    <!-- TESTO PRINICIPALE -->
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
    </div>

    <!-- OFFERTE -->
    <h3>OFFERTE DEL GIORNO</h3>
    <div class="row">
        <?php
        include('./DB/Farmaco.php');
        include('./DB/DBManager.php');

        $rs = DBManager::readAll();
        foreach ($rs as $farmaco) {
            $contatore = 0;
            $sconto = (($farmaco['prezzoVecchio'] - $farmaco['prezzo']) * 100 ) / $farmaco['prezzoVecchio'];
            if ($sconto >= 50) {
            
                if($contatore == 0) {
                    echo '<div class="container">
                        <div class="row">';
                }

                echo '<div class="col-sm">
                    <!-- Card -->
                    <div class="card">
                        <div class="view zoom overlay">
                            <img class="img-fluid w-100" src="' . $farmaco['img'] . '" alt="Sample">
                            <h4 class="mb-0"><span class="badge badge-primary badge-pill badge-news">SCONTO</span></h4>
                        </div>

                        <div class="card-body text-center">
                            <h5>' . $farmaco['nomeProdotto'] . '</h5>
                            <!-- CATEGORIA -->
                            <p class="small text-muted text-uppercase mb-2">' . $farmaco['categoria'] . '</p>
                            <hr>
                            <h6 class="mb-3">
                                <!-- PREZZO SCONTATO / VECCHIO PREZZO -->
                                <span class="text-danger mr-1">€' . $farmaco['prezzo'] . '</span>
                                <span class="text-grey"><s>€' . $farmaco['prezzoVecchio'] . '</s></span>
                            </h6>

                            <button type="button" class="btn btn-primary btn-sm mr-1 mb-2">
                                <i class="fas fa-shopping-cart pr-2"></i>AGGIUNGI AL CARRELLO
                            </button>
                            <button type="button" class="btn btn-light btn-sm mr-1 mb-2">
                                <i class="fas fa-info-circle pr-2"></i>DETTAGLI
                            </button>
                            <button type="button" class="btn btn-danger btn-sm px-3 mb-2 material-tooltip-main" data-toggle="tooltip" data-placement="top" title="Add to wishlist">
                                <i class="far fa-heart"></i>
                            </button>

                        </div>
                    </div>
                    </div>';

                    $contatore++;
                    if($contatore == 3) {
                        echo '</div></div>;';
                        $contatore = 0;
                    }
            }
        }
        ?>
    </div>
</body>

</html>