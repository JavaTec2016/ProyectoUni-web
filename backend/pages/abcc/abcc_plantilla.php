<?php
header("location: /proyesto/backend/pages/feedEventos.php");
exit(0);
require_once('../auth.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php include_once('../addBootstrap.php') ?>
    <?php include_once('../styles.php') ?>
</head>

<body>
    <?php require_once('nav_abcc.php') ?>
    <div style="display:flex;">

        <!-- BARRA LATERAL -->

        <div class="sticky-top side-nav" style="z-index:1019;">
            <div name="nav-filler"></div>

            <!-- NAV FORMULARIO -->

            <nav class="vh-100 bg-light abcc">
                <div clas="col">
                    <div class="row" id="form-header">
                        <form id="form">
                            <div id="form-body">

                            </div>
                            <button type="submit" id="formSubmit">Enviar</button>
                        </form>
                    </div>
                </div>
            </nav>
        </div>

        <!-- CONTENEDOR DE TABLA Y ESAS COSAS -->

        <div class="container" style="position: relative;">
            <div name="nav-filler"></div>
            <div class="row">
                <div class="col-sm-12">

                    <!-- la tabla en cuestion -->

                    <table class="table table-hover" id="tablaResults">
                        <thead id="tablaResults-header"></thead>
                        <tbody id="tablaResults-body"></tbody>
                    </table>
                </div>
            </div>
        </div>
</body>
<script src="../../middleware/scripTabla.js"></script>
<script src="../../middleware/request.js"></script>
<script src="../../middleware/showToast.js"></script>

</html>