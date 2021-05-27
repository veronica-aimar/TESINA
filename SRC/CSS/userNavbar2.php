<!-- NAVBAR -->
<nav class="navbar">
    <div class="inner-width">
        <div class="navbar-menu">
            <a href="home.php">HOME</a>
            <a href="carrello.php?id=<?php echo $_SESSION['idUtente']; ?>">CARRELLO</a>
            <form action="userPage.php" method="POST" style="display: inline;">
                <a href="#">ESCI</a> 
            </form>
        </div>
    </div>
</nav>