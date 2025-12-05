<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <?php include_once('styles.php') ?>
</head>

<body style="background-color: rgba(240,240,255, 1);" id="body">
    <?php include_once('navbar_landing.php') ?>
    <div class="container-fluid bg-white">
        <div class="d-flex justify-content-center pt-5">
            <img src="assets/img/logo.webp" width="220px" alt="WIP">
        </div>
        <h1 class="display-1 text-center pb-5 pt-5">
            Eventos universitarios para Colectas y Donativos de la Institucion
        </h1>
    </div>
    <div class="container-fluid" id="eventos">
        <h3 class="display-5 text-center pt-4">
            Información de los eventos más recientes
        </h3>
        <?php include_once('carrusel.php'); ?>
    </div>

    <footer id="footer" class="pt-4 pb-4 bg-white">
        <div class="container">
            <div class="row text-center">
                <div class="col-12 col-md"><a href="#acrobatec">Contacto</a></div>
                <div class="col-12 col-md"><a href="#acrobatec">¿Quiénes somos?</a></div>
                <div class="col-12 col-md"><a href="#acrobatec">Mapa del sitio</a></div>
            </div>
            <div class="row text-center">
                <div class="col pt-4 pb-4">
                    <a href="#" data-bs-toggle="tooltip" title="Facebook" data-bs-placement="left"><i
                            class="fa-brands fa-square-facebook fa-3x ps-3 pe-3"></i></a>
                    <a href="#" data-bs-toggle="tooltip" title="Instagram" data-bs-placement="bottom"><i
                            class="fa-brands fa-square-instagram fa-3x ps-3 pe-3"></i></a>
                    <a href="#" data-bs-toggle="tooltip" title="Tik Tok" data-bs-placement="bottom"><i
                            class="fa-brands fa-tiktok fa-3x ps-3 pe-3"></i></a>
                    <a href="#" data-bs-toggle="tooltip" title="X/Twitter" data-bs-placement="bottom"><i
                            class="fa-brands fa-square-x-twitter fa-3x ps-3 pe-3"></i></a>
                    <a href="#" data-bs-toggle="tooltip" title="YouTube" data-bs-placement="right"><i
                            class="fa-brands fa-square-youtube fa-3x ps-3 pe-3"></i></a>
                </div>
            </div>

            <div class="row text-center">
                <div class="col pb-1 pt-4">
                    <img src="assets/img/logo.webp" alt="logo Universidad Beta" width="75px" height="75px">
                </div>
            </div>
            <div class="row text-center">
                <div class="col pb-2 pt-4">
                    <p>&copy;2025 - Universidad Beta</p>
                    <p>Reservados todos los derechos</p>
                </div>
            </div>
        </div>
    </footer>
</body>

</html>