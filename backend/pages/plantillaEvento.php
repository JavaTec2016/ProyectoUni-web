<?php
include_once('../model/model_evento.php');
function eventoCard(string $id, array $eventoDatos, string $imgPath = "assets/img/cogs.png"){

    ob_start();

?>
    <div class="card border-dark text-center mb-3 shadow evento-feed align-items-center" style="width: 14rem;" id="<?php echo $id ?>">
        <img src="<?php echo $imgPath ?>" alt="WIP" style="width: 80%;">
        <div class="card-body">
            <h5 class="card-title"><?php echo $eventoDatos[Evento::NOMBRE] ?></h5>
            <p class="card-text"> <? echo $eventoDatos[Evento::DESCRIPCION] ?></p>
            <p class="card-text"><small class="text-body-secondary"><?php echo $eventoDatos[Evento::FECHA_INICIO] . " - " . $eventoDatos[Evento::FECHA_FIN] ?></small></p>
        </div>
    </div>
<?php 
    return ob_get_clean();
}
?>