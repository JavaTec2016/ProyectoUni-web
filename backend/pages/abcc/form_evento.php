<?php
require_once('../../model/model_evento.php');
require_once('abcc_manager.php');

class FormEvento {
    public static $selectTipo = array(
        "Fonoton" => "Fonoton",
        "Graduacion" => "Fiesta de graduacion",
        "Festival" => "Festival"
    );

    static function makeFormEvento(string $id) {
      ob_start();
    ?>
        <form id="<?php echo $id ?>">
            <div id="<?php echo $id ?>-body">
                <?php
                echo buildField(Evento::NOMBRE, "text", "Nombre del evento: ");
                echo buildField(Evento::FECHA_INICIO, "date", "Fecha de inicio: ");
                echo buildField(Evento::FECHA_FIN, "date", "Fecha de fin: ");
                echo buildField(Evento::TIPO, "select", "Tipo de evento: ", null, self::$selectTipo); //selecccc
                echo buildField(Evento::DESCRIPCION, "text", "Descripcion: "); //textoarea
            ?>
        </div>
        <button class="btn btn-primary px-5 margin-half" type="submit" id="<?php echo $id ?>Submit">Enviar</button>
    </form>
    <?php
        return ob_get_clean();
    }
}
?>