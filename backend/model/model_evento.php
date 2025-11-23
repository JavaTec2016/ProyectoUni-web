<?php 
require_once('Modelo.php');
class Evento extends Modelo {
    public static $rules = [];

    public final const ID = "id";
    public final const NOMBRE = "nombre";
    public final const FECHA_INICIO = "fecha_inicio";
    public final const FECHA_FIN = "fecha_fin";
    public final const TIPO = "tipo";
    public final const DESCRIPCION ="descripcion";

    public function __construct(int|null $id=null, string|null $nombre=null, string|null $fecha_inicio=null, string|null $fecha_fin=null, string|null $tipo=null, string|null $descripcion=null){
        $this->fillValuesFrom(array_keys(static::$rules), func_get_args());
    }

    public static function setRules(){
        static::addRule(self::ID, new DataRow("int", "INT", true, true, false, -1, -1, true, Modelo::REGEX_INTEGER));
        static::addRule(self::NOMBRE, new DataRow("string", "VARCHAR", true, false, false, 0, 100, false, Modelo::REGEX_ACENTO));
        static::addRule(self::FECHA_INICIO, new DataRow("date", "DATE", true, false, false, -1, -1, false, Modelo::CHECK_DATE));
        static::addRule(self::FECHA_FIN, new DataRow("date", "DATE", false, false, false, -1, -1, false, Modelo::CHECK_DATE));
        static::addRule(self::TIPO, new DataRow("string", "VARCHAR", true, false, false, 0, 50, false, Modelo::REGEX_ACENTO));
        static::addRule(self::DESCRIPCION, new DataRow("string", "TEXT", true, false, false, -1, -1, false, Modelo::REGEX_ACENTO));
    }
}
Evento::setRules();
Models::set("evento", new Evento());

?>