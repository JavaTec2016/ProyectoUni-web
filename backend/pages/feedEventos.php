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
    <?php require_once('../controller/DAO.php') ?>
    <div style="display:flex;height: 100%;">

        <div class="side-nav" style="z-index:1019">
            <nav class="bg-light abcc scroller">
                <div clas="col">
                    <!-- cosas de eventos o volver al panel -->
                    <div class="row">

                    </div>
                    <!-- botones para cambiar entre tablas ABCC rapidito -->
                    <a class="row abcc_opcion" href="abcc/abcc_evento.php">
                        <img src="assets/img/cogs.png" alt="">
                        <p>Eventos</p>
                    </a>
                    <a class="row abcc_opcion" href="abcc/abcc_Corporacion.php">
                        <img src="assets/img/cogs.png" alt="">
                        <p>Corporaciones</p>
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
            <div class="row scroller" style="height: 90vh;">
                <!-- BANNER -->
                <div class="col-sm-12">
                    <h1 class="display-5 text-center pt-5">Eventos más recientes</h1>
                    <p class="text-center pb-5">Presione en un evento para ver detalles</p>
                </div>
                <!-- PANEL DE EVENTOS -->
                <div class="col-sm-12">
                    <div class="no-overflow">
                        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4">



                            <!-- loop llenando las cartas de eventos -->
                            <?php
                            $datos = array(
                                Evento::NOMBRE => 1,
                                Evento::DESCRIPCION => 2,
                                Evento::FECHA_INICIO => 3,
                                Evento::FECHA_FIN => 4
                            );
                            $dao = new DAO();
                            $result = $dao->assoc($dao->consultar("evento", array_keys($datos), null, null, array(Evento::FECHA_INICIO => DAO::DESCEND), 16));

                            foreach ($result as $idx => $registro) {
                            ?>
                                <div class="col align-items-center">
                                    <?php
                                    echo eventoCard("evento_" . $idx, $registro);
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
<script src="../middleware/navFix.js"></script>
<script>
    setFiller('nav-feed');
</script>

</html>