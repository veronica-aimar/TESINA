<!-- NAVBAR -->
<nav class="navbar">
    <div class="inner-width">
        <div class="navbar-menu">
            <a href="home.php">HOME</a>
            <a href="userPage.php?id=<?php echo $_SESSION['idUtente']; ?>">PREFERITI</a>
            <form action="list.php" method="GET" style="display: inline;">
                <input type="submit" class="btn btn-primary" value="CATALOGO" name="catalogo">
            </form>
            <form action="userPage.php" method="POST" style="display: inline;">
                <input type="submit" class="btn btn-primary" value="ESCI" name="esci">
            </form>
        </div>
    </div>
</nav>

<script>
    $(function() {
        $(document).scroll(function() {
            var $nav = $(".navbar");
            $nav.toggleClass('scrolled', $(this).scrollTop() > $nav.height());
        });
    });
</script>