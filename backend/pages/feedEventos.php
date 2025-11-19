<?php require_once('auth.php') ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php include_once('addBootstrap.php') ?>
    <?php include_once('styles.php') ?>
</head>

<body id="h">
    <?php require_once('plantillaEvento.php') ?>
    <?php require_once('navbar_feedEventos.php') ?>
    <div style="display:flex;">

        <div class="sticky-top side-nav" style="z-index:1019;">
            <div name="nav-filler"></div>
            <nav class="vh-100 bg-light abcc">
                <div clas="col">
                    <!-- cosas de eventos o volver al panel -->
                    <div class="row">

                    </div>
                    <!-- botones para cambiar entre tablas ABCC rapidito -->
                    <a class="row abcc_opcion" href="#">
                        <img src="assets/img/cogs.png" alt="">
                        <p>Eventos</p>
                    </a>
                    <a class="row abcc_opcion" href="#">
                        <img src="assets/img/cogs.png" alt="">
                        <p>Donadores</p>
                    </a>
                    <a class="row abcc_opcion" href="#">
                        <img src="assets/img/cogs.png" alt="">
                        <p>Asistencias</p>
                    </a>
                    <a class="row abcc_opcion" href="#">
                        <img src="assets/img/cogs.png" alt="">
                        <p>Donativos</p>
                    </a>
                    <a class="row abcc_opcion" href="#">
                        <img src="assets/img/cogs.png" alt="">
                        <p>Garantías</p>
                    </a>
                    <a class="row abcc_opcion" href="#">
                        <img src="assets/img/cogs.png" alt="">
                        <p>Clases</p>
                    </a>

                </div>
            </nav>
        </div>
        <div class="container" style="position: relative;">
            <div name="nav-filler"></div>
            <div class="row">
                <!-- PANEL DE EVENTOS -->
                <div class="col-sm-12">
                    <div class="no-overflow">
                        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4">



                            <!-- loop llenando las cartas de eventos -->
                            <?php
                            $placeholder = array(
                                Evento::NOMBRE => "evento",
                                Evento::DESCRIPCION => "evento placeholder WIP ahi va ya merito sale esto",
                                Evento::FECHA_INICIO => "11-11-11",
                                Evento::FECHA_FIN => "11-11-12"
                            );

                            for ($i = 0; $i < 16; $i++) {
                            ?>

                                <div class="col align-items-center">
                                    <?php
                                    echo eventoCard("evento_" . $i, $placeholder);
                                    ?>
                                </div>

                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
    const nav = document.getElementById('nav-feed');
    document.getElementsByName('nav-filler').forEach(elem => {
        elem.style.height = nav.offsetHeight + "px";
        console.log(nav.offsetHeight);
    })
</script>

</html>