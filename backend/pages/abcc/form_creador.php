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
        "admin" => "Administrador",
        "Coordinador" => "Coordinador",
        "Voluntario" => "Voluntario",
    );

    public static $garantia_estados = array(
        "Pendiente" => "Pendiente",
        "Completada" => "Completada",
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
            <?php if ($_SESSION['rol'] == 'admin') { ?>
                <button class="btn btn-primary px-2 ms-2" type="submit" id="<?php echo $id ?>Submit">Enviar</button>
            <?php } ?>
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
                echo buildField(Corporacion::TELEFONO, "text", "Numero de telefono: ", null, null, "", 10);
                echo buildField(Corporacion::EMAIL, "email", "Correo electronico: "); //selecccc
                ?>
            </div>
            <?php if ($_SESSION['rol'] == 'admin') { ?>
                <button class="btn btn-primary px-2 ms-2" type="submit" id="<?php echo $id ?>Submit">Enviar</button>
            <?php } ?>
            <button class="btn btn-secondary px-2 me-2" type="reset" id="<?php echo $id ?>Clear">Limpiar</button>
        </form>
    <?php
        return ob_get_clean();
    }

    static function makeFormGarantia(string $id)
    {

        $donadores = array();
        $eventos = array();
        $dao = getUserPDAO();
        $results = $dao->consultar("donador", array(Donador::ID, Donador::NOMBRE));
        foreach ($results as $result) {
            $donadores[$result[Donador::ID]] = $result[Donador::NOMBRE];
        }

        $results = $dao->consultar("evento", array(Evento::ID, Evento::TIPO, Evento::NOMBRE));
        foreach ($results as $result) {
            $eventos[$result[Evento::ID]] = $result[Evento::TIPO] . ": " . $result[Evento::NOMBRE];
        }
        ob_start();
    ?>
        <form id="<?php echo $id ?>">
            <div id="<?php echo $id ?>-body">
                <?php
                echo buildField(Garantia::ID_EVENTO, "select", "Evento al que pertenece: ", null, $eventos); //configuracion posterior
                echo buildField(Garantia::ID_DONADOR, "select", "Donador: ", null, $donadores);
                echo buildField(Garantia::GARANTIA, "decimal", "Donacion garantizada: ");
                //echo buildField(Garantia::PAGO_TOTAL, "decimal", "Ttotal pagado: "); auto
                echo buildField(Garantia::METODO_PAGO, "text", "Metodo de pago: ");
                //echo buildField(Garantia::NUMERO_PAGOS, "number", "Numero de pagos: "); auto
                echo buildField(Garantia::FECHA_INICIO, "date", "Fecha de registro: ");
                echo buildField(Garantia::FECHA_GARANTIA, "date", "Fecha de vencimiento: ");
                //echo buildField(Garantia::ID_CIRCULO, "select", "Circulo: "); auto
                ?>
            </div>
            <?php if ($_SESSION['rol'] == 'admin') { ?>
                <button class="btn btn-primary px-2 ms-2" type="submit" id="<?php echo $id ?>Submit">Enviar</button>
            <?php } ?>
            <button class="btn btn-secondary px-2 me-2" type="reset" id="<?php echo $id ?>Clear">Limpiar</button>
        </form>
    <?php
        return ob_get_clean();
    }

    static function makeFormDonador(string $id)
    {
        $corporacionesSeleccion = array();
        $clasesSeleccion = array();
        $categoriasSeleccion = array();
        ///consultar las clases y corporaciones pa los selects
        $dao = getUserPDAO();

        $results = $dao->consultar("corporacion", array(Corporacion::ID, Corporacion::NOMBRE), null, null, [Corporacion::NOMBRE => PDAO::ASCEND]);
        foreach ($results as $result) {
            $corporacionesSeleccion[$result[Corporacion::ID]] = $result[Corporacion::NOMBRE];
        }
        $results = $dao->consultar("clase", array(Clase::ID, Clase::ANIO_GRADUCION), null, null, [Clase::ANIO_GRADUCION => PDAO::ASCEND]);
        foreach ($results as $result) {
            $clasesSeleccion[$result[Clase::ID]] = $result[Clase::ANIO_GRADUCION];
        }
        $results = $dao->consultar("donador_categoria", array(Donador_Categoria::NOMBRE), null, null, [Donador_Categoria::NOMBRE => PDAO::ASCEND]);
        foreach ($results as $result) {
            $categoriasSeleccion[$result[Donador_Categoria::NOMBRE]] = $result[Donador_Categoria::NOMBRE];
        }

        ob_start();
    ?>
        <form id="<?php echo $id ?>">
            <div id="<?php echo $id ?>-body">
                <?php
                echo buildField(Donador::NOMBRE, "text", "Nombre: ");
                echo buildField(Donador::DIRECCION, "text", "Dirección: ");
                echo buildField(Donador::TELEFONO, "text", "Telefono: ", null, null, "", 10);
                echo buildField(Donador::EMAIL, "email", "Correo electrónico: ");
                //echo buildField(Donador::CATEGORIA, "select", "Categoria: ", null, $categoriasSeleccion);
                echo buildField(Donador::ANIO_GRADUACION, "number", "Año de gradiación: ");
                echo buildField(Donador::ID_CLASE, "select", "Clase a la que pertenece: ", null, $clasesSeleccion);
                echo buildField(Donador::ID_CORPORACION, "select", "Corporación afiliada: ", null, $corporacionesSeleccion);
                echo buildField(Donador::NOMBRE_CONYUGE, "text", "Nombre del conyuge: ");
                echo buildField(Donador::ID_CORPORACION_CONYUGE, "select", "Corporación del conyuge: ", null, $corporacionesSeleccion);
                self::$donador_categorias = $categoriasSeleccion;
                ?>
            </div>
            <?php if ($_SESSION['rol'] == 'admin') { ?>
                <button class="btn btn-primary px-2 ms-2" type="submit" id="<?php echo $id ?>Submit">Enviar</button>
            <?php } ?>
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
            <?php if ($_SESSION['rol'] == 'admin') { ?>
                <button class="btn btn-primary px-2 ms-2" type="submit" id="<?php echo $id ?>Submit">Enviar</button>
            <?php } ?>
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
                echo buildField(Usuario::NOMBRE, "text", "Nombre: ", null, null, "", 100);
                echo buildField(Usuario::PASS, "text", "Contraseña: ", null, null, "", 100);
                echo buildField(Usuario::ROL, "select", "Rol: ", null, self::$usuario_roles);
                ?>
            </div>
            <?php if ($_SESSION['rol'] == 'admin') { ?>
                <button class="btn btn-primary px-2 ms-2" type="submit" id="<?php echo $id ?>Submit">Enviar</button>
            <?php } ?>
            <button class="btn btn-secondary px-2 me-2" type="reset" id="<?php echo $id ?>Clear">Limpiar</button>
        </form>
    <?php
        return ob_get_clean();
    }

    static function makeFormGarantiaDonadorEvento(string $id)
    {

        $garantias = array();
        $donadores = array();
        $eventos = array();
        $circulos = array();
        ///consultar garantias pa seleccionar
        $dao = getUserPDAO();

        $results = $dao->consultar("garantia", array(Garantia::ID, Garantia::GARANTIA));
        foreach ($results as $result) {
            $garantias[$result[Garantia::ID]] = $result[Garantia::GARANTIA];
        }

        $results = $dao->consultar("donador", array(Donador::ID, Donador::NOMBRE));
        foreach ($results as $result) {
            $donadores[$result[Donador::ID]] = $result[Donador::NOMBRE];
        }
        $results = $dao->consultar("evento", array(Evento::ID, Evento::NOMBRE));
        foreach ($results as $result) {
            $eventos[$result[Evento::ID]] = $result[Evento::NOMBRE];
        }
        $results = $dao->consultar("circulo", array(Circulo::ID, Circulo::NOMBRE));
        foreach ($results as $result) {
            $circulos[$result[Circulo::ID]] = $result[Circulo::NOMBRE];
        }

        ob_start();
    ?>
        <form id="<?php echo $id ?>">
            <div id="<?php echo $id ?>-body">
                <?php
                echo buildField(model_garantia_donador_evento::$aliases["garantia"][Garantia::ID], "select", "Garantia: ", null, $garantias);
                echo buildField(Garantia::ID_DONADOR, "select", "Donador: ", null, $donadores);
                echo buildField(Garantia::ID_EVENTO, "select", "Evento: ", null, $eventos);
                echo buildField(Garantia::GARANTIA, "number", "Monto garantizado: ", null, null, "", 13);
                //echo buildField(Garantia::PAGO_TOTAL, "number", "Pago total: ");
                //echo buildField(Garantia::METODO_PAGO, "text", "Método de pago: ");
                //echo buildField(Garantia::NUMERO_PAGOS, "number", "Número: ");
                //echo buildField(Garantia::NUMERO_TARJETA, "number", "Número de tarjeta: ");
                //echo buildField(Garantia::FECHA_INICIO, "date", "Fecha de inicio: ");
                //echo buildField(Garantia::FECHA_GARANTIA, "date", "Fecha límite: ");
                //echo buildField(Garantia::ID_CIRCULO, "select", "Círculo: ", null, $circulos);
                //echo buildField(Garantia::ESTADO, "select", "Estado de la garantía: ", null, self::$garantia_estados);
                //echo buildField(Donador::NOMBRE, "text", "Nombre del donador: ", null, self::$garantia_estados);
                ?>
            </div>
            <button class="btn btn-secondary px-2 me-2" type="reset" id="<?php echo $id ?>Clear">Limpiar</button>
        </form>
    <?php
        return ob_get_clean();
    }

    static function makeFormDonadorCategoria(string $id)
    {

        ob_start();
    ?>
        <form id="<?php echo $id ?>">
            <div id="<?php echo $id ?>-body">
                <?php
                echo buildField(Donador_Categoria::NOMBRE, "text", "Nombre de la categoria: ", null, null, "", 50);
                ?>
            </div>
            <?php if ($_SESSION['rol'] == 'admin') { ?>
                <button class="btn btn-primary px-2 ms-2" type="submit" id="<?php echo $id ?>Submit">Enviar</button>
            <?php } ?>
            <button class="btn btn-secondary px-2 me-2" type="reset" id="<?php echo $id ?>Clear">Limpiar</button>
        </form>
    <?php
        return ob_get_clean();
    }

    static function makeFormCirculo(string $id)
    {
        ob_start();
    ?>
        <form id="<?php echo $id ?>">
            <div id="<?php echo $id ?>-body">
                <?php
                echo buildField(Circulo::NOMBRE, "text", "Nombre: ", null, null, "", 100);
                echo buildField(Circulo::MONTO_MINIMO, "text", "Contraseña: ", null, null, "", 13);
                ?>
            </div>
            <?php if ($_SESSION['rol'] == 'admin') { ?>
                <button class="btn btn-primary px-2 ms-2" type="submit" id="<?php echo $id ?>Submit">Enviar</button>
            <?php } ?>
            <button class="btn btn-secondary px-2 me-2" type="reset" id="<?php echo $id ?>Clear">Limpiar</button>
        </form>
<?php
        return ob_get_clean();
    }
}
?>