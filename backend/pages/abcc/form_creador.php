<?php
require_once('../../model/allModels.php');
require_once('abcc_manager.php');

class FormCreador
{
    public static $evento_tipos = array(
        "Fonoton" => "Fonoton",
        "Graduacion" => "Fiesta de graduacion",
        "Festival" => "Festival"
    );

    static function makeFormEvento(string $id)
    {
        ob_start();
?>
        <form id="<?php echo $id ?>">
            <div id="<?php echo $id ?>-body">
                <?php
                echo buildField(Evento::NOMBRE, "text", "Nombre del evento: ");
                echo buildField(Evento::FECHA_INICIO, "date", "Fecha de inicio: ");
                echo buildField(Evento::FECHA_FIN, "date", "Fecha de fin: ");
                echo buildField(Evento::TIPO, "select", "Tipo de evento: ", null, self::$evento_tipos); //selecccc
                echo buildField(Evento::DESCRIPCION, "text", "Descripcion: "); //textoarea
                ?>
            </div>
            <button class="btn btn-primary px-5 margin-half" type="submit" id="<?php echo $id ?>Submit">Enviar</button>
        </form>
    <?php
        return ob_get_clean();
    }

    static function makeFormCorporacion(string $id)
    {
        ob_start();
    ?>
        <form id="<?php echo $id ?>">
            <div id="<?php echo $id ?>-body">
                <?php
                echo buildField(Corporacion::NOMBRE, "text", "Nombre: ");
                echo buildField(Corporacion::DIRECCION, "text", "Direccion: ");
                echo buildField(Corporacion::TELEFONO, "tel", "Numero de telefono: ");
                echo buildField(Corporacion::EMAIL, "email", "Correo electronico: "); //selecccc
                ?>
            </div>
            <button class="btn btn-primary px-5 margin-half" type="submit" id="<?php echo $id ?>Submit">Enviar</button>
        </form>
    <?php
        return ob_get_clean();
    }

    static function makeFormGarantia(string $id)
    {
        ob_start();
    ?>
        <form id="<?php echo $id ?>">
            <div id="<?php echo $id ?>-body">
                <?php
                echo buildField(Corporacion::NOMBRE, "text", "Nombre: ");
                echo buildField(Corporacion::DIRECCION, "text", "Direccion: ");
                echo buildField(Corporacion::TELEFONO, "tel", "Numero de telefono: ");
                echo buildField(Corporacion::EMAIL, "email", "Correo electronico: "); //selecccc
                ?>
            </div>
            <button class="btn btn-primary px-5 margin-half" type="submit" id="<?php echo $id ?>Submit">Enviar</button>
        </form>
<?php
        return ob_get_clean();
    }
}
?>