<!-- NAVBAR -->
<nav class="navbar">
    <div class="inner-width">
        <div class="navbar-menu">
            <a href="home.php">HOME</a>
            <form action="home.php" method="GET" style="display: inline;">
                <select name="aggiorna">
                    <option value="#" selected>AGGIORNA</option>
                    <option value="sito1">Sito 1</option>
                    <option value="sito2">Sito 2</option>
                    <option value="sito3">Sito 3</option>
                    <option value="sito4">Sito 4</option>
                    <option value="sito5">Sito 5</option>
                    <option value="sito6">Sito 6</option>
                    <option value="sito7">Sito 7</option>
                    <option value="tutto">Tutto</option>
                </select>
            </form>
            <form action="list.php" method="POST" style="display: inline;">
                <input type="submit" class="btn btn-primary" value="CATALOGO" name="catalogo"> 
            </form>
            <a href="register.php">REGISTRATI</a>
            <a href="login.php">ACCEDI</a>
        </div>
    </div>
</nav>
<!-- 
<nav class="navbar navbar-expand-lg">
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="home.php">HOME</a>
            </li>
            <form action="list.php" method="POST" style="display: inline;">
                <li class="nav-item">
                    <input type="submit" class="btn btn-primary" value="CATALOGO" name="catalogo">
                </li> 
            </form>
            <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">AGGIORNA</a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="#">Sito 1</a>
                <a class="dropdown-item" href="#">Sito 2</a>
                <a class="dropdown-item" href="#">Sito 3</a>
                <a class="dropdown-item" href="#">Sito 4</a>
                <a class="dropdown-item" href="#">Sito 5</a>
                <a class="dropdown-item" href="#">Sito 6</a>
                <a class="dropdown-item" href="#">Sito 7</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#">Tutti</a>
            </div>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="register.php">REGISTRATI</a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="login.php">ACCEDI</a>
            </li>
        </ul>
        <form action="list.php" method="GET">
            <div class="input-group">
                <input type="search" class="form-control rounded" placeholder="Cerca il prodotto..." aria-label="Search" aria-describedby="search-addon" id="SearchBar" name="SearchBar" />
                <input type="submit" class="btn btn-outline-primary" id="SearchButton" name="SearchButton" value="CERCA">
            </div>
        </form>
    </div>
</nav>
-->

<script>
    $(function () {
        $(document).scroll(function () {
            var $nav = $(".navbar");
            $nav.toggleClass('scrolled', $(this).scrollTop() > $nav.height());
        });
    });
</script>