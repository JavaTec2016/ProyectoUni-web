<?php
    require_once('Modelo.php');
    class Corporacion extends Modelo {
        
        public static $rules = array();

        public final const ID = "id";
        public final const NOMBRE = "nombre";
        public final const DIRECCION = "direccion";
        public final const TELEFONO = "telefono";
        public final const EMAIL = "email";

        public function __construct(int $id=0, string $nombre="", string $direccion="", string $telefono="", string $email="") {
            $this->fillValuesFrom(array_keys(static::$rules), func_get_args());
        }
        public static function setRules(){
            
            static::addRule(self::ID, new DataRow("int", "INT", true, true, false, -1, -1, true, Modelo::REGEX_INTEGER));
            static::addRule(self::NOMBRE, new DataRow("string", "VARCHAR", true, false, false, 0, 100, false, Modelo::REGEX_ACENTO));
            static::addRule(self::DIRECCION, new DataRow("string", "VARCHAR", true, false, false, 0, 200, false, Modelo::REGEX_NO_SPECIAL));
            static::addRule(self::TELEFONO, new DataRow("string", "VARCHAR", false, false, false, 0, 10, false, Modelo::REGEX_INTEGER));
            static::addRule(self::EMAIL, new DataRow("string", "VARCHAR", false, false, false, 0, 50, false, Modelo::REGEX_EMAIL));

        }
    }
    Corporacion::setRules();
    Models::set("corporacion", new Corporacion());
?>