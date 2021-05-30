<!-- NAVBAR -->
<nav class="navbar">
    <div class="inner-width">
        <div class="navbar-menu">
            <a href="home.php">HOME</a>
            <form action="list.php" method="GET" style="display: inline;">
                <input type="submit" class="btn btn-primary" value="CATALOGO" name="catalogo">
            </form>
            <a href="register.php">REGISTRATI</a>
            <a href="login.php">ACCEDI</a>
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