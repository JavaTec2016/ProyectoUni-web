<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary sticky-top fix" id="nav-feed" style="width: 100%;">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="assets/img/cogs.png" alt="Bootstrap" width="30" style="margin-right: 1rem; margin-left: 1rem;">
                MENU PRINCIPAL
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarText">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#h">Principio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Lista detallada</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Filtrar</a>
                    </li>
                </ul>

                <a class="navbar-text me-3" href="#">
                    Próximamente
                </a>
                <?php require_once('formLogout.php'); ?>

            </div>
        </div>
    </nav>
</body>

</html>