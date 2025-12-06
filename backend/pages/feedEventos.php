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
    <?php include_once('backend/controller/GetUserPDAO.php'); ?>
    <?php

    if(!isset($_SESSION) || !$_SESSION['autenticado'] || $_SESSION['rol'] != 'admin'){}
    else require_once('backend/pages/usuariosLateral.php');
    ?>
    <!-- DETALLES DE EVENTO SI -->

    <div class="modal" tabindex="-1" id="modalEvento" aria-hidden="false">
        <div class="modal-dialog">
            <div class="modal-content" id="modalEvento_content">
                <div class="modal-header">
                    <h5 class="modal-title"> Detalles del evento </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!--modal interior -->
                <div class="modal-body" id="navEvento">
                    <table class="table table-hover" id="detallesEvento_table">

                    </table>
                </div>


                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>


    <div style="display:flex;height: 100%;">

        <div class="side-nav" style="z-index:1019">
            <nav class="bg-light abcc scroller">
                <div clas="col">
                    <!-- cosas de eventos o volver al panel -->
                    <div class="row">

                    </div>
                    <!-- botones para cambiar entre tablas ABCC rapidito -->
                    <a class="row abcc_opcion" href="abcc_evento">
                        <img src="assets/img/cogs.png" alt="">
                        <p>Eventos</p>
                    </a>
                    <a class="row abcc_opcion" href="abcc_corporacion">
                        <img src="assets/img/cogs.png" alt="">
                        <p>Corporaciones</p>
                    </a>
                    <a class="row abcc_opcion" href="abcc_donador">
                        <img src="assets/img/cogs.png" alt="">
                        <p>Donadores</p>
                    </a>
                    <a class="row abcc_opcion" href="#">
                        <img src="assets/img/cogs.png" alt="">
                        <p>Donativos</p>
                    </a>
                    <a class="row abcc_opcion" href="abcc_garantia">
                        <img src="assets/img/cogs.png" alt="">
                        <p>Garantías</p>
                    </a>
                    <a class="row abcc_opcion" href="abcc_clases">
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
                                Evento::ID => 0,
                                Evento::NOMBRE => 1,
                                Evento::DESCRIPCION => 2,
                                Evento::FECHA_INICIO => 3,
                                Evento::FECHA_FIN => 4,
                                Evento::TIPO => 5,
                            );
                            $dao = getUserPDAO(conexionPDO::BD_USER);
                            $result = $dao->consultar("evento", array_keys($datos), null, null, array(Evento::FECHA_INICIO => PDAO::DESCEND), 16);
                            if(count($result) == 0){
                                echo '<i class="text-center pb-5">No hay eventos recientes.</i>';
                            }
                            foreach ($result as $idx => $registro) {
                            ?>
                                <div class="col align-items-center">
                                    <?php
                                    $imgPath = "backgroundFestival.webp";
                                    if($registro[Evento::TIPO] == 'Graduacion') $imgPath = "backgroundGraduacion.webp";
                                    if ($registro[Evento::TIPO] == 'Fonoton') $imgPath = "backgroundFonoton.webp";
                                    echo eventoCard("evento_" . $registro[Evento::ID], $registro, "assets/img/".$imgPath);
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
<script src="js/scripTabla.js"></script>
<script src="js/request.js"></script>
<script src="js/showToast.js"></script>
<script src="js/scripForm.js"></script>
<script src="js/ABCCUtils.js"></script>
<script>
    const feeds = [...document.getElementsByClassName('evento-feed')];
    const tablaModal = document.getElementById("detallesEvento_table");
    const camposIds = {
        id: "<?php echo Evento::ID ?>",
        nombre: "<?php echo Evento::NOMBRE ?>",
        fechaInicio: "<?php echo Evento::FECHA_INICIO ?>",
        fechaFin: "<?php echo Evento::FECHA_FIN ?>",
        tipo: "<?php echo Evento::TIPO ?>",
        descripcion: "<?php echo Evento::DESCRIPCION ?>"
    }

    feeds.forEach(
        /**@param {HTMLDivElement} feed*/
        feed => {
            feed.onclick = (ev) => {
                let id = parseInt(feed.id.split("_")[1]);
                crearBody(tablaModal);
                setBodyHTML(tablaModal, "Cargando...");
                consultar("api_mysql_consultas.php?tabla=evento&id=" + id, null,
                    (result) => {
                        console.log(result, id);
                        let modelo = result.resultSet[0];
                        console.log(modelo);
                        setBodyHTML(tablaModal, "");
                        const headers = {};
                        headers[camposIds.id] = "ID: ";
                        headers[camposIds.nombre] = "Nombre: ";
                        headers[camposIds.fechaInicio] = "fecha de inicio: ";
                        headers[camposIds.fechaFin] = "fecha de fin: ";
                        headers[camposIds.tipo] = "Tipo: ";
                        headers[camposIds.descripcion] = "Descripcion: ";
                        agregarRow(tablaModal, modelo, modelo.id, headers);
                    },
                    (reason) => {
                        setBodyHTML(tablaModal, "No se pudieron cargar los datos del evento");
                    }
                )
            }
        })
</script>

</html>