<?php
require_once('abcc_manager.php');

class FormCreador
{
    public static $evento_tipos = array(
        "Fonoton" => "Fonoton",
        "Graduacion" => "Fiesta de graduacion",
        "Festival" => "Festival"
    );
    public static $donador_categorias = array(
        "Graduado" => "Graduado",
        "Alumno" => "Alumno",
        "Padre" => "Padre",
        "Administrador" => "Administrador",
        "Personal Docente" => "Personal Docente",
        "Personal Administrativo" => "Personal Administrativo",
        "Corporación" => "Corporación",
        "Docente" => "Docente"
    );

    public static $usuario_roles = array(
        "admin"=>"Administrador",
        "Coordinador" => "Coordinador",
        "Voluntario" => "Voluntario",
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
            <button class="btn btn-primary px-2 ms-2" type="submit" id="<?php echo $id ?>Submit">Enviar</button>
            <button class="btn btn-secondary px-2 me-2" type="reset" id="<?php echo $id ?>Clear">Limpiar</button>
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
            <button class="btn btn-primary px-2 ms-2" type="submit" id="<?php echo $id ?>Submit">Enviar</button>
            <button class="btn btn-secondary px-2 me-2" type="reset" id="<?php echo $id ?>Clear">Limpiar</button>
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
                echo buildField(Garantia::ID_EVENTO, "button", "Evento al que pertenece: "); //configuracion posterior
                echo buildField(Garantia::ID_DONADOR, "button", "Donador: ");
                echo buildField(Garantia::GARANTIA, "decimal", "Donacion garantizada: ");
                //echo buildField(Garantia::PAGO_TOTAL, "decimal", "Ttotal pagado: "); auto
                echo buildField(Garantia::METODO_PAGO, "text", "Metodo de pago: ");
                //echo buildField(Garantia::NUMERO_PAGOS, "number", "Numero de pagos: "); auto
                echo buildField(Garantia::FECHA_INICIO, "date", "Fecha de registro: ");
                echo buildField(Garantia::FECHA_GARANTIA, "date", "Fecha de vencimiento: ");
                //echo buildField(Garantia::ID_CIRCULO, "select", "Circulo: "); auto
                ?>
            </div>
            <button class="btn btn-primary px-2 ms-2" type="submit" id="<?php echo $id ?>Submit">Enviar</button>
            <button class="btn btn-secondary px-2 me-2" type="reset" id="<?php echo $id ?>Clear">Limpiar</button>
        </form>
    <?php
        return ob_get_clean();
    }

    static function makeFormDonador(string $id)
    {
        $corporacionesSeleccion = array();
        $clasesSeleccion = array();
        ///consultar las clases y corporaciones pa los selects
        $dao = new DAO();

        $results = $dao->consultar("corporacion", array(Corporacion::ID, Corporacion::NOMBRE), null, null, [Corporacion::NOMBRE => DAO::ASCEND]);
        $results = $dao->assoc($results);
        foreach ($results as $result) {
            $corporacionesSeleccion[$result[Corporacion::ID]] = $result[Corporacion::NOMBRE];
        }
        $results = $dao->consultar("clase", array(Clase::ID, Clase::ANIO_GRADUCION), null, null, [Clase::ANIO_GRADUCION => DAO::ASCEND]);
        $results = $dao->assoc($results);
        foreach ($results as $result) {
            $clasesSeleccion[$result[Clase::ID]] = $result[Clase::ANIO_GRADUCION];
        }

        ob_start();
    ?>
        <form id="<?php echo $id ?>">
            <div id="<?php echo $id ?>-body">
                <?php
                echo buildField(Donador::NOMBRE, "text", "Nombre: ");
                echo buildField(Donador::DIRECCION, "text", "Dirección: ");
                echo buildField(Donador::TELEFONO, "number", "Telefono: ");
                echo buildField(Donador::EMAIL, "email", "Correo electrónico: ");
                echo buildField(Donador::CATEGORIA, "select", "Categoria: ", null, self::$donador_categorias);
                echo buildField(Donador::ANIO_GRADUACION, "number", "Año de gradiación: ");
                echo buildField(Donador::ID_CLASE, "select", "Clase a la que pertenece: ", null, $clasesSeleccion);
                echo buildField(Donador::ID_CORPORACION, "select", "Corporación afiliada: ", null, $corporacionesSeleccion);
                echo buildField(Donador::NOMBRE_CONYUGE, "text", "Nombre del conyuge: ");
                echo buildField(Donador::ID_CORPORACION_CONYUGE, "select", "Corporación del conyuge: ", null, $corporacionesSeleccion);
                ?>
            </div>
            <button class="btn btn-primary px-2 ms-2" type="submit" id="<?php echo $id ?>Submit">Enviar</button>
            <button class="btn btn-secondary px-2 me-2" type="reset" id="<?php echo $id ?>Clear">Limpiar</button>
        </form>
    <?php
        return ob_get_clean();
    }
    static function makeFormClase(string $id)
    {
        ob_start();
    ?>
        <form id="<?php echo $id ?>">
            <div id="<?php echo $id ?>-body">
                <?php
                echo buildField(Clase::ANIO_GRADUCION, "number", "Año de graduación: ");
                ?>
            </div>
            <button class="btn btn-primary px-2 ms-2" type="submit" id="<?php echo $id ?>Submit">Enviar</button>
            <button class="btn btn-secondary px-2 me-2" type="reset" id="<?php echo $id ?>Clear">Limpiar</button>
        </form>
    <?php
        return ob_get_clean();
    }

    static function makeFormUsuario(string $id)
    {
        ob_start();
    ?>
        <form id="<?php echo $id ?>">
            <div id="<?php echo $id ?>-body">
                <?php
                echo buildField(Usuario::NOMBRE, "text", "Nombre: ");
                echo buildField(Usuario::PASS, "text", "Contraseña: ");
                echo buildField(Usuario::ROL, "select", "Rol: ", null, self::$usuario_roles);
                ?>
            </div>
            <button class="btn btn-primary px-2 ms-2" type="submit" id="<?php echo $id ?>Submit">Enviar</button>
            <button class="btn btn-secondary px-2 me-2" type="reset" id="<?php echo $id ?>Clear">Limpiar</button>
        </form>
<?php
        return ob_get_clean();
    }
}
?>