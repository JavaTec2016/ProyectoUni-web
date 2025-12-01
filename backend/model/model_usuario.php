<?php
class Usuario extends Modelo {
    public static $rules = [];
    public final const NOMBRE = "nombre";
    public final const PASS = "pass";
    public final const ROL = "rol";

    public function __construct(string|null $nombre= null, string|null $pass= null, string|null $rol=null) {
        $this->fillValuesFrom(array_keys(static::$rules), func_get_args());
    }
    public static function setRules()
    {
        static::addRule(self::NOMBRE, new DataRow("string", "VARCHAR", true, true, false, 0, 100, false, Modelo::REGEX_ACENTO));
        static::addRule(self::PASS, new DataRow("string", "VARCHAR", true, false, false, 0, 100, false, "*"));
        static::addRule(self::ROL, new DataRow("string", "VARCHAR", true, false, false, 0, 30, false, Modelo::REGEX_ACENTO));
    }
}
Usuario::setRules();
Models::set("usuario", new Usuario());
?>