<?php
require_once('abcc_plantilla.php');
require_once('../../model/model_evento.php');
?>

<!-- -->
<script>
    const form = document.getElementById("form");
    const formBody = document.getElementById("form-body");
    const tabla = document.getElementById("tablaResults");
    formBody.innerHTML = "";
    build = new FormBuilder();
    formBody.append(
        buildField("<?php echo Evento::NOMBRE ?>", undefined, "text", undefined, "Nombre del evento: "),
        buildField("<?php echo Evento::FECHA_INICIO ?>", undefined, "date", undefined, "Fecha de inicio: "),
        buildField("<?php echo Evento::FECHA_FIN ?>", undefined, "date", undefined, "Fecha de fin: "),
        buildField("<?php echo Evento::TIPO ?>", undefined, "text", undefined, "Tipo: "), //select
        buildField("<?php echo Evento::DESCRIPCION ?>", undefined, "text", undefined, "Tipo: "), //textarea
    );

    form.onsubmit = (ev) => {
        ev.preventDefault();
        
    }
</script>