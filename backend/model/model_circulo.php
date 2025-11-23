<?php

class Circulo extends Modelo
{
    public static $rules = [];

    public final const ID = "id";
    public final const NOMBRE = "nombre";
    public final const MONTO_MINIMO = "monto_minimo";

    public function __construct(int|null $id = null, string|null $nombre = null, float|null $monto_minimo = null)
    {
        $this->fillValuesFrom(array_keys(static::$rules), func_get_args());
    }
    public static function setRules()
    {
        static::addRule(self::ID, new DataRow("int", "INT", true, true, false, -1, -1, true, Modelo::REGEX_INTEGER));
        static::addRule(self::NOMBRE, new DataRow("string", "STRING", true, false, false, -1, -1, true, Modelo::REGEX_AZ_SPACE));
        static::addRule(self::MONTO_MINIMO, new DataRow("double", "DECIMAL", true, false, false, -1, -1, false, Modelo::REGEX_DECIMAL));
    }
}

Circulo::setRules();
Models::set("circulo", new Circulo());

?>