<?php
    require_once('Modelo.php');
    class Donador extends Modelo {
        public static $rules = array();

        public final const ID = "id";
        public final const NOMBRE = "nombre";
        public final const DIRECCION = "direccion";
        public final const TELEFONO = "telefono";
        public final const EMAIL = "email";
        public final const CATEGORIA = "categoria";
        public final const ANIO_GRADUACION = "anio_graduacion";
        public final const ID_CLASE = "id_clase";
        public final const ID_CORPORACION = "id_corporacion";
        public final const NOMBRE_CONYUGE = "nombre_conyuge";
        public final const ID_CORPORACION_CONYUGE = "id_corporacion_conyuge";
        
        public function __construct(int $id = 0, string $nombre = "", string $direccion = "", string $telefono = "", string $email = "", string $categoria = "", int $anio_graduacion = 0, int $id_clase = 0, int $id_corporacion = 0, string $nombre_conyuge = "", int $id_corporacion_conyuge = 0) {
            $this->fillValuesFrom(array_keys(static::$rules), func_get_args());
        }
        public static function setRules(){
            
            static::addRule(self::ID, new DataRow("int", "INT", true, true, false, -1, -1, true, ""));
            static::addRule(self::NOMBRE, new DataRow("string", "VARCHAR", true, false, false, 0, 30, false, ""));
            static::addRule(self::DIRECCION, new DataRow("string", "VARCHAR", false, false, false, 0, 100, false, ""));
            static::addRule(self::TELEFONO, new DataRow("string", "VARCHAR", false, false, false, 0, 10, false, ""));
            static::addRule(self::EMAIL, new DataRow("string", "VARCHAR", false, false, false, 0, 100, false, ""));
            static::addRule(self::CATEGORIA, new DataRow("string", "VARCHAR", true, false, false, 0, 50, false, ""));
            static::addRule(self::ANIO_GRADUACION, new DataRow("int", "INT", true, false, false, -1, -1, false, ""));
            static::addRule(self::ID_CLASE, new DataRow("int", "INT", true, false, true, 0, 30, false, ""));
            static::addRule(self::ID_CORPORACION, new DataRow("int", "INT", false, false, true, -1, -1, false, ""));
            static::addRule(self::NOMBRE_CONYUGE, new DataRow("string", "VARCHAR", false, false, false, 0, 50, false, ""));
            static::addRule(self::ID_CORPORACION_CONYUGE, new DataRow("int", "INT", false, false, true, -1, -1, false, ""));
            
        }
    }
    Donador::setRules();
    Models::set("donador", new Donador());
?>