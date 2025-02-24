<!-- A black horizontal navbar that becomes vertical on small screens -->
<nav class="navbar navbar-expand-lg navbar-dark bg-black">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="dashboard.php" style="color: gold;">Dashboard EI-TI</a>
        <div class="collapse navbar-collapse" id="navbarTogglerDemo03">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="dashboard.php" style="color: gold;">Home</a>
                </li>
                <?php
                if ($permissoes == "admin") {
                    ?>
                    <li class="nav-item">
                        <a class="nav-link" href="historico.php" style="color: gold;">Histórico</a>
                    </li>
                    <?php
                }
                ?>
            </ul>
            <div class="d-flex">
                <a href="logout.php" class="btn btn-outline-warning" style="color: gold;">Logout</a>
            </div>
        </div>
    </div>
</nav>
<br><br>
