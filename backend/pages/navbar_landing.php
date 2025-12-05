<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>navobar</title>
    <?php include_once('addBootstrap.php') ?>
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><img src="assets/img/logo.webp" height="40" alt="logo"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarText">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#body">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#eventos">Eventos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#footer">Contacto</a>
                    </li>
                </ul>
                
                <a class="navbar-text me-3" href="#">
                    Solicitar registro
                </a>
                <form class="d-flex" action="logout">
                    <button class="btn btn-outline-info" type="submit">Acceso</button>
                </form>
                
            </div>
        </div>
    </nav>
</body>

</html>